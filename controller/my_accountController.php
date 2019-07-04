<?php

require_once __DIR__ . '/../model/memberservice.class.php';

class my_accountController
{
    function index()
    {
        $ls = new MemberService();
        $data = $ls->getAccountDetails();
        
        require_once __DIR__ . '/../view/account.php';
    }
};

?>