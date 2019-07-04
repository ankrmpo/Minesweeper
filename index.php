<?php

require_once __DIR__ . '/model/db.class.php';

function ispisiFormuZaLogin()
{
    require_once __DIR__ . '/view/welcome.php';
}

function pokusaj_logina_uspio()
{
    if(!(isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password']))) return false;
    # login
    else return false;
}

function pokusaj_registracije()
{
    if(isset($_POST['register']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['mail'])) return true;
    else return false;
}

function registriraj_novog_korisnika()
{
    require_once __DIR__ . '/controller/' . 'registerController.php';
    $controller=new $controllerName();
    $action='index';
    $controller->$action();
}

function pokreniAplikaciju()
{
    require_once 'choose.php';
}

if($username = pokusaj_logina_uspio()) pokreniAplikaciju();

else if(pokusaj_registracije()) registriraj_novog_korisnika();

else ispisiFormuZaLogin();

?>