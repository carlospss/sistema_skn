<?php


require_once("C:\\xampp\htdocs\sistema-skn\backend\conexao\conexao.php");
$chave = file_get_contents("C:\\xampp\htdocs\sistema-skn\backend\conexao\chave.txt");


class Login_Cliente{


	private $cpf;
	private $senha;


	public function __construct($cpf, $senha){

		$this->cpf = preg_replace('/[.-]/', '', $cpf);
		$this->senha = $senha;
	}

	public function fazer_login(){

		global $conn;
		global $chave;

		$q = $conn->prepare("
			SELECT
				cpf
			FROM
				Cliente
			WHERE
				AES_DECRYPT(cpf, UNHEX(SHA2('$chave', 512)))=?
			AND	
				AES_DECRYPT(senha, UNHEX(SHA2('$chave', 512)))=?;
		");

		$q->bind_param('ss', $this->cpf, $this->senha);
		$q->execute();

		$get = $q->get_result();
		$id = $get->fetch_assoc();
		
		if(empty($id)) return "Usuário ou senha inválidos.";
		else{
			$_SESSION['id'] =  bin2hex($id["cpf"]);
			header("location: http://" . $_SERVER['SERVER_NAME'] . "/sistema-skn/acompanhe-seu-processo/");
		}
	}	

}





?>