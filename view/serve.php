<?php
function sendJSONandExit($message)
{
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($message);
    flush();
    exit(0);
}
$error = "";
$fileBrojIgraca = 'docs/br_igraca.txt';
$fileIgraci = 'docs/igraci.txt';

if(!file_exists($fileBrojIgraca))
    $error = $error . "File " . $fileBrojIgraca . " doesn't exist!";
else if(!is_readable($fileBrojIgraca))
    $error = $error . "File " . $fileBrojIgraca . " is not readable!";
else if(!is_writable($fileBrojIgraca))
    $error = $error . "File " . $fileBrojIgraca . " is not writable!";
if(!file_exists($fileIgraci))
    $error = $error . "File " . $fileIgraci . " doesn't exist!";
else if(!is_readable($fileIgraci))
    $error = $error . "File " . $fileIgraci . " is not readable!";
else if(!is_writable($fileIgraci))
    $error = $error . "File " . $fileIgraci . " is not writable!";
if($error !== "")
{
    $response = [];
    $response['error'] = $error;
    sendJSONandExit($response);
}
if(!isset($_GET['username']))
{
    $response = [];
    $response['error'] = "Username not defined!";
    sendJSONandExit($response);
}
else if(!isset($_GET['whoSent']))
{
    $response = [];
    $response['error'] = "Action undefined!";
    sendJSONandExit($response);
}

$broj_igraca = file_get_contents($fileBrojIgraca);
$broj_igraca = intval($broj_igraca);

$fileTrenutnaIgra = "docs/trenutnaIgra.txt";
$fileZavrsnaIgra = "docs/zavrsnaIgra.txt";
$error = "";

if(!file_exists($fileTrenutnaIgra))
    $error = $error . "File " . $fileTrenutnaIgra . " doesn't exist!";
else if(!is_readable($fileTrenutnaIgra))
    $error = $error . "File " . $fileTrenutnaIgra . " is not readable!";
else if(!is_writable($fileTrenutnaIgra))
    $error = $error . "File " . $fileTrenutnaIgra . " is not writable!";

if(!file_exists($fileZavrsnaIgra))
    $error = $error . "File " . $fileZavrsnaIgra . " doesn't exist!";
else if(!is_readable($fileZavrsnaIgra))
    $error = $error . "File " . $fileZavrsnaIgra . " is not readable!";
else if(!is_writable($fileZavrsnaIgra))
    $error = $error . "File " . $fileZavrsnaIgra . " is not writable!";

if($broj_igraca === 0)
{
    require_once __DIR__ . '/docs/generator.php';

    file_put_contents($fileTrenutnaIgra, json_encode($GameField));
    file_put_contents($fileZavrsnaIgra, json_encode($EndField));
}

$max_broj_igraca = 2;
$z = 10;
$zastavice = [];
for($i = 0; $i < $max_broj_igraca; ++$i)
{
    $zastavice[$i] = $z;
    ++$z;
}

