<?php

if(!isset($_SESSION)) session_start();

class start_gameController
{
    function index()
    {
        require_once __DIR__ . '/../view/wait.php';
    }
};

?>