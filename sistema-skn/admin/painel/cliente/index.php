<?php

	session_start();

  	if(!isset($_SESSION['admin'])){

    header("location: http://" . $_SERVER['SERVER_NAME'] . "/sistema-skn/admin/");
  }

	$bancos = file_get_contents("../../../backend/json/bancos.json");
	$bancos = json_decode($bancos, true);

	require_once("../../../backend/classes/Admin.php");
  	$admin = new Admin;

  	if(isset($_GET['cpf']) && !empty($_GET['cpf'])){
  		$cpf = preg_replace("/[\.\-]/", "", $_GET['cpf']);

  		$cliente = $admin->mostrar_dados_cliente($cpf);
  		$negociacao = $admin->mostrar_dados_negociacao($cpf);
  		$etapa = $admin->mostrar_etapa_atual($cpf, $negociacao['id_negociacao']);
  	}
  	elseif((isset($_GET['etapas']) && !empty($_GET['etapas'])) && (isset($_GET['etapas']) && !empty($_GET['etapas']))){

  		$etapas = $admin->mostrar_todas_as_etapas($_GET['etapas'], $_GET['id_negociacao']);
  		print_r($etapas);
  	}

  	if(isset($_POST['etapa_atualizar'])){

  		$admin->atualizar_etapa($_POST['etapa'], $_POST['despacho'], $_POST['cpf'], $_POST['id_negociacao']);
  	}
  	if(isset($_POST['cliente'])){

  		$admin->atualizar_dados_cliente($_POST['nome'], $_POST['telefone'], $_POST['cep'], $_POST['rua'], $_POST['bairro'], $_POST['numero'], $_POST['complemento'], $_POST['cidade'], $_POST['uf'], $_POST['rg'], $_POST['cpf'], $_POST['email']);
  	}
  	if(isset($_POST['negociacao'])){

  		$admin->atualizar_dados_negociacao($_POST['valor_imovel'], $_POST['valor_financiamento'], $_POST['fgts'], $_POST['cartorio'], $_POST['banco'], $_POST['cidade'], $_POST['cpf'], $_POST['id_negociacao']);
  	}
  	if(isset($_POST['etapa'])){

  		$admin->atualizar_despacho($_POST['despacho'], $_POST['cpf'], $_POST['id_negociacao'], $_POST['id_etapa']);

  	}

?>
<?php require_once("../../../frontend/HTML/estilo_painel.html");?>

<nav role='navigation'>
  <form method="GET" action>
  	<h3>Pesquisar cliente pelo cpf:</h3>
   	<input type="search" name="cpf">
   	<input type="submit" value="Pesquisar">
  </form>
</nav>