if($_GET['whoSent'] === "IWantToJoin") // igrač se želi priključiti, pošalji mu odgovor
{ 
    $response = [];
    
    if($broj_igraca === $max_broj_igraca) // nema slobodnih mjesta pa vrati full
    {
        $response['flag'] = "full";
        sendJSONandExit($response);
    }
    else // ima slobodnih mjesta pa ga ubaci i vrati indeks zastavice kao signal
    {
        $novi = [];
        $novi['username'] = $_GET['username'];
        $novi['indeks'] = $broj_igraca;
        $novi['bodovi'] = 0;
        $str = implode(',', $novi);
        file_put_contents($fileIgraci, $str . "\n", FILE_APPEND);
        $response['flag'] = $broj_igraca;
        $response['flags'] = $zastavice;
        ++$broj_igraca; //povecaj broj igraca i spremi u datoteku
        file_put_contents($fileBrojIgraca, "" . $broj_igraca);
        sendJSONandExit($response);
    }
}
else if($_GET['whoSent'] === "CanWeStart") // igrač provjerava može li igra početi
{
    $response = [];

    $lastchecked = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;
    $currentcheck = filemtime($fileBrojIgraca);
    while($currentcheck <= $lastchecked)
    {
        usleep(10000);
        clearstatcache();
        $currentcheck = filemtime($fileBrojIgraca);
    }
    // došao novi igrač
    $response['timestamp'] = $currentcheck;
    $broj_igraca = intval(file_get_contents($fileBrojIgraca));

    if($broj_igraca === $max_broj_igraca)
        $response['response'] = "yes";
    else
        $response['response'] = "no";

    sendJSONandExit($response);
}
else if($_GET['whoSent'] === "CheckGameStatus")
{
    $reponse = array();

    $lastchecked = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;
    $currentcheck = filemtime($fileTrenutnaIgra);
    while($currentcheck <= $lastchecked)
    {
        usleep(10000);
        clearstatcache();
        $currentcheck = filemtime($fileTrenutnaIgra);
    }

    $igraci = explode('\n', file_get_contents($fileIgraci));

    for($i = 0; $i < count($igraci); ++$i)
    {
        $igrac = explode(',', $igraci[$i]);
        if($igrac[0]===$_GET['username']) $response['bodovi']=$igrac[2];
    }

    $response['timestamp'] = $currentcheck;
    $response['field'] = file_get_contents($fileTrenutnaIgra);

    sendJSONandExit($response);
}
else if($_GET['whoSent'] === "ExitTheGame")
{
    $response = [];

    --$broj_igraca;
    file_put_contents($fileBrojIgraca, "" . $broj_igraca);

    $igraci = explode('\n', file_get_contents($fileIgraci));
    file_put_contents($fileIgraci, "");
    for($i = 0; $i < count($igraci); ++$i)
    {
        $igrac = explode(',', $igraci[$i]);
        if(!($igrac[0] === $_GET['username']))
        {   
            $novi = implode(',', $igrac);
            file_put_contents($fileIgraci, $novi . "\n", FILE_APPEND);
        }
    }
    

    sendJSONandExit($response);
}
else if($_GET['whoSent'] === "OdigrajPotez")
{
    if(!isset($_GET['potez']))
        $error = $error . 'Move undefined!';
    else if(!isset($_GET['klik']))
        $error = $error . 'Click undefined';
    
    if($error !== "")
        sendJSONandExit($error);

    $event = $_GET['klik'];
    $polje = explode(',', $_GET['potez']);
    $response=array();

    

    $igraci = explode('\n', file_get_contents($fileIgraci));
    file_put_contents($fileIgraci, "");
    $igrac = [];

    for($i = 0; $i < count($igraci); ++$i)
    {
        $igrac[$i] = explode(',', $igraci[$i]);
    }

    $zavrsnaPloca = json_decode(file_get_contents($fileZavrsnaIgra));
    $trenutnaPloca = json_decode(file_get_contents($fileTrenutnaIgra));
    $velicina = count($zavrsnaPloca[0]);

    if($event === "")
    {
        $error = $error . "Press left or right mouse button!";
        sendJSONandExit($error);
    }
    
    else if($event === "left" && intval($trenutnaPloca[$polje[0]][$polje[1]]) === 0)
    {
        if(intval($zavrsnaPloca[$polje[0]][$polje[1]]) === -1)
        {
            for($i = 0; $i < count($igraci); ++$i)
            {
                if($_GET['username'] === $igrac[$i][0])
                {
                    $bodovi = $igrac[$i][2];
                    $bodovi = intval($bodovi);

                    $bodovi -= 15;
                    if($bodovi < 0 )
                        $bodovi = 0;
                    
                    $igrac[$i][2] = $bodovi;
                }
            }

            $trenutnaPloca[$polje[0]][$polje[1]] = intval($zavrsnaPloca[$polje[0]][$polje[1]]);
            file_put_contents($fileTrenutnaIgra, json_encode($trenutnaPloca));

            $response['field'] = $trenutnaPloca;
            sendJSONandExit($response);
        }
        else if(intval($zavrsnaPloca[$polje[0]][$polje[1]]) >= 1 &&  intval($zavrsnaPloca[$polje[0]][$polje[1]]) <= 8)
        {
            for($i = 0; $i < count($igraci); ++$i)
            {
                if($_GET['username'] === $igrac[$i][0])
                {
                    $bodovi = $igrac[$i][2];
                    $bodovi = intval($bodovi);

                    $bodovi += 5;
                    
                    $igrac[$i][2] = $bodovi;
                }
            }

            $trenutnaPloca[$polje[0]][$polje[1]] = intval($zavrsnaPloca[$polje[0]][$polje[1]]);
            file_put_contents($fileTrenutnaIgra, json_encode($trenutnaPloca));

            $response['field'] = $trenutnaPloca;
            sendJSONandExit($response);
        }
        else if($zavrsnaPloca[$polje[0]][$polje[1]] === 9)
        {
            $trenutnaPloca[$polje[0]][$polje[1]] = $zavrsnaPloca[$polje[0]][$polje[1]];

            file_put_contents($fileTrenutnaIgra, json_encode($trenutnaPloca));

            $response['field'] = $trenutnaPloca;
            sendJSONandExit($response);
        }

    }
    else if($event === "right" && intval($trenutnaPloca[$polje[0]][$polje[1]]) === 0)
    {
        if(intval($zavrsnaPloca[$polje[0]][$polje[1]]) !== -1)
        {
            for($i = 0; $i < count($igraci); ++$i)
                {
                    if($_GET['username'] === $igrac[$i][0])
                    {
                        $bodovi = $igrac[$i][2];
                        $bodovi = intval($bodovi);

                        $bodovi -= 5;
                        if($bodovi < 0 )
                            $bodovi = 0;
                        
                        $igrac[$i][2] = $bodovi;
                    }
                }
                $trenutnaPloca[$polje[0]][$polje[1]] = intval($_GET['flag']);
                file_put_contents($fileTrenutnaIgra, json_encode($trenutnaPloca));
            
           
            $response['field'] = $trenutnaPloca;
            sendJSONandExit($response);
        }
        else
        {
            for($i = 0; $i < count($igraci); ++$i)
            {
                if($_GET['username'] === $igrac[$i][0])
                {
                    $bodovi = $igrac[$i][2];
                    $bodovi = intval($bodovi);

                    $bodovi += 10;
                    if($bodovi < 0 )
                        $bodovi = 0;
                    
                    $igrac[$i][2] = $bodovi;
                }
            }

            $trenutnaPloca[$polje[0]][$polje[1]] = intval($_GET['flag']);
            file_put_contents($fileTrenutnaIgra, json_encode($trenutnaPloca));

            $response['field'] = $trenutnaPloca;
            sendJSONandExit($response);
        }
    }

    else if($event === "right" && intval($trenutnaPloca[$polje[0]][$polje[1]]) === intval($_GET['flag']))
    {
        $trenutnaPloca[$polje[0]][$polje[1]] = 0;
        file_put_contents($fileTrenutnaIgra, json_encode($trenutnaPloca));

        $response['field'] = $trenutnaPloca;
        sendJSONandExit($response);
    }

    for($i = 0; $i < count($igraci); ++$i)
        $igraci[$i] = implode(',', $igrac[$i]);
    
    file_put_contents($fileIgraci, implode('\n', $igraci));
    file_put_contents($fileIgraci, '\n', FILE_APPEND);

    $response['field'] = file_get_contents($fileTrenutnaIgra);
}

