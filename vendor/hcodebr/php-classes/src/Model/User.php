<?php 
	namespace Hcode\Model;
	use \Hcode\DB\Sql;
	use \Hcode\Model;
	use \Hcode\Mailer;

	Class User extends Model{

		const SESSION = "User";
		const SECRET = "Mvcsoftwaresphp";

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

		public static function listAll()
		{

			$sql = new Sql();

			return $sql->select ("SELECT * FROM tb_users user INNER JOIN tb_persons persons USING (idperson) ORDER BY persons.desperson");
		}

		public function save()
		{

			$sql = new Sql();

			$results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)",array(

				":desperson"=>$this->getdesperson(),
				":deslogin"=>$this->getdeslogin(),
				":despassword"=>$this->getdespassword(),
				":desemail"=>$this->getdesemail(),
				":nrphone"=>$this->getnrphone(),
				":inadmin"=>$this->getinadmin()

			));

			$this->setData($results[0]);
		}

		public function get($iduser)
		{

			$sql = new Sql();

			$results=$sql->select("SELECT * FROM tb_users us INNER JOIN tb_persons per USING(idperson) WHERE us.iduser = :iduser",array(
				":iduser"=>$iduser
			));

			$this->setData($results[0]);
		}

		public function update()
		{

			$sql = new Sql();

			$results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)",array(
				":iduser"=>$this->getiduser(),
				":desperson"=>$this->getdesperson(),
				":deslogin"=>$this->getdeslogin(),
				":despassword"=>$this->getdespassword(),
				":desemail"=>$this->getdesemail(),
				":nrphone"=>$this->getnrphone(),
				":inadmin"=>$this->getinadmin()

			));

			$this->setData($results[0]);
			
		}

		public function delete()
		{

			$sql = new Sql();

			$sql->query("CALL sp_users_delete(:iduser)", array(
			 ":iduser"=>$this->getiduser()
			));
		}

public static function getForgot($email, $inadmin = true)
{
     $sql = new Sql();
     $results = $sql->select("
         SELECT *
         FROM tb_persons a
         INNER JOIN tb_users b USING(idperson)
         WHERE a.desemail = :email;
     ", array(
         ":email"=>$email
     ));
     if (count($results) === 0)
     {
         throw new \Exception("Não foi possível recuperar a senha.");
     }
     else
     {
         $data = $results[0];
         $results2 = $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip)", array(
             ":iduser"=>$data['iduser'],
             ":desip"=>$_SERVER['REMOTE_ADDR']
         ));
         if (count($results2) === 0)
         {
             throw new \Exception("Não foi possível recuperar a senha.");
         }
         else
         {
             $dataRecovery = $results2[0];
             $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
             $code = openssl_encrypt($dataRecovery['idrecovery'], 'aes-256-cbc', User::SECRET, 0, $iv);
             $result = base64_encode($iv.$code);
             if ($inadmin === true) {
                 $link = "http://www.mvcsoftecommerce.com.br:8000/admin/forgot/reset?code=$result";
             } else {
                 $link = "http://www.mvcsoftecommerce.com.br:8000/forgot/reset?code=$result";
             } 
             $mailer = new Mailer($data['desemail'], $data['desperson'], "Redefinir senha da Hisoka Store", "forgot", array(
                 "name"=>$data['desperson'],
                 "link"=>$link
             )); 
             $mailer->send();
             return $link;
         }
     }
 }
 		
 	public static function validForgotDecrypt($result)
 {
     $result = base64_decode($result);
     $code = mb_substr($result, openssl_cipher_iv_length('aes-256-cbc'), null, '8bit');
     $iv = mb_substr($result, 0, openssl_cipher_iv_length('aes-256-cbc'), '8bit');;
     $idrecovery = openssl_decrypt($code, 'aes-256-cbc', User::SECRET, 0, $iv);
     //$idrecovery = Encryption::Decrypt($code);
     $sql = new Sql();
     $results = $sql->select("SELECT *
         FROM tb_userspasswordsrecoveries a
         INNER JOIN tb_users b USING(iduser)
         INNER JOIN tb_persons c USING(idperson)
         WHERE
         a.idrecovery = :idrecovery
         AND
         a.dtrecovery IS NULL
         AND DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW();", array(
         ":idrecovery"=>$idrecovery
     ));
     if (count($results) === 0)
     {
         throw new \Exception("Não foi possível recuperar a senha.");
     }
     else
     {
         return $results[0];
         exit;
     }
 }

 public static function setForgotUsed($idrecovery){

 		$sql = new Sql();

 		$sql->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery",
 			array(
 				":idrecovery"=>$idrecovery
 			));

 }

 public function setPassword ($password){

 		$sql = new Sql();

 		$sql->query("UPDATE tb_users SET despassword = :password WHERE iduser = :iduser",array(
 		   ":password"=>$password,
 		   ":iduser"=>$this->getiduser()

 		));

 }

}
 
 ?>