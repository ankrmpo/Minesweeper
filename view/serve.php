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

$fileIgra = "docs/igra.txt";
$error = "";

if(!file_exists($fileIgra))
    $error = $error . "File " . $fileIgra . " doesn't exist!";
else if(!is_readable($fileIgra))
    $error = $error . "File " . $fileIgra . " is not readable!";
else if(!is_writable($fileIgra))
    $error = $error . "File " . $fileIgra . " is not writable!";

if($broj_igraca === 0)
{
    require_once __DIR__ . '/docs/generator.php';

    file_put_contents($fileIgra, json_encode($GameField));
}

$max_broj_igraca = 1;
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
    $currentcheck = filemtime($fileIgra);
    while($currentcheck <= $lastchecked)
    {
        usleep(10000);
        clearstatcache();
        $currentcheck = filemtime($fileIgra);
    }

    $response['timestamp'] = $currentcheck;
    $response['field'] = file_get_contents($fileIgra);

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

?>