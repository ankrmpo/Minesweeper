<?php

if(!isset($_SESSION)) session_start();

class helloController
{
    function index()
    {
        require_once __DIR__ . '/../view/hello.php';
    }
};

?>