<?php


require_once("C:\\xampp\htdocs\sistema-skn\backend\conexao\conexao.php");
$chave = file_get_contents("C:\\xampp\htdocs\sistema-skn\backend\conexao\chave.txt");

class Cliente{


	public function acompanhar_processo($cpf){

		global $conn;
		global $chave;

		$q = $conn->prepare("

			SELECT
				e.despacho,
				DATE_FORMAT(e.dt_etapa, '%d/%m/%Y') AS dt_etapa,
				e.hr_etapa
			FROM 
				Etapas e
			LEFT JOIN
				Cliente c ON e.cpf_cliente = c.cpf
			LEFT JOIN
				Negociacao n ON n.id_negociacao = e.id_negociacao_fk
			WHERE 
				n.status = 'EM ANDAMENTO'
			AND
				c.cpf = UNHEX(?);
		");

		$q->bind_param('s', $cpf);
		$q->execute();

		$get = $q->get_result();

		return $get->fetch_all();

	}

	public function filtrar_dados($cpf){

		global $conn;
		global $chave;

		$q = $conn->prepare("

			SELECT
				c.nome,
				AES_DECRYPT(c.cpf, UNHEX(SHA2('$chave', 512))) AS cpf,
				n.id_negociacao
			FROM 
				Cliente c
			LEFT JOIN
				Negociacao n ON n.cpf_cliente_fk = c.cpf
			WHERE 
				c.cpf = UNHEX(?);
		");

		$q->bind_param('s', $cpf);
		$q->execute();

		$get = $q->get_result();

		return $get->fetch_assoc();
	}
}


?>