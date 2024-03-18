<?php

class Cadastro_cliente{
	private $nome;
	private $tel;
	private $rg;
	private $cpf;
	private $email;
	private $cep;
	private $rua;
	private $bairro;
	private $numero;
	private $cidade;
	private $uf;
	private $endereco; 
	private $chave;

	public function __construct($nome, $tel, $cep, $rua, $bairro, $numero, $complemento, $cidade, $uf, $rg, $cpf, $email){
		$this->nome = $nome;
		$this->tel = $tel;
		$this->cep = $cep;
		$this->endereco = [];
		$this->rua = $rua;
		$this->bairro = $bairro;
		$this->numero = $numero;
		$this->complemento = $complemento; 
		$this->cidade = $cidade;
		$this->uf = $uf;
		$this->rg = $rg;
		$this->cpf = $cpf;
		$this->email = $email;
		$this->chave = file_get_contents("C:\\xampp\htdocs\sistema-skn\backend\conexao\chave.txt");		
	}


	private function validar_nome() :bool{
		
		if(preg_match('/^[a-zA-Z]+/' ,$this->nome)){
			if(preg_match('/\s+/', $this->nome)) return true;
			else return false;
		}
		
		else return false;
	}
	

	private function validar_telefone() :bool{
		if(preg_match('/^\(?[0-9]{2}\)?\s?[9]?[0-9]{4}\-?[0-9]{4}$/', $this->tel)){
			
			$this->tel = preg_replace('/[\-\)\(\s]/', '', $this->tel);
			return true;
		}else return false;
	}

	private function validar_email():bool{//FEITO

		if(preg_match('/^\b([.]?[a-zA-Z0-9][-_]*)+\@([a-zA-Z0-9][.]?)+\b$/', $this->email)){
			//verificar se há pontos no servidor:
			if(preg_match('/[a-zA-Z0-9]{6,}$/', $this->email)) return false;
			return true;
		}else return false; 
	}

	private function validar_Rg():bool{

		if(preg_match('/^[0-9]{2}.[0-9]{3}.[0-9]{3}-[0-9]{1,2}$/', $this->rg)){
			return true;

		}else return false;
	}

	private function validar_CPF(){
		//VERIFICA SE O CPF ESTÁ COM A FORMATAÇÃO CORRETA
		if(preg_match('/^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}\-?[0-9]{2}$/', $this->cpf)){
			
			$this->cpf = preg_replace('/[.-]/', '', $this->cpf);
			
			//VALIDA O CPF SEGUINDO ALGORISMO DE VALIDAÇÃO
			$resto_por_11;
			for($d = 0; $d <= 9; $d++){
				static $raiz_do_digito = 0;
				$raiz_do_digito += $this->cpf[9 - $d] * (11 - $d);

				if($d == 9) $resto_por_11 = $raiz_do_digito % 11;
			}
      
			if($resto_por_11 >= 2){
				if((11 - $resto_por_11) == $this->cpf[10]){
					return true;
				} 
				else return false;

			}else if($resto_por_11 < 2){
				if($this->cpf[10] == 0){
					return true;
				} 
				else return false;
			}
		}else return false;
	
	}
	
	private function validar_cep(){
		//verifica se o cep pertence a algum endereco
		if(preg_match('/^[0-9]{5}-?[0-9]{3}$/', $this->cep)){

			$endereco = @file_get_contents("https://viacep.com.br/ws/". $this->cep . "/json/");
			$endereco = json_decode($endereco, true);

			if(isset($endereco['erro'])) return false;
			else $this->endereco = $endereco;

		}else return false;

		$this->cep = preg_replace('/\-/', '', $this->cep);
		return true;
	}
	private function validar_uf(){
		if(!empty($this->endereco)){
			if($this->endereco["uf"] == $this->uf) return true;
			else return false;
		}else return false;
		
	}

