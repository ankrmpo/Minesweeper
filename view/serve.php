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
if($_GET['whoSent'] === "IWantToJoin") // igrač se želi priključiti, pošalji mu odgovor
{ 
    $response = [];
    $broj_igraca = file_get_contents($fileBrojIgraca);
    $broj_igraca = intval($broj_igraca);
    
    if($broj_igraca === 4) // nema slobodnih mjesta pa vrati full
    {
        $response['flag'] = "full";
        sendJSONandExit($response);
    }
    else // ima slobodnih mjesta pa ga ubaci i vrati indeks zastavice kao signal
    {
        $novi = [];
        $novi['username'] = $_GET['username'];
        $novi['indeks'] = $broj_igraca;
        file_put_contents($fileIgraci, json_encode($novi), FILE_APPEND);
        $response['flag'] = $broj_igraca;
        ++$broj_igraca; //povecaj broj igraca i spremi u datoteku
        file_put_contents($fileBrojIgraca, "" . $broj_igraca);
        sendJSONandExit($response);
    }
}
else if($_GET['whoSent'] === "CanWeStart") // igrač provjerava može li igra početi
{
    $lastchecked = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;
    $currentcheck = filemtime($filename);
    while($currentcheck <= $lastchecked)
    {
        usleep(10000);
        clearstatcache();
        $currentcheck = filemtime($filename);
    }
    // došao novi igrač
    $broj_igraca = file_get_contents($fileBrojIgraca);
    $broj_igraca = intval($broj_igraca);
    $response['timestamp'] = $currentcheck;
    if($broj_igraca === 4)
        $response['response'] = "yes";
    else
        $response['response'] = "no";
    sendJSONandExit($response);
}
?>