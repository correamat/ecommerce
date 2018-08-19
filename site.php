<?php

use \Hcode\Page;

$app->get('/', function() {
    
	$page = new Page();
	//AQUI ELE VAI JUNTAR O HEADER E O FOOTER NO INDEX HTML
	$page->setTpl("index");

});

 ?>