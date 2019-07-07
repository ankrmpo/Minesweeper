<?php

function sendJSONandExit($message)
{
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($message);
    flush();
    exit(0);
}

$error = "";
$brojIgraca = 'docs/br_igraca.txt';
// $igraci = 'docs/igraci.txt';

if(!file_exists($brojIgraca))
    $error = $error . "File " . $brojIgraca . " doesn't exist!";
else if(!is_readable($brojIgraca))
    $error = $error . "File " . $brojIgraca . " is not readable!";
else if(!is_writable($brojIgraca)){
    $error = $error . "File " . $brojIgraca - " is not writable!";

// if(!file_exists($igraci))
//     $error = $error . "File " . $igraci . " doesn't exist!";
// else if(!is_readable($igraci))
//     $error = $error . "File " . $igraci . " is not readable!";
// else if(!is_writable($igraci))
//     $error = $error . "File " . $igraci - " is not writable!";

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
        $broj_igraca = file_get_contents($brojIgraca);
        $broj_igraca = intval($broj_igraca);

        $response = [];
        $response['error'] = "evo ga";
        if($broj_igraca === 4) // nema slobodnih mjesta pa vrati full
        {
            $response['flag'] = "full";

            sendJSONandExit($response);
        }
        else // ima slobodnih mjesta pa ga ubaci i vrati indeks zastavice kao signal
        {
            $response['flag'] = $broj_igraca;
            ++$broj_igraca; //povecaj broj igraca i spremi u datoteku
            file_put_contents($brojIgraca, "" . $broj_igraca);

            sendJSONandExit($response);
        }
    }

    else if($_GET['whoSent'] === "CanWeStart") // igrač provjerava može li igra početi
    {
        $lastchecked = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;
        $currentcheck = filemtime($brojIgraca);

        while($currentcheck <= $lastchecked)
        {
            usleep(10000);
            clearstatcache();
            $currentcheck = filemtime($brojIgraca);
        }

        // došao novi igrač
        $broj_igraca = file_get_contents($brojIgraca);
        $broj_igraca = intval($broj_igraca);
        $response['timestamp'] = $currentcheck;

        if($broj_igraca === 4)
            $response['response'] = "yes";
        else
            $response['response'] = "no";

        sendJSONandExit($response);
    }

    else
    {
        $response['flag'] = "full";

        sendJSONandExit($response);
    }
}

?>