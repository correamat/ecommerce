<?php 
	namespace Hcode\Model;
	use \Hcode\DB\Sql;
	use \Hcode\Model;

	Class User extends Model{

		const SESSION = "User";

		public static function login($login, $password)
		{

			$sql = new Sql();

			$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN",array(
				":LOGIN"=>$login
			));

			if(count($results)==0){
				//UTILIZANDO A BARA PARA ACHAR A EXCEPTION PADRÃO POIS NÃO CRIAMOS UMA NESSE NAMESPACE
				throw new \Exception("Usuário Inexistente ou senha inválida.");
				
			}

			$data = $results[0];

			if(password_verify($password, $data["despassword"])==true)
			{

				$user = new User();

				$user->setData($data);
				//COLOCANDO OS DADOS DO USUÁRIO NA SESSÃO E RETORNANDO O MESMO
				$_SESSION[User::SESSION] = $user->getValues();

				return $user;


			}else{

				throw new \Exception("Usuário Inexistente ou senha inválida.");
			}

		}

		public static function verifyLogin($inadmin = true){

			if(

				!isset($_SESSION[User::SESSION]) 
				|| 
				!$_SESSION[User::SESSION] 
				|| 
				!(int)$_SESSION[User::SESSION]["iduser"] > 0 
				|| (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin

			){
				Header("Location: /admin/login");
				exit;
			}

		}

		public static function logout()
		{

			$_SESSION[User::SESSION] = NULL;

		}

	}
 ?>