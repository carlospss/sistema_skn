<?php

require_once("C:\\xampp\htdocs\sistema-skn\backend\conexao\conexao.php");
class Login_Admin{


	private function validar_user($user) :bool{
		return preg_match('/^\w$/', $user);
	}

	private function validar_senha($senha) :bool{
		
		return preg_match('/^\w$/', $senha);
	}

	private function fazer_login($user, $senha) :bool{
		global $conn;

		$senha = hash('sha256', $senha);
		$user = hash('sha256',$user);

		$q = $conn->prepare("

			SELECT 
				usuario
			FROM 
				Admin
			WHERE
				usuario=?
			AND 
				senha=?;
		");

		$q->bind_param('ss', $user, $senha);
		$q->execute();

		$get = $q->get_result();

		return empty($get->fetch_assoc());
	}

	public function validar_dados($user, $senha) :string{
		$erros = [];

		if($this->validar_user($user)) $erros[] = "user";
		if($this->validar_senha($senha)) $erros[] = "senha";
		if($this->fazer_login($user, $senha)) return "usuário ou senha inválidos.";

		$_SESSION['admin'] = true;

		header("location: http://" . $_SERVER['HTTP_HOST'] . "/sistema-skn/admin/painel/processos");
	}

	public function verificar_adm_existe(){

		global $conn;
		
		$q = $conn->prepare("

			SELECT 
				*
			FROM 
				Admin
		");

		$q->execute();

		$get = $q->get_result();

		return empty($get->fetch_assoc());
	}

	public function criar_adm($user, $pass){

		global $conn;
		
		$pass = hash('sha256', $pass);
		$user = hash('sha256',$user);

		$q = $conn->prepare("

			INSERT INTO 
				Admin(usuario, senha)
			VALUES(?,?)
		");

		$q->bind_param('ss', $user, $pass);
		$q->execute();


		$_SESSION['admin'] = true;
		header("location: http://" . $_SERVER['HTTP_HOST'] . "/sistema-skn/admin/painel/");

	}
}

?>