function otvori($trenutnaPloca, $zavrsnaPloca, $velicina, $x, $y)
{
    if($zavrsnaPloca[$x][$y] <= 8 && $zavrsnaPloca[$x][$y] >= 1)
    {
        $trenutnaPloca[$x][$y] = $zavrsnaPloca[$x][$y];
        return $trenutnaPloca;
    }

    if($x === 0)
    {
        if($y === 0)
        {
            $trenutnaPloca[$x][$y] = $zavrsnaPloca[$x][$y];
            $trenutnaPloca[$x + 1][$y] = $zavrsnaPloca[$x + 1][$y];
            $trenutnaPloca[$x][$y + 1] = $zavrsnaPloca[$x][$y + 1];
            return $trenutnaPloca;
        }
        else if($y === $velicina - 1)
        {
            $trenutnaPloca[$x][$y] = $zavrsnaPloca[$x][$y];
            $trenutnaPloca[$x + 1][$y] = $zavrsnaPloca[$x + 1][$y];
            $trenutnaPloca[$x][$y - 1] = $zavrsnaPloca[$x][$y - 1];
            return $trenutnaPloca;
        }
        else
        {
            $trenutnaPloca[$x][$y] = $zavrsnaPloca[$x][$y];  
            $trenutnaPloca[$x][$y - 1] = $zavrsnaPloca[$x][$y - 1];
            $trenutnaPloca[$x + 1][$y - 1] = $zavrsnaPloca[$x + 1][$y - 1];
            $trenutnaPloca[$x + 1][$y] = $zavrsnaPloca[$x + 1][$y];
            $trenutnaPloca[$x + 1][$y + 1] = $zavrsnaPloca[$x + 1][$y + 1];
            $trenutnaPloca[$x][$y + 1] = $zavrsnaPloca[$x][$y + 1];
            return $trenutnaPloca; 
        }
    }
    else if($x === $velicina - 1)
    {
        if($y === 0)
        {
            $trenutnaPloca[$x][$y] = $zavrsnaPloca[$x][$y];
            $trenutnaPloca[$x - 1][$y] = $zavrsnaPloca[$x - 1][$y];
            $trenutnaPloca[$x][$y + 1] = $zavrsnaPloca[$x][$y + 1];
            return $trenutnaPloca;
        }
        else if($y === $velicina - 1)
        {
            $trenutnaPloca[$x][$y] = $zavrsnaPloca[$x][$y];
            $trenutnaPloca[$x - 1][$y] = $zavrsnaPloca[$x - 1][$y];
            $trenutnaPloca[$x][$y - 1] = $zavrsnaPloca[$x][$y - 1];
            return $trenutnaPloca;
        }
        else
        {
            $trenutnaPloca[$x][$y] = $zavrsnaPloca[$x][$y];
            $trenutnaPloca[$x][$y - 1] = $zavrsnaPloca[$x][$y - 1];
            $trenutnaPloca[$x - 1][$y - 1] = $zavrsnaPloca[$x - 1][$y - 1];
            $trenutnaPloca[$x - 1][$y] = $zavrsnaPloca[$x - 1][$y];
            $trenutnaPloca[$x - 1][$y + 1] = $zavrsnaPloca[$x - 1][$y + 1];
            $trenutnaPloca[$x][$y + 1] = $zavrsnaPloca[$x][$y + 1];  
            return $trenutnaPloca;      
        }
    }
    else if($y === 0) // ovdje x više nije ruban jer je to pokriveno u prvom slucaju
    {
        $trenutnaPloca[$x][$y] = $zavrsnaPloca[$x][$y];
        $trenutnaPloca[$x - 1][$y] = $zavrsnaPloca[$x - 1][$y];
        $trenutnaPloca[$x - 1][$y + 1] = $zavrsnaPloca[$x - 1][$y + 1];
        $trenutnaPloca[$x][$y + 1] = $zavrsnaPloca[$x][$y + 1];
        $trenutnaPloca[$x + 1][$y + 1] = $zavrsnaPloca[$x + 1][$y + 1];
        $trenutnaPloca[$x + 1][$y] = $zavrsnaPloca[$x + 1][$y];
        return $trenutnaPloca;
    }
    else if($y === $velicina - 1)
    {
        $trenutnaPloca[$x][$y] = $zavrsnaPloca[$x][$y];
        $trenutnaPloca[$x - 1][$y] = $zavrsnaPloca[$x - 1][$y];
        $trenutnaPloca[$x - 1][$y - 1] = $zavrsnaPloca[$x - 1][$y - 1];
        $trenutnaPloca[$x][$y - 1] = $zavrsnaPloca[$x][$y - 1];
        $trenutnaPloca[$x + 1][$y - 1] = $zavrsnaPloca[$x + 1][$y - 1];
        $trenutnaPloca[$x + 1][$y] = $zavrsnaPloca[$x + 1][$y];
        return $trenutnaPloca;
    }
    else
    {
        $trenutnaPloca = otvori($trenutnaPloca, $zavrsnaPloca, $velicina, $x - 1, $y - 1);
        $trenutnaPloca = otvori($trenutnaPloca, $zavrsnaPloca, $velicina, $x, $y - 1);
        $trenutnaPloca = otvori($trenutnaPloca, $zavrsnaPloca, $velicina, $x + 1, $y - 1);
        $trenutnaPloca = otvori($trenutnaPloca, $zavrsnaPloca, $velicina, $x - 1, $y);
        $trenutnaPloca = otvori($trenutnaPloca, $zavrsnaPloca, $velicina, $x + 1, $y);
        $trenutnaPloca = otvori($trenutnaPloca, $zavrsnaPloca, $velicina, $x - 1, $y + 1);
        $trenutnaPloca = otvori($trenutnaPloca, $zavrsnaPloca, $velicina, $x, $y + 1);
        $trenutnaPloca = otvori($trenutnaPloca, $zavrsnaPloca, $velicina, $x + 1, $y + 1);
        return $trenutnaPloca;
    }
}

?>