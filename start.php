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

}

function crtaj_register()
{
    
}

?>