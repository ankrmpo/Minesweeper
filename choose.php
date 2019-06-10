<?php

if(!isset($_GET['rt']))
{
    $controllerName='hello';
    $action='index';
}

else
{
    $parts=explode('/',$_GET['rt']);
    
    if(isset($parts[0]) && preg_match('/^[a-zA-Z0-9_]+$/',$parts[0]))
        $controllerName=$parts[0];
    else $controllerName='hello';

    if(isset($parts[1]) && preg_match('/^[a-zA-Z0-9_]+$/',$parts[1]))
        $action=$parts[1];
    else $action='index';

}

$controllerName = $controllerName . 'Controller';
require_once __DIR__ . '/controller/' . $controllerName . '.php';

$controller=new $controllerName();
$controller->$action();

?>