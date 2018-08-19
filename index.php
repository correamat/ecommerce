<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;


//SLIM UTILIZA ROOTAS PARA RANKEAMENTO DE CEO
$app = new \Slim\Slim();

$app->config('debug', true);

require_once("site.php");
require_once("admin.php");
require_once("admin-users.php");
require_once("admin-login.php");
require_once("admin-categories.php");
require_once("admin-products.php");
//QUANDO CHAMAR O SITE SEM NENHUMA ROTA ELE VAI EXECUTAR A FUNÇÃO ABAIXO CRIANDO UMA NOVA PAGE

//ROTA DA PAGE ADMIN


$app->run();

 ?>