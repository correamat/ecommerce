<?php 

use \Hcode\PageAdmin;
use \Hcode\Model\User;


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



 ?>