<main role="main">

	<?php if(isset($_GET['cpf']) && empty($cliente)):?>
		<h2>Não existe nenhum cliente com esse cpf no sistema</h2>
	<?php endif;?>

	<?php if(!isset($_GET['etapas']) && !empty($cliente)):?>
		<section class="panel important">
			<h1>Cliente</h1>
			<form method="POST" action>
				<h2>Nome:</h2>
				<input type="text" name="nome" value="<?=$cliente['nome']?>" required autofocus/>
				<h2>Cpf:</h2>
				<input type="text" name="cpf" value="<?=$cliente['cpf']?>" required autofocus/>
				<h2>Rg:</h2>
				<input type="text" name="rg" value="<?=$cliente['rg']?>" required autofocus/>
				<h2>Telefone:</h2>
				<input type="text" name="telefone" value="<?=$cliente['telefone']?>" required autofocus/>
				<h2>E-mail:</h2>
				<input type="email" name="email" value="<?=$cliente['email']?>" required autofocus/>
				<h2>Cep:</h2>
				<input type="text" name="cep" value="<?=$cliente['cep']?>" required autofocus/>
				<h2>Rua:</h2>
				<input type="text" name="rua" value="<?=$cliente['rua']?>" required autofocus/>
				<h2>Bairro:</h2>
				<input type="text" name="bairro" value="<?=$cliente['bairro']?>" required autofocus/>
				<h2>Número:</h2>
				<input type="text" name="numero" value="<?=$cliente['numero']?>" required autofocus/>
				<h2>Complemento:</h2>
				<input type="text" name="complemento" value="<?=$cliente['complemento']?>" autofocus/>
				<h2>Cidade:</h2>
				<input type="text" name="cidade" value="<?=$cliente['cidade']?>" required autofocus/>
				<h2>Uf:</h2>
				<input type="text" name="uf" value="<?=$cliente['uf']?>" required autofocus/>

				<button type="submit" name="cliente">Atualizar dados cliente</button>
			</form>
		</section>




		<?php if(!empty($negociacao['status'])):?>
		<section class="panel important">
			<h1>Negociação:</h1>
			<form method="POST" action>
				<h2>Código:</h2>
				<input type="number" name="id_negociacao" value="<?=$negociacao['id_negociacao']?>" disabled autofocus/>
				<h2>Valor imóvel:</h2>
				<input type="text" name="valor_imovel" value="R$ <?=number_format($negociacao['valor_imovel'],2,",",".")?>" required autofocus/>
				<h2>Valor financimento:</h2>
				<input type="text" name="valor_financiamento" value="R$ <?=number_format($negociacao['valor_financiamento'],2,",",".")?>" required autofocus/>
				<h2>Valor fgts:</h2>
				<input type="text" name="fgts" value="R$ <?=number_format($negociacao['valor_fgts'],2,",",".")?>" required autofocus/>
				<h2>Cidade do imóvel:</h2>
				<input type="text" name="cidade" value="<?=$negociacao['cidade_imovel']?>" required autofocus/>
				<h2>Banco:</h2>
				<input type="text" name="banco" value="<?=$negociacao['banco']?>" required autofocus/>
				<h2>Cartório:</h2>
				<input type="text" name="cartorio" value="<?=$negociacao['cartorio']?>" required autofocus/>
				<h2>Data cadastro:</h2>
				<input type="text" name="dt_negociacao" value="<?=$negociacao['dt_negociacao']?>" disabled autofocus/>
				<h2>Hora:</h2>
				<input type="text" name="hr_negociacao" value="<?=$negociacao['hr_negociacao']?>" disabled autofocus/>
				<h2>Status:</h2>
				<input type="text" value="<?=$negociacao['status']?>" disabled autofocus/>
				<input type="hidden" name="cpf" value="<?=$cliente['cpf']?>">
				<input type="hidden" name="id_negociacao" value="<?=$negociacao['id_negociacao']?>"/>
				
				<button type="submit" name="negociacao">Atualizar dados negociação</button>
			</form>

		</section>

		<section class="panel important">
			<h1>Etapa atual:</h1>
			<?php if(!empty($etapa)):?>
				<form method="POST" action>
					<h2>Etapa:</h2>
					<input type="text" name="etapa" value="<?=$etapa['etapa']?>" disabled autofocus/>
					<h2>Despacho:</h2>
					<input type="text" name="despacho" value="<?=$etapa['despacho']?>" required autofocus/>
					<h2>Data:</h2>
					<input type="text" name="dt_etapa" value="<?=$etapa['dt_etapa']?>" disabled autofocus/>
					<h2>Hora:</h2>
					<input type="text" name="hr_etapa" value="<?=$etapa['hr_etapa']?>" disabled autofocus/>
					<input type="hidden" name="cpf" value="<?=$cliente['cpf']?>">
					<input type="hidden" name="id_negociacao" value="<?=$negociacao['id_negociacao']?>">
					<input type="hidden" name="id_etapa" value="<?=$etapa['id_etapa']?>">

					<button type="submit" name="etapa">Atualizar despacho</button>
				</form>
			<?php else: ?>
				<h2>Esse cliente ainda não possui nenhuma etapa</h2>
			<?php endif;?>
		</section>

		<section class="panel important"> 
			<h1>Atualizar etapa:</h1>
			<form method="POST" action>
					<h2>Etapa:</h2>
					<select name = "etapa">
						<option value="">Selecionar Banco:</option>
						<option>Análise de Crédito</option>
						<option>Aguardando documentação Completa</option>
						<option>Vistoria do Imóvel</option>
						<option>Débito de FGTS</option>
						<option>Análise Jurídica</option>
						<option>Interveniente Quitante</option>
						<option>Emissão de Contrato</option>
						<option>Aguardando documentos complementares</option>
						<option>Registro em Cartório</option>	
						<option>Finalizado</option>
					</select>
					<input type="hidden" name="cpf" value="<?=$cliente['cpf']?>">
					<input type="hidden" name="id_negociacao" value="<?=$negociacao['id_negociacao']?>">
					<h2>Despacho:</h2>
					<textarea name="despacho"></textarea>

					<button type="submit" name="etapa_atualizar">Atualizar etapa</button>
			</form>
					<button style="margin-top: 40px;"><a href="http://<?=$_SERVER['SERVER_NAME']?>/sistema-skn/admin/painel/cliente/?etapas=<?=$cliente['cpf']?>&id_negociacao=<?=$negociacao['id_negociacao']?>">Mostrar todas as etapas desse cliente</a></button>

		</section>
	


	<?php else:?>
		<section class="panel important">
			<h1>O processo anterior desse cliente foi finalizado! Clique no botão abaixo para criar uma nova negociação:</h1>


			<button><a href="http://<?=$_SERVER['SERVER_NAME']?>/sistema-skn/admin/painel/cadastrar-negociacao/?cpf=<?=$cliente['cpf']?>">Criar negociação</a></button>
		</section>
	<?php endif;?>
<?php elseif(isset($_GET['etapas']) && isset($_GET['id_negociacao'])):?>

		<section class="panel important">
			<h1>Histórico de etapas:</h1>
		</section>
			<?php if(!empty($etapas)):?>
				<?php for($i=0;$i<count($etapas);$i++):?>
					<section class="panel important">
						<form method="POST" action>
							<h2>Etapa:</h2>
							<input type="text" name="etapa" value="<?=$etapas[$i][0]?>" disabled autofocus/>
							<h2>Despacho:</h2>
							<input type="text" name="despacho" value="<?=$etapas[$i][1]?>" required autofocus/>
							<h2>Data:</h2>
							<input type="text" name="dt_etapa" value="<?=$etapas[$i][2]?>" disabled autofocus/>
							<h2>Hora:</h2>
							<input type="text" name="hr_etapa" value="<?=$etapas[$i][3]?>" disabled autofocus/>
							<h2>Situação:</h2>
							<input type="text" name="hr_etapa" value="<?=$etapas[$i][4]?>" disabled autofocus/>

							<input type="hidden" name="cpf" value="<?=$_GET['etapas']?>">
							<input type="hidden" name="id_negociacao" value="<?=$_GET['id_negociacao']?>">
							<input type="hidden" name="id_etapa" value="<?=$etapas[$i][5]?>">

							<button type="submit" name="etapa">Atualizar despacho</button>
						</form>
					</section>
				<?php endfor;?>
			<?php else:?>
				<h3>Esse cliente ainda não possui nenhuma etapa</h3>
			<?php endif;?>

<?php endif;?>
</main>
</body>