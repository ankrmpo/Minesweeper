<?php

require_once __DIR__ . '/../model/memberservice.class.php';

class rankingController
{
    function index()
    {
        $ls=new MemberService();

        $ranks=$ls->getRanks();

        require_once __DIR__ . '/../view/ranking.php';
    }
};

?>