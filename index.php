<?php

session_start();

require_once __DIR__ . '/model/db.class.php';

function ispisiFormuZaLogin()
{
    require_once __DIR__ . '/view/welcome.php';
}

function pokusaj_logina_uspio()
{
    if(isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password'])) 
    {
        $_SESSION['username']=$_POST['username'];
        $_SESSION['password']=$_POST['password'];

        return true;
    }
    else return false;
}

function pokusaj_registracije()
{
    if(isset($_POST['register']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['mail'])) 
    {
        $_SESSION['username']=$_POST['username'];
        $_SESSION['password']=$_POST['password'];
        $_SESSION['mail']=$_POST['mail'];
        
        return true;
    }
    else return false;
}

function registriraj_novog_korisnika()
{
    require_once __DIR__ . '/controller/' . 'registerController.php';
    $controllerName =  'registerController';
    $controller=new $controllerName();
    $action='index';
    $controller->$action();
}

function ulogiraj_korisnika()
{
    require_once __DIR__ . '/controller/' . 'loginController.php';
    $controllerName =  'loginController';
    $controller=new $controllerName();
    $action='index';
    $controller->$action();
}

if(pokusaj_logina_uspio()) ulogiraj_korisnika();

else if(pokusaj_registracije()) registriraj_novog_korisnika();

else ispisiFormuZaLogin();

?>