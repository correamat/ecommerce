<?php 

namespace Hcode;

class Model {

	private $values = [];
	//METODO MÁGICO PARA CRIAR E GERENCIAR OS GET AND SETTERS
	//NAME ELE VERIFICA SE É GET OU SET E O ARGS E O VALOR QUE FOI PASSADO PARA O MESMO O CONTEÚDO DELE
	public function __call($name, $args)
	{
		//PEGANDO A PRIMEIRA POSIÇÃO ATÉ A 3
		$method = substr($name, 0, 3);
		//PEGANDO A 3 POSIÇÃO ATÉ O FINAL NOME DA FUNÇÃO
		$fieldName = substr($name, 3, strlen($name));

		switch ($method) {
			case "get":
				return $this->values[$fieldName];
			break;

			case "set":
				$this->values[$fieldName] = $args[0];
			break;

			default:
				# code...
				break;
		}


	}

	public function setData($data = array())
	{

		foreach ($data as $key => $value) {
			$this->{"set".$key}($value);
		}

	}

	public function getValues()
	{

		return $this->values;

	}

}

 ?>