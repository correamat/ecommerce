<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use  Hcode\PageAdmin;
use  Hcode\Page;
use Hcode\Model\User;
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
    
	User::verifyLogin();
	$page = new PageAdmin();
	//AQUI ELE VAI JUNTAR O HEADER E O FOOTER NO INDEX HTML
	$page->setTpl("index");

});
//CRIANDO NOVA ROTA DE PAGINA PARA INDEX ADMIN

$app->get('/admin/login',function(){

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");

});
//CRIANDO NOVA ROTA DE PAGINA PARA INDEX ADMIN POST VALIDANDO LOGIN E SENHA
$app->post('/admin/login',function(){

	User::login($_POST["login"],$_POST["password"]);

	Header("Location: /admin");
	exit;

});

$app->get('/admin/logout', function(){

	User::logout();

	Header("Location: /admin/login");
	exit;

});

$app->run();

 ?>