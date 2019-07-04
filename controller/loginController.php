<?php

if(!isset($_SESSION)) session_start();

require_once __DIR__ . '/../model/memberservice.class.php';

class loginController
{
    function index()
    {
        $ls=new MemberService();
        $result=$ls->login();
        if($result==true) require_once __DIR__ . '/../view/hello.php';
        else require_once __DIR__ . '/../view/welcome.php';
    }
};

?>