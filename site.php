<?php

use \Hcode\Page;
use \Hcode\Model\Product;

$app->get('/', function() {
    
	$products = Product::listAll();

	$page = new Page();
	//AQUI ELE VAI JUNTAR O HEADER E O FOOTER NO INDEX HTML
	$page->setTpl("index",[
		'products'=>Product::checkList($products)
	]);

});

 ?>