<?php

function sendJSONandExit($message)
{
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($message);
    flush();
    exit(0);
}

$error = "";
$filename = 'docs/br_igraca.txt';

if(!file_exists($filename))
    $error = $error . "File " . $filename . " doesn't exist!";
else if(!is_readable($filename))
    $error = $error . "File " . $filename . " is not readable!";
else if(!is_writable($filename))
    $error = $error . "File " . $filename - " is not writable!";

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
else // sve postavljeno pa obradi funkciju
{
    if($_GET['whoSent'] === "IWantToJoin") // igrač se želi priključiti, pošalji mu odgovor
    {
        $broj_igraca = file_get_contents($filename);
        $broj_igraca = intval($broj_igraca);

        $response = [];
        if($broj_igraca === 4) // nema slobodnih mjesta pa vrati full
        {
            $response['flag'] = "full";

            sendJSONandExit($response);
        }
        else // ima slobodnih mjesta pa ga ubaci i vrati indeks zastavice kao signal
        {
            $response['flag'] = $broj_igraca;
            ++$broj_igraca; //povecaj broj igraca i spremi u datoteku
            file_put_contents($filename, "" . $broj_igraca);

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
        $broj_igraca = file_get_contents($filename);
        $broj_igraca = intval($broj_igraca);
        $response['timestamp'] = $currentcheck;
        
        if($broj_igraca === 4)
            $response['response'] = "yes";
        else
            $response['response'] = "no";

        sendJSONandExit($response);
    }
}

?>