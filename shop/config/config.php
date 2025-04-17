<?php
define("ROOT", dirname(__DIR__));
const APP = ROOT.'/app';
const CORE = ROOT.'/core';
const CONFIG = ROOT.'/config';

require_once ROOT.'/vendor/autoload.php';
$router = new \Core\Router();
require_once CONFIG.'/routes.php';
$router->setNotFoundHandler(function (){
    echo "Custom 404 Page";
});
$router->dispatch();