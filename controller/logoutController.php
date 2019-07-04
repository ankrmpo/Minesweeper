<?php

if(!isset($_SESSION)) session_start();

class logoutController
{
    function index()
    {
        session_unset();
        session_destroy();
        require_once __DIR__ . '/../view/welcome.php';
    }
};

?>