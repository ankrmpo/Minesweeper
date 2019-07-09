<?php require_once __DIR__ . '/_header.php'; 
    if(!isset($_SESSION)) session_start();
    $username=$_SESSION['username'];
?>
    <link rel="stylesheet" type="text/css" href="view/style/wait.css">
</head>
<body>
    <div class="game">
    </div>
    <div id="no">
        <div class="text"></div>
    </div>
    <br><br>
    <button type="button" class="exit">Exit the game</button>

    <script>
        //Znam da ima puno komentara i da su neki predetaljni,ali kad nismo skupa fizički, ne mogu ti drukčije objasnit svoje ideje...
        //E i ja mislim da server na ovaj način ni ne treba znat kome šalje,samo šalje poruku ovisno o svojim varijablama i tipu poruke,znači stvarno kao chat
        //username šaljemo serveru sa svakom porukom da zna koji igrač treba staviti u polje na početku i kome treba dodijeliti/oduzeti bodove
        var username="<?php echo $username; ?>";
        var timestamp1 = 0, timestamp2 = 0;
        //moja označava zastavicu ovog usera,server nam ju daje ukoliko je IWantToJoin uspjesan
        var moja=-2;
        var bodovi=0;
        //zastavice svih igrača,moja je jedna od njih,ovo je ako imamo 4 igrača/ili možemo staviti da dohvaćamo ovo polje sa servera
        var zastavice = Array();
        $(document).ready(function()
        {
            //prvo šaljemo serveru da se želimo pridružiti
            IWantToJoin();
            //ukoliko smo odigrali potez klikom na field koji se sastoji od polja(sva polja su iste klase)
            $(document).on('mousedown','.polja',OdigrajPotez);
            //ako kliknemo na izlaz iz igre
            $(".exit").on( "click", ExitTheGame);        
        });

        function IWantToJoin()
        {
            $.ajax(
            {
                url: "view/serve.php",
                data:
                {
                    username: username,
                    whoSent: "IWantToJoin",
                },
                dataType: "json",
                success: function( data )
                {
                    console.log( "IWantToJoin :: success :: data = " + JSON.stringify( data ) );
                    if( typeof( data.error ) === "undefined" )
                    {
                        //ako server vrati string full,ispisujemo sorry poruku
                        if(data.flag=="full")
                        {
                            console.log(data.flag);
                            IspisiNemaMjesta();
                        }
                        //ako vrati indeks zastavice, uspješno smo se prijavili i čekamo ostale
                        else if(!isNaN(data.flag)) // nije vratio full pa provjeri je li dobiven indeks zastavice
                        {
                            var fl = data.flag;
                            fl = Number(fl);
                            zastavice = data.flags;
                            if(fl >= 0 && fl <= 3)
                            {
                                moja = zastavice[fl]; // dobio zastavicu i čeka početak igre
                                console.log(moja);
                                CanWeStart();
                            }
                        }
                    }
                    //ako je greška probamo opet
                    else
                    {
                        console.log(data.error);
                        IWantToJoin();
                    }
                },
                error: function( xhr, status )
                {
                    console.log( "IWantToJoin :: error :: status = " + status );
                    //ako je greška probamo opet
                    // if( status === "timeout" )
                    //     IWantToJoin();
                }
            });
        }
        
        function CanWeStart()
        {
            $.ajax(
            {
                url: "view/serve.php",
                data:
                {
                    username: username,
                    timestamp: timestamp1,
                    whoSent: "CanWeStart"
                },
                dataType: "json",
                success: function( data )
                {
                    console.log( "CanWeStart :: success :: data = " + JSON.stringify( data ) );
                    if( typeof( data.error ) === "undefined" )
                    {
                        timestamp1 = data.timestamp;
                        if(data.response=="no") CanWeStart();
                        else CheckGameStatus();
                    }
                    else CanWeStart();
                },
                error: function( xhr, status )
                {
                    console.log( "CanWeStart :: error :: status = " + status );
                    if( status === "timeout" )
                        CanWeStart();
                }
            });
        }
        //sorry stranica s gumbom za natrag,sve je već css-ano
        function IspisiNemaMjesta()
        {
            var text="The game is occupied, we are so sorry...";
            $(".text").append(document.createTextNode(text));
            $('#no').append('<br><br>');
            $('#no').append('<img id="sorry" src="view/style/sorry.jpg" />');
            $('#no').append('<br><br>');
            $('#no').append('<a class="back" href="choose.php">Back to menu</a>');
        }
        //ovo će stalno zahtijevati od servera da pošalje trenutačno stanje igre
        function CheckGameStatus()
        {
            $.ajax(
            {
                url: "view/serve.php",
                dataType: "json",
                data:
                {
                    //ovo šaljemo samo da server zna da ga ne pita netko tko nije dio igre
                    username:username,
                    timestamp: timestamp2,
                    whoSent: "CheckGameStatus",
                    cache: new Date().getTime()
                },
                success: function( data )
                {
                    console.log( "CheckGameStatus :: success :: data = " + JSON.stringify( data ) );
                    if( typeof( data.error ) === "undefined" )
                    {
                        timestamp2 = data.timestamp;
                        bodovi=data.bodovi;
                        IscrtajField(JSON.parse(data.field));
                        if(data.msg=="game-over") IscrtajGameOver();
                        
                        CheckGameStatus();
                    }
                    else CheckGameStatus();
                },
                error: function( xhr, status )
                {
                    console.log( "CheckGameStatus :: error :: status = " + status );
                    if( status === "timeout" )
                        CheckGameStatus();
                }
            });
        }
        
        //crta trenutno stanje
        function IscrtajField(field)
        {
            $(".game").html("");
            var size=field.length;
            var table=$("<table>").attr('class',"field");
            for(var i=0;i<size;++i)
            {
                var tr=$("<tr class='row'>");
                for(var j=0;j<size;++j)
                {
                    var td=$("<td class='cell'>");
                    //svima damo istu klasu da gore provjerimo jel kliknuto,tj je li netko odigrao potez
                    var polje=$("<button type='button' id='"+i+','+j+"' class='polja'></button>");
                    $(polje).on("contextmenu", function() {return false;} );
                    //-1 je bomba
                    if(field[i][j]==-1)
                    {
                        $(polje).html("💣");
                        $(polje).attr("disabled", "disabled");
                    }
                    //9 je ono sivo polje koje se samo otvorilo uz brojeve,ne možemo ga kliknut
                    else if(field[i][j]==9)
                    {
                        $(polje).attr("disabled", "disabled");
                        $(polje).css("background-color", "gray");
                    }
                    //je li zastavica i čija je
                    else if(zastavice.includes(field[i][j]) == true)
                    {
                        //ovo je zapravo CRNA ZASTAVICA!!!,vidi se bijelo iz nekog super html razloga,to je ako je moja
                        console.log(moja,JSON.stringify(zastavice));
                        if(field[i][j] == moja)
                        {
                            $(polje).html("🏴");
                        }
                        else
                        {
                            //ovo je zapravo BIJELA ZASTAVICA!!!,vidi se crno iz nekog super html razloga,to je ako nije moja
                            $(polje).html("🏳️");
                            //i ne možemo ju kliknut onda
                            $(polje).attr("disabled", "disabled");
                        }
                    }
                    //inače je neki broj,samo ga ispišemo i ne možemo ga dirat
                    else if(field[i][j]!=0)
                    {
                        $(polje).html(field[i][j]);
                        $(polje).attr("disabled", "disabled");
                    }
                    $(polje).appendTo(td);
                    tr.append(td);
                }
                table.append(tr);
            }
            $('.game').append(table);
            //$('.game').append( "br");
            $('.game').append("Bodovi: "+bodovi);
        }
        //aktivira se kad kliknemo na neko polje,šaljemo id i koji klik
        function OdigrajPotez(event)
        {
            var klik;
            if(event.button === 0)
                klik = "left";
            else if(event.button === 2)
                klik = "right";
            else klik = ".";
            console.log(event.button);
            $.ajax(
            {
                url: "view/serve.php",
                dataType: "json",
                data:
                {
                    username:username,
                    whoSent: "OdigrajPotez",
                    potez: $(this).attr('id'),
                    flag: moja,
                    klik: klik,
                    field:Array()
                },
                success: function( data )
                {
                    console.log( "OdigrajPotez :: success :: data = " + JSON.stringify( data ) );
                    if( typeof( data.error ) === "undefined" )
                    {
                        IscrtajField(data.field);
                    }
                    else CheckGameStatus();
                },
                error: function( xhr, status )
                {
                    console.log( "OdigrajPotez :: error :: status = " + status );
                    if( status === "timeout" )
                        OdigrajPotez();
                }
            });
        }

        function ExitTheGame()
        {
            $.ajax(
            {
                url: "view/serve.php",
                dataType: "json",
                data:
                {
                    username:username,
                    whoSent: "ExitTheGame"
                },
                success: function( data )
                {
                    console.log( "ExitTheGame :: success :: data = " + JSON.stringify( data ) );
                    if( typeof( data.error ) === "undefined" )
                    {
                        $("body").html("<div class='izlazni_tekst'>You left the game before the end, dummy. Your score will be exterminated!</div>");
                        $("body").append("<a href='choose.php' class='izlazni_button'>Back to menu</a>");
                    }
                    else ExitTheGame();
                },
                error: function( xhr, status )
                {
                    console.log( "ExitTheGame :: error :: status = " + status );
                    if( status === "timeout" )
                        ExitTheGame();
                }
            });
        }

    </script>

<?php require_once __DIR__ . '/_footer.php'; ?>