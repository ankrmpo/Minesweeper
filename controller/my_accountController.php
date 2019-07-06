<?php

if(!isset($_SESSION)) session_start();
require_once __DIR__ . '/../model/memberservice.class.php';

class my_accountController
{
    function index()
    {
        $ls = new MemberService();
        $data = $ls->getAccountDetails();
        
        require_once __DIR__ . '/../view/account.php';
    }

    function changeData()
    {
        $ls = new MemberService();
        $ls->changeAccountDetails();
        $data = $ls->getAccountDetails();
        
        require_once __DIR__ . '/../view/account.php';
    }
};

?>