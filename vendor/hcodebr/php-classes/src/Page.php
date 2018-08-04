<?php 

namespace Hcode;

use Rain\Tpl;

Class Page{

	private $tpl;
	private $options = [];
	private $defaults = [
		"data"=>[]
	];
	public function __construct($opts = array()){
		//CONFIG PASTA DO TEMPLATE
		//ARRAY MERGE MESCLA DOIS ARRAY
		$this->options = array_merge($this->defaults,$opts);
		$config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/",
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false // set to false to improve the speed
		);

		Tpl::configure( $config );

		//CRIANDO A CLASSE

		$this->tpl = new Tpl;

		self::setData($this->options["data"]);
		
		//CHAMANDO O TEMPLATE
		$this->tpl->draw("header");

	}

	private function setData ($data = array()){

		foreach ($data as $key => $value) {
			//VARIAVEIS QUE VÃO APARECER NO TEMPLATE
			$this->tpl->assign($key,$value);
		}

	}

	public function setTpl ($name,$data = array(), $returnHTML = false){

		self::setData($data);

		return $this->tpl->draw($name,$returnHTML);

	}

	public function __destruct(){

		$this->tpl->draw("footer");

	}
}

 ?>