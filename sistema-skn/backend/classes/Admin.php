<?php

require_once("C:\\xampp\htdocs\sistema-skn\backend\conexao\conexao.php");
$chave = file_get_contents("C:\\xampp\htdocs\sistema-skn\backend\conexao\chave.txt");

class Admin{


	
	public function cadastrar_cliente($nome, $tel, $cep, $rua, $bairro, $numero, $complemento, $cidade, $uf, $rg, $cpf, $email)
	{
		require_once("Cadastro_Cliente.php");

		$cliente = new Cadastro_Cliente($nome, $tel, $cep, $rua, $bairro, $numero, $complemento, $cidade, $uf, $rg, $cpf, $email);
		return $cliente->validar_dados();
	}

	public function cadastrar_negociacao($valor_imovel, $valor_financiamento, $fgts, $cartorio, $banco, $cidade, $cpf_cliente, $bancos){

		require_once("Negociacao.php");

		$negociacao = new Negociacao($valor_imovel, $valor_financiamento, $fgts, $cartorio, $banco, $cidade, $cpf_cliente, $bancos);
		return $negociacao->validar_dados();
	}

	public function mostrar_dados_cliente($cpf){

		global $conn;
		global $chave;

		$q = $conn->prepare("

			SELECT
				nome,
				AES_DECRYPT(cpf, UNHEX(SHA2('$chave', 512))) AS cpf,
				AES_DECRYPT(rg, UNHEX(SHA2('$chave', 512))) AS rg,
				AES_DECRYPT(telefone, UNHEX(SHA2('$chave', 512))) AS telefone,
				AES_DECRYPT(email, UNHEX(SHA2('$chave', 512))) AS email,
				cep,
				rua,
				bairro,
				AES_DECRYPT(numero, UNHEX(SHA2('$chave', 512))) AS numero,
				complemento,
				cidade,
				uf
			FROM 
				Cliente
			WHERE
				cpf=AES_ENCRYPT(?, UNHEX(SHA2('$chave', 512)));
		");

		$q->bind_param('s', $cpf);
		$q->execute();

		$get = $q->get_result();

		return $get->fetch_assoc();
	}

	public function mostrar_dados_negociacao($cpf){

		global $conn;
		global $chave;

		$q = $conn->prepare("

			SELECT
				id_negociacao,
				valor_imovel,
				valor_financiamento,
				valor_fgts,
				cidade_imovel,
				banco,
				cartorio,
				DATE_FORMAT(dt_negociacao, '%d/%m/%Y') AS dt_negociacao,
				hr_negociacao,
				status
			FROM 
				Negociacao
			WHERE
				cpf_cliente_fk=AES_ENCRYPT(?, UNHEX(SHA2('$chave', 512)))
			AND 
				status = 'EM ANDAMENTO';
		");

		$q->bind_param('s', $cpf);
		$q->execute();

		$get = $q->get_result();

		return $get->fetch_assoc();
	}	


	public function mostrar_etapa_atual($cpf, $id_negociacao){

		global $conn;
		global $chave;

		$q = $conn->prepare("

			SELECT
				etapa,	
				despacho,
				DATE_FORMAT(dt_etapa, '%d/%m/%Y') AS dt_etapa,
				hr_etapa,
				id_etapa		
			FROM 
				Etapas 
			WHERE
				cpf_cliente=AES_ENCRYPT(?, UNHEX(SHA2('$chave', 512)))
			AND 
				status='ATUAL'
			AND
				id_negociacao_fk=?;
		");

		$q->bind_param('ss', $cpf, $id_negociacao);
		$q->execute();

		$get = $q->get_result();

		return $get->fetch_assoc();
	}

	public function mostrar_todas_as_etapas($cpf, $id_negociacao){

		global $conn;
		global $chave;

		$q = $conn->prepare("

			SELECT
				etapa,	
				despacho,
				DATE_FORMAT(dt_etapa, '%d/%m/%Y') AS dt_etapa,
				hr_etapa,
				status,
				id_etapa		
			FROM 
				Etapas 
			WHERE
				cpf_cliente=AES_ENCRYPT(?, UNHEX(SHA2('$chave', 512)))
			AND
				id_negociacao_fk=?;
		");

		$q->bind_param('ss', $cpf, $id_negociacao);
		$q->execute();

		$get = $q->get_result();

		return $get->fetch_all();

	}

	public function atualizar_etapa($etapa, $despacho, $cpf, $id_negociacao){
		global $conn;
		global $chave;
		$q = $conn->prepare("

			INSERT INTO
				Etapas(
					etapa,
					despacho,
					cpf_cliente,
					id_negociacao_fk,
					dt_etapa,
					hr_etapa,
					status
				)
				VALUE(

					?,
					?,
					AES_ENCRYPT(?, UNHEX(SHA2('$chave', 512))),
					?,
					CURDATE(),
					CURTIME(),
					'ATUAL'
				);
		");

		$q->bind_param('ssss', $etapa, $despacho, $cpf, $id_negociacao);
		$q->execute();

		$q = $conn->prepare("

			UPDATE
				Etapas
			SET 
				status=''
			WHERE
				etapa != ?
			AND
				cpf_cliente=AES_ENCRYPT(?, UNHEX(SHA2('$chave', 512)))
			AND 
				id_negociacao_fk=?;

		");

		$q->bind_param('sss', $etapa, $cpf, $id_negociacao);
		$q->execute();


		if($etapa == "Finalizado"){

			$q = $conn->prepare("

				UPDATE 
					Negociacao
				SET 
				 	status='FINALIZADO'
				WHERE 
					cpf_cliente_fk=AES_ENCRYPT(?, UNHEX(SHA2('$chave', 512)));

			");

			$q->bind_param('s', $cpf);
			$q->execute();

			$q->close();

		}

		header("location: ?cpf=$cpf");
	}



	public function atualizar_despacho($despacho, $cpf, $id_negociacao, $id_etapa){

		global $conn;
		global $chave;

		$q = $conn->prepare("

			UPDATE
				Etapas
			SET
				despacho=?,
				dt_etapa=CURDATE(),
				hr_etapa=CURTIME()
			WHERE 
				cpf_cliente=AES_ENCRYPT(?, UNHEX(SHA2('$chave', 512)))
			AND
				id_negociacao_fk=?
			AND
				id_etapa=?;

		");
		$q->bind_param('ssss',$despacho, $cpf, $id_negociacao, $id_etapa);

		$q->execute();

		header("location: ?cpf=$cpf");

	}

	
	public function atualizar_dados_cliente($nome, $telefone, $cep, $rua, $bairro, $numero, $complemento, $cidade, $uf, $rg, $cpf, $email){
				
		global $conn;
		global $chave;

		$q = $conn->prepare("

			UPDATE
				Cliente
			SET
				nome=?,
				cpf=AES_ENCRYPT(?, UNHEX(SHA2('$chave', 512))),
				rg = AES_ENCRYPT(?, UNHEX(SHA2('$chave', 512))),
				telefone=AES_ENCRYPT(?, UNHEX(SHA2('$chave', 512))),
				email=AES_ENCRYPT(?, UNHEX(SHA2('$chave', 512))),
				cep=?,
				rua=?,
				bairro=?,
				numero=AES_ENCRYPT(?, UNHEX(SHA2('$chave', 512))),
				complemento=?,
				cidade=?,
				uf=?
			WHERE 
				cpf=AES_ENCRYPT(?, UNHEX(SHA2('$chave', 512)));

		");
		$q->bind_param('sssssssssssss',$nome, $cpf, $rg, $telefone,  $email,  $cep, $rua, $bairro, $numero, $complemento, $cidade, $uf, $cpf);

		$q->execute();

		header("location: ?cpf=$cpf");

	}
	
	public function atualizar_dados_negociacao($valor_imovel, $valor_financiamento, $fgts, $cartorio, $banco, $cidade, $cpf, $id_negociacao){

		global $conn;
		global $chave;

		$valor_imovel = preg_replace("/[R$\.\,\s]/", "", $valor_imovel);
			settype($valor_imovel, 'float');

		$valor_financiamento = preg_replace("/[R$\.\,\s]/", "", $valor_financiamento);
			settype($valor_financiamento, 'float');

		$fgts = preg_replace("/[R\$\.\,\s]/", "", $fgts);
			settype($fgts, 'float');

		$q = $conn->prepare("

			UPDATE
				Negociacao
			SET
				valor_imovel=?,
				valor_financiamento=?,
				valor_fgts=?,
				cartorio=?,
				banco=?,
				cidade_imovel=?
			WHERE 
				cpf_cliente_fk=AES_ENCRYPT(?, UNHEX(SHA2('$chave', 512)))
			AND
				id_negociacao=?;

		");
		$q->bind_param('ssssssss',$valor_imovel, $valor_financiamento, $fgts, $cartorio, $banco, $cidade, $cpf, $id_negociacao);

		$q->execute();

		header("location: ?cpf=$cpf");
	}
	public function mostrar_processos_com_atraso(){

		global $conn;
		global $chave;

		$q = $conn->prepare("

				SELECT
					c.nome,
					AES_DECRYPT(cpf, UNHEX(SHA2('$chave', 512))) AS cpf,
					e.etapa,
					DATE_FORMAT(e.dt_etapa, '%d/%m/%Y') AS dt_etapa
				FROM 
					Cliente c
				LEFT JOIN
					Etapas e ON c.cpf=e.cpf_cliente
				WHERE
					e.status='ATUAL'
				AND
					e.etapa != 'FINALIZADO'
				AND
					DAY(CURDATE()) - DAY(e.dt_etapa) >= 5
				AND
					YEAR(CURDATE()) = YEAR(e.dt_etapa);  
		");

		$q->execute();

		$get = $q->get_result();

		return $get->fetch_all(); 
	}

	public function filtrar_processos_por_banco($bancos){

		global $conn;
		global $chave;

		$condicao_sql = ""; 
		foreach ($bancos as $banco){
    		$condicao_sql .= "n.banco='". $banco . "' OR "; 
		}

		$condicao_sql = rtrim($condicao_sql, " OR ");

		$q = $conn->query("

			SELECT
				c.nome,
				AES_DECRYPT(c.cpf, UNHEX(SHA2('$chave', 512))) AS cpf,
				n.banco,
				e.etapa
			FROM 
				Cliente c
			LEFT JOIN
				Negociacao n ON c.cpf=n.cpf_cliente_fk
			LEFT JOIN
				Etapas e ON e.cpf_cliente=c.cpf
			WHERE
				($condicao_sql)
			AND
				e.status = 'ATUAL'
			AND
				MONTH(CURDATE()) = MONTH(e.dt_etapa) 
			AND
				YEAR(CURDATE()) = YEAR(e.dt_etapa)
			ORDER BY 
				n.banco;  	

		");

		return $q->fetch_all();
	}
	public function qtd_processos_por_banco($bancos){

		global $conn;
		global $chave;

		$condicao_sql = ""; 
		foreach ($bancos as $banco){
    		$condicao_sql .= "n.banco='". $banco . "' OR "; 
		}

		$condicao_sql = rtrim($condicao_sql, " OR ");

		$q = $conn->query("

			SELECT
				n.banco,
				COUNT(*) AS qtd_bancos
			FROM 
				Cliente c
			LEFT JOIN
				Negociacao n ON c.cpf=n.cpf_cliente_fk
			LEFT JOIN
				Etapas e ON e.cpf_cliente=c.cpf
			WHERE
				($condicao_sql)
			AND
				e.status = 'ATUAL'
			AND
				MONTH(CURDATE()) = MONTH(e.dt_etapa) 
			AND
				YEAR(CURDATE()) = YEAR(e.dt_etapa)
			GROUP BY
				n.banco
			ORDER BY 
				n.banco;  	

		");

		return $q->fetch_all();

	}
	public function filtrar_processos_por_etapa($etapa){

		global $conn;
		global $chave;

		$q = $conn->prepare("

			SELECT
				c.nome,
				AES_DECRYPT(c.cpf, UNHEX(SHA2('$chave', 512))) AS cpf,
				e.etapa
			FROM 
				Cliente c
			LEFT JOIN
				Etapas e ON e.cpf_cliente=c.cpf
			WHERE
				e.etapa=?
			AND
				e.status = 'ATUAL'
			AND
				MONTH(CURDATE()) = MONTH(e.dt_etapa) 
			AND
				YEAR(CURDATE()) = YEAR(e.dt_etapa);	
		");

		$q->bind_param('s', $etapa);

		$q->execute();

		$get = $q->get_result();

		return $get->fetch_all();
	}

	public function filtrar_processos_por_etapa_e_por_banco($etapa, $bancos){

		global $conn;
		global $chave;

		$condicao_sql = ""; 
		foreach ($bancos as $banco){
    		$condicao_sql .= "n.banco='". $banco . "' OR "; 
		}

		$condicao_sql = rtrim($condicao_sql, " OR ");

		$q = $conn->query("

			SELECT
				c.nome,
				AES_DECRYPT(c.cpf, UNHEX(SHA2('$chave', 512))) AS cpf,
				n.banco,
				e.etapa
			FROM 
				Cliente c
			LEFT JOIN
				Negociacao n ON c.cpf=n.cpf_cliente_fk
			LEFT JOIN
				Etapas e ON e.cpf_cliente=c.cpf
			WHERE
				($condicao_sql)
			AND
				e.etapa='$etapa'
			AND
				e.status = 'ATUAL'
			AND
				MONTH(CURDATE()) = MONTH(e.dt_etapa) 
			AND
				YEAR(CURDATE()) = YEAR(e.dt_etapa)
			ORDER BY 
				n.banco;  	

		");

		return $q->fetch_all();
	}

	public function qtd_processos_por_etapa_e_por_banco($etapa, $bancos){

		global $conn;
		global $chave;

		$condicao_sql = ""; 
		foreach ($bancos as $banco){
    		$condicao_sql .= "n.banco='". $banco . "' OR "; 
		}

		$condicao_sql = rtrim($condicao_sql, " OR ");

		$q = $conn->query("

			SELECT
				n.banco,
				COUNT(*) AS qtd_banco
			FROM 
				Cliente c
			LEFT JOIN
				Negociacao n ON c.cpf=n.cpf_cliente_fk
			LEFT JOIN
				Etapas e ON e.cpf_cliente=c.cpf
			WHERE
				($condicao_sql)
			AND
				e.etapa='$etapa'
			AND
				e.status = 'ATUAL'
			AND
				MONTH(CURDATE()) = MONTH(e.dt_etapa) 
			AND
				YEAR(CURDATE()) = YEAR(e.dt_etapa)
			GROUP BY
				n.banco
			ORDER BY 
				n.banco;  	

		");

		return $q->fetch_all();
	}

	public function gerar_relatorio($inicio, $fim){
		global $conn;
		global $chave;

		$q = $conn->prepare("

				SELECT 
					c.nome,
					AES_DECRYPT(c.cpf, UNHEX(SHA2('$chave', 512))) AS cpf,
					n.banco,
					n.valor_imovel,
					n.valor_financiamento,
					e.etapa,
					DATE_FORMAT(e.dt_etapa, '%d/%m/%Y') AS dt_etapa
				FROM
					Cliente c
				LEFT JOIN 
					Negociacao n ON c.cpf=n.cpf_cliente_fk
				LEFT JOIN
					Etapas e ON c.cpf=e.cpf_cliente
				WHERE
					e.dt_etapa >= ?
				AND	
					e.dt_etapa <= ?
				AND
					e.etapa = 'FINALIZADO';
	
		");			

		$q->bind_param('ss' , $inicio, $fim);
		$q->execute();
		$get = $q->get_result();

		return $get->fetch_all();
	}

}	

?>