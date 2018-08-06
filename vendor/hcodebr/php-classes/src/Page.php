<?php 

namespace Hcode;

use Rain\Tpl;

Class Page{

	private $tpl;
	private $options = [];
	private $defaults = [
		"header"=>true,
		"footer"=>true,
		"data"=>[]
	];
	public function __construct($opts = array(), $tpl_dir = "/views/"){
		//CONFIG PASTA DO TEMPLATE
		//ARRAY MERGE MESCLA DOIS ARRAY
		$this->options = array_merge($this->defaults,$opts);
		$config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false // set to false to improve the speed
		);

		Tpl::configure( $config );

		//CRIANDO A CLASSE

		$this->tpl = new Tpl;

		self::setData($this->options["data"]);
		
		//CHAMANDO O TEMPLATE
		if($this->options["header"]==true)
		{

			$this->tpl->draw("header");
		}

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

			if($this->options["footer"]==true)
		{

			$this->tpl->draw("footer");
		}

	}
}

 ?>