	private function validar_rua(){
		if(!empty($this->endereco)){
			if($this->endereco['logradouro'] == $this->rua) return true;
			else return false;
		}else return false;

	}
	private function validar_bairro(){
		if(!empty($this->endereco)){
			if($this->endereco['bairro'] == $this->bairro) return true;
			else return false;
		}else return false;
	}
	private function validar_cidade(){
		if(!empty($this->endereco)){
			if($this->endereco['localidade'] == $this->cidade) return true;	
			else return true;	
		}else return false;
	}
	private function validar_complemento(){
		if($this->complemento){
			if(preg_match('/^([0-9a-zA-ZáàâãéèêíìîóôõòúùûçÁÀÂÃÉÈÊÍÌÎÓÒÔÕÚÙÛÇ]\s?){3,}$/u', $this->complemento)) return true;
			else return false;
		}else return true;
		
	}
	private function validar_numero(){
		if($this->numero){
			if(preg_match('/^[0-9]+$/', $this->numero)) return true;
			else return false;
		}else return true;
		
	}

	public function validar_dados(){

		$erros = [];
		$msg_error = 'Ocorreu um erro interno, Por favor tente novamente em 5 minutos pedimos desculpas pelo transtorno.';
		if(!$this->validar_cep()) $erros[] = 'cep';
		if(!$this->validar_CPF()) $erros[] = 'cpf';
		if(!$this->validar_uf()) $erros[] = 'uf';
		if(!$this->validar_email()) $erros[] = 'email';
		if(!$this->validar_Rg()) $erros[] = 'rg';	
		if(!$this->validar_nome()) $erros[] = 'nome'; 
 		if(!$this->validar_telefone()) $erros[] = 'tel';
		if(!$this->validar_rua()) $erros[] = 'rua';
		if(!$this->validar_bairro()) $erros[] = 'bairro';
		if(!$this->validar_cidade()) $erros[] = 'cidade';	
		if(!$this->validar_complemento()) $erros[] = 'complemento';
		if(!$this->validar_numero()) $erros[] = 'numero';


		if(empty($erros)){
			if(!$this->verificar_se_cliente_existe()) return 'Esse usuário já existe';
			$this->salvar_dados_cliente();
			return true;

		}else{

			return $erros;
		}	
	}	
	private function salvar_dados_cliente(){
		global $conn;

		$q = $conn->prepare("INSERT INTO Cliente(nome, rg, cpf, email, telefone,cep, bairro,rua, numero, complemento, cidade, uf, dt_cadastro, hr_cadastro ,senha) 
			VALUE(?, 
				AES_ENCRYPT(?, UNHEX(SHA2('$this->chave', 512))), 
				AES_ENCRYPT(?, UNHEX(SHA2('$this->chave', 512))), 
				AES_ENCRYPT(?, UNHEX(SHA2('$this->chave', 512))), 
				AES_ENCRYPT(?, UNHEX(SHA2('$this->chave', 512))), 
				?, 
				?, 
				?,
				AES_ENCRYPT(?, UNHEX(SHA2('$this->chave', 512))), 
				?, 
				?, 
				?,
				CURDATE(), 
				CURTIME(),
				AES_ENCRYPT(?, UNHEX(SHA2('$this->chave', 512))));");

		$senha = substr($this->cpf, 0, 4);
		$q->bind_param('sssssssssssss',$this->nome, $this->rg, $this->cpf, $this->email, $this->tel, $this->cep, $this->bairro, $this->rua,$this->numero, $this->complemento, $this->cidade, $this->uf, $senha);
		
		$q->execute();
		$q->close();
	}
	public function verificar_se_cliente_existe(){
		global $conn;

		$q = $conn->prepare("SELECT email FROM Cliente WHERE 
			AES_DECRYPT(email, UNHEX(SHA2('$this->chave', 512)))=? OR 
			AES_DECRYPT(cpf, UNHEX(SHA2('$this->chave', 512)))=? OR 
			AES_DECRYPT(rg, UNHEX(SHA2('$this->chave', 512)))=? OR 
			AES_DECRYPT(telefone, UNHEX(SHA2('$this->chave', 512)))=?");

		$q->bind_param('ssss', $this->email, $this->cpf, $this->rg, $this->tel);
		$q->execute();
		$get = $q->get_result();
		
		$arr = $get->fetch_assoc();

		if(empty($arr)){
			return true;
		}
		else return false;
		
	}

}

?>

