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

$app->get("/admin/users",function(){

	User::verifyLogin();

	$users = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users",array("users"=>$users));



});

$app->get("/admin/users/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");

});

$app->get("/admin/users/:iduser/delete", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");

	exit;

});

//UPDATE USER
$app->get("/admin/users/:iduser", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-update",array(
			"user"=>$user->getValues()
		));



});

$app->post("/admin/users/create", function(){

	User::verifyLogin();

	$user =  new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");

	exit;

});

$app->post("/admin/users/:iduser", function($iduser){

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;
});

$app->get("/admin/forgot", function(){

$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot");

});

$app->post("/admin/forgot", function(){

	
	$user = User::getForgot($_POST["email"]);

	header("Location: /admin/forgot/sent");

	exit;

});

$app->get("/admin/forgot/sent", function(){

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-sent");


});

$app->get("/admin/forgot/reset", function(){

	$user = User::validForgotDecrypt($_GET["code"]);
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-reset",array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));

});

$app->post("/admin/forgot/reset", function(){

	$forgot = User::validForgotDecrypt($_POST["code"]);

	User::setForgotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	$password = password_hash($_POST["password"],PASSWORD_DEFAULT,[
		"cost"=>12
	]);

	$user->setPassword($password);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-reset-success");


});

$app->run();

 ?>