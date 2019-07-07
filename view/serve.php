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

if(!isset($_GET['response'])) //ili nije ništa postavljeno ili je funkcija IWantToJoin pozvala
{
    if(!isset($_GET['username'])) // nije postavljeno, vrati error
    {
        
        $response = [];
        $response['error'] = "Username not defined!";

        sendJSONandExit($response);
    }
    else // postavljen username, znači IWantToJoin je pozvala
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
}
else // ili username nije postavljen ili ga je pozvala funkcija CanWeStart
{
    if(!isset($_GET['username'])) // nije postavljen username
    {
        $response = [];
        $response['error'] = "Username not defined!";

        sendJSONandExit($response);
    }
    else // CanWeStart vraćamo samo yes or no
    {
        $broj_igraca = file_get_contents($filename);
        $broj_igraca = intval($broj_igraca);

        if($broj_igraca === 4)
            $response['response'] = "yes";
        else
            $response['response'] = "false";

        sendJSONandExit($response);
    }
}


?>