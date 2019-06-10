<?php

require_once __DIR__ . '/../model/projectservice.class.php';

class all_projectsController
{
    function index()
    {
        $ls=new ProjectService();

        $projectList=$ls->getAllProjects();

        require_once __DIR__ . '/../view/all_projects.php';
    }
};

?>