<?php

if(pokusaj_logina())
    crtaj_login();

else if(pokusaj_registracije())
    crtaj_register();

else
    crtaj_welcome_screen();

function pokusaj_logina()
{
    if(isset($_POST['login'])) return true;
    return false;
}

function pokusaj_registracije()
{
    if(isset($_POST['register'])) return true;
    return false;
}

function crtaj_login()
{
    require_once __DIR__ . '/view/login.php';
}

function crtaj_register()
{
    require_once __DIR__ . '/view/register.php';
}

function crtaj_welcome_screen()
{
    require_once __DIR__ . '/index.php';
}

?>