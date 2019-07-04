<?php

require_once __DIR__ . '/../model/memberservice.class.php';

class registerController
{
    function index()
    {
        $ls=new MemberService();
        $result=$ls->register();
        if($result==true) require_once __DIR__ . '/../view/hello.php';
        else require_once __DIR__ . '/../view/welcome.php';
    }
};

?>