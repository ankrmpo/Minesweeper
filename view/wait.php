<?php require_once __DIR__ . '/_header.php'; 

    if(!isset($_SESSION)) session_start();
    $username=$_SESSION['username'];
?>
    <link rel="stylesheet" type="text/css" href="view/style/wait.css">
</head>
<body>
    <div class="main">
        <div id="wait">

        </div>
        <div id="players">

        </div>
    </div>
    <div id="no">
        <div class="text"></div>
    </div>

    <script>
        var username="<?php echo $username; ?>";
        $(document).ready(function()
        {
            IspisiNemaMjesta();
            //CanWeStart();
        });

        /*function IWantToJoin(username)
        {
            $.ajax(
            {
                url: "neka serverska skripta",
                dataType: "json",
                data:
                {
                    username:username
                },
                success: function( data )
                {
                    console.log( "IWantToJoin :: success :: data = " + JSON.stringify( data ) );

                    if( typeof( data.error ) === "undefined" )
                    {
                        if(data.username=="full") IspisiNemaMjesta();
                        else if(data.username==username) CanWeStart();
                    }
                },
                error: function( xhr, status )
                {
                    console.log( "IWantToJoin :: error :: status = " + status );

                    if( status === "timeout" )
                        IspisiNemaMjesta();
                }
            });
        }

        function CanWeStart()
        {

        }*/

        function IspisiNemaMjesta()
        {
            var text="The game is occupied, we are so sorry...";
            $(".text").append(document.createTextNode(text));
            $('#no').append('<br><br>');
            $('#no').append('<img id="sorry" src="view/style/sorry.jpg" />');
            $('#no').append('<br><br>');
            $('#no').append('<a class="back" href="choose.php">Back to menu</a>');
        }

    </script>
</body>

<?php require_once __DIR__ . '/_footer.php'; ?>