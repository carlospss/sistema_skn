<?php

	class Negociacao{

		private $valor_imovel;
		private $valor_financiamento;
		private $fgts;
		private $cidade;
		private $cartorio;
		private $banco;
		private $cpf_cliente;
		private $bancos;
		private $chave;

		public function __construct($valor_imovel, $valor_financiamento, $fgts, $cartorio, $banco, $cidade, $cpf_cliente, $bancos){
			$this->valor_imovel = $valor_imovel;
			$this->valor_financiamento = $valor_financiamento;
			$this->fgts = $fgts;
			$this->cidade = $cidade;
			$this->cartorio = $cartorio;
			$this->banco = $banco;
			$this->bancos = $bancos;
			$this->cpf_cliente = preg_replace('/[.-]/', '', $cpf_cliente);
			$this->chave = file_get_contents("C:\\xampp\htdocs\sistema-skn\backend\conexao\chave.txt");
		}

		private function validar_valor_imovel(){
			$this->valor_imovel = preg_replace("[R\$\.\,\s]", $this->valor_imovel);
			settype($this->valor_imovel, 'float');
			if(!$this->valor_imovel) return false;
			if($this->valor_imovel < 20000) return false;
			
			return true;
		}		
		private function validar_valor_financiamento(){
			$this->valor_imovel = preg_replace("[R\$\.\,\s]", $this->valor_imovel);
			settype($this->valor_financiamento, 'float');
			if(!$this->valor_financiamento) return false;
			if($this->valor_financiamento < 20000) return false;
			
			return true;
			
		}
		private function validar_valor_fgts(){
			$this->valor_imovel = preg_replace("[R\$\.\,\s]", $this->valor_imovel);
			if($this->fgts){
				settype($this->fgts, 'float');
            	if(!$this->fgts) return false;
				if($this->fgts < 1000) return false;
			}

			return true;
		}
		private function validar_cidade(){

			if(preg_match('/^([a-zA-ZáàâãéèêíìîóôõòúùûçÁÀÂÃÉÈÊÍÌÎÓÒÔÕÚÙÛÇ]\s?){3,}$/u', $this->cidade)){
			
				$this->cidade = strtolower($this->cidade);

				$this->cidade = preg_replace('/\s/', '-', $this->cidade);
				$this->cidade = preg_replace('/[áàâã]/u', 'a',$this->cidade);
				$this->cidade = preg_replace('/[èéê]/u', 'e',$this->cidade);
				$this->cidade = preg_replace('/[íìî]/u', 'i',$this->cidade);
				$this->cidade = preg_replace('/[óòôõ]/u', 'o',$this->cidade);
				$this->cidade = preg_replace('/[úùû]/u', 'u',$this->cidade);
				$this->cidade = preg_replace('/ç/u', 'c', $this->cidade);

				$cidade = @file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/municipios/" . $this->cidade);
				if($cidade === '[]') return false;
				else return true;
			}
	}
	private function validar_cartorio(){

		if(preg_match('/^[a-zA-Z0-9áàâãéèêíìîóôõòúùûçÁÀÂÃÉÈÊÍÌÎÓÒÔÕÚÙÛÇ\s]+$/', $this->cartorio)) return true;
		else return false;
	}

	private function validar_banco(){

		if(!in_array($this->banco, $this->bancos)) return false;
		return true;
	}
	private function verificar_negociacao_em_andamento(){

		global $conn;
			$q = $conn->prepare("
				SELECT 
					*
				FROM
					Negociacao
				WHERE
					cpf_cliente_fk=AES_ENCRYPT(?, UNHEX(SHA2('$this->chave', 512)))
				AND	
					status= 'EM ANDAMENTO';
				");

			$q->bind_param('s',$this->cpf_cliente);
		
			$q->execute();
			$get = $q->get_result();

			$q->close();

			if(count($get->fetch_all()) > 0) return false;
			else return true;
			
	}

	private function salvar_dados(){

			global $conn;
			$q = $conn->prepare("
				INSERT INTO 
				Negociacao
					(cpf_cliente_fk, 
					valor_imovel, 
					valor_financiamento, 
					valor_fgts, 
					cidade_imovel, 
					cartorio,
					banco,
					dt_negociacao, 
					hr_negociacao, 
					status) 
				VALUE(
					AES_ENCRYPT(?, UNHEX(SHA2('$this->chave', 512))), 
					?, 
					?, 
					?, 
					?, 
					?, 
					?, 
					CURDATE(), 
					CURTIME(),
					'EM ANDAMENTO');
				");

			$q->bind_param('sssssss',$this->cpf_cliente, $this->valor_imovel, $this->valor_financiamento, $this->fgts, $this->cidade, $this->cartorio, $this->banco);
		
			$q->execute();
			$q->close();
			
	}

	public function validar_dados(){

		$erros = [];

		if(!$this->validar_valor_imovel()) $erros[] = 'valor_imovel';
		if(!$this->validar_valor_financiamento()) $erros[] = 'valor_financiamento';
		if(!$this->validar_valor_fgts()) $erros[] = 'fgts';
		if(!$this->validar_cidade()) $erros[] = 'cidade';
		if(!$this->validar_cartorio()) $erros[] = 'cartorio';
		if(!$this->validar_banco()) $erros[] = 'banco';
 		
 		if(empty($erros)){
 			if(!$this->verificar_negociacao_em_andamento()) return "Esse cliente já tem uma negociação em andamento";
 			$this->salvar_dados();
 			header("location: http://" . $_SERVER['SERVER_NAME'] . "/sistema-skn/admin/cliente/?cpf=."$this->cpf_cliente);
 			return true;
 		}else return $erros;
 		
	}	
}
?>