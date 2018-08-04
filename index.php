<?php 

require_once("vendor/autoload.php");

use \Slim\Slim;
use  Hcode\PageAdmin;
use  Hcode\Page;
//SLIM UTILIZA ROOTAS PARA RANKEAMENTO DE CEO
$app = new \Slim\Slim();

$app->config('debug', true);
//QUANDO CHAMAR O SITE SEM NENHUMA ROTA ELE VAI EXECUTAR A FUNÇÃO ABAIXO CRIANDO UMA NOVA PAGE
$app->get('/', function() {
    
	$page = new Page();
	//AQUI ELE VAI JUNTAR O HEADER E O FOOTER NO INDEX HTML
	$page->setTpl("index");

});
//ROTA DA PAGE ADMIN
$app->get('/admin', function() {
    
	$page = new PageAdmin();
	//AQUI ELE VAI JUNTAR O HEADER E O FOOTER NO INDEX HTML
	$page->setTpl("index");

});

$app->run();

 ?>