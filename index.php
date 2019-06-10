<?php

require_once __DIR__ . '/model/db.class.php';

function ispisiFormuZaLogin()
{
    require_once __DIR__ . '/view/welcome.php';
}

function pokusaj_logina_uspio()
{
    //tu provjeravamo uspješan ili neuspješan login/register
    return false;
}

function pokreniAplikaciju()
{
    require_once 'choose.php';
}

if(pokusaj_logina_uspio()) pokreniAplikaciju();

else ispisiFormuZaLogin();

?>