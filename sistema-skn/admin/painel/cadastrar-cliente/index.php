<?php
	
  //PÁGINA DESTINADA AO CADASTRO DO CLIENTE E DA NEGOCIAÇÃO DESSE CLIENTE
  

  session_start();

  if(!isset($_SESSION['admin'])){

    header("location: http://" . $_SERVER['SERVER_NAME'] . "/sistema-skn/admin/");
  }
  

	$bancos = file_get_contents("../../../backend/json/bancos.json");
	$bancos = json_decode($bancos, true);

  require_once("../../../backend/classes/Admin.php");
  $admin = new Admin;

	if(isset($_POST['nome']) && isset($_POST['telefone']) && isset($_POST['cep']) && isset($_POST['rua']) && isset($_POST['bairro']) &&isset($_POST['numero']) && isset($_POST['complemento']) && isset($_POST['cidade']) && isset($_POST['uf']) && isset($_POST['rg']) && isset($_POST['cpf']) && isset($_POST['email'])) {

		  $erros_cliente = $admin->cadastrar_cliente($_POST['nome'], $_POST['telefone'], $_POST['cep'], $_POST['rua'], $_POST['bairro'], $_POST['numero'], $_POST['complemento'], $_POST['cidade'], $_POST['uf'], $_POST['rg'], $_POST['cpf'], $_POST['email']);


  }

  if(isset($_POST['valor_imovel']) && isset($_POST['valor_financiamento']) && isset($_POST['fgts']) && isset($_POST['cartorio']) && isset($_POST['banco']) && isset($_POST['municipio']) && isset($_POST['cpf'])) {

      $erros_negociacao = $admin->cadastrar_negociacao($_POST['valor_imovel'], $_POST['valor_financiamento'], $_POST['fgts'], $_POST['cartorio'], $_POST['banco'], $_POST['municipio'], $_POST['cpf'], $bancos);

  }


    if(isset($erros_negociacao) && $erros_negociacao === true){

        $cpf = preg_replace('/[.-]/', '', $_POST['cpf']);
        header("location: http://" . $_SERVER['SERVER_NAME'] . "/sistema-skn/admin/painel/cliente/?cpf=".$cpf);
    }
  
    
?>
<?php require_once("../../../frontend/HTML/estilo_painel.html");?>


<
<nav role='navigation'>
  <h1>Cadastrar cliente:</h1>
</nav>


<main role="main">
  
  <section class="panel important">
    <?php if(isset($erros_cliente) && is_string($erros_cliente)):?>
      <span><?=$erros_cliente?></span>
    <?php endif;?>
  	<form method="POST" action>
      <?php if($_SERVER['REQUEST_METHOD'] == "GET" || (isset($erros_cliente) && $erros_cliente !== true)):?>
  		  <h1>Cadastrar cliente</h1>

  		  <h2>Nome:</h2>
        <?php if(isset($_POST['nome'])):?>
  		    <input type="text" tabindex="1" name="nome" value="<?=$_POST['nome']?>"required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="nome" required autofocus/>
        <?php endif;?>

        <?php if(isset($erros_cliente) && is_array($erros_cliente) && in_array('nome', $erros_cliente)):?>
          <span class="resp">Por favor, digite um nome válido</span>
        <?php endif; ?>

  		  <h2>Rg:</h2>
      	<?php if(isset($_POST['rg'])):?>
          <input type="text" tabindex="1" name="rg" value="<?=$_POST['rg']?>"required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="rg" required autofocus/>
        <?php endif;?>

        <?php if(isset($erros_cliente) && is_array($erros_cliente) && in_array('rg', $erros_cliente)):?>
          <span class="resp">Por favor, digite um número de rg válido</span>
        <?php endif; ?>

      	<h2>Cpf:</h2>
      	 <?php if(isset($_POST['cpf'])):?>
          <input type="text" tabindex="1" name="cpf" value="<?=$_POST['cpf']?>"required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="cpf" required autofocus/>
        <?php endif;?>

          <?php if(isset($erros_cliente) && is_array($erros_cliente) && in_array('cpf', $erros_cliente)):?>
            <span class="resp">Por favor, digite um número de cpf válido</span>
          <?php endif; ?>

      	<h2>E-mail:</h2>
      	 <?php if(isset($_POST['email'])):?>
          <input type="text" tabindex="1" name="email" value="<?=$_POST['email']?>"required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="email" required autofocus/>
        <?php endif;?>

         <?php if(isset($erros_cliente) && is_array($erros_cliente) && in_array('email', $erros_cliente)):?>
            <span class="resp">Por favor, digite um email válido</span>
          <?php endif; ?>

      	<h2>Telefone:</h2>
      	 <?php if(isset($_POST['telefone'])):?>
          <input type="text" tabindex="1" name="telefone" value="<?=$_POST['telefone']?>"required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="telefone" required autofocus/>
        <?php endif;?>

          <?php if(isset($erros_cliente) && is_array($erros_cliente) && in_array('tel', $erros_cliente)):?>
            <span class="resp">Por favor, digite um número de telefone válido</span>
          <?php endif; ?>

      	<h2>Cep:</h2>
      	 <?php if(isset($_POST['cep'])):?>
          <input type="text" tabindex="1" name="cep" value="<?=$_POST['cep']?>"required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="cep" required autofocus/>
        <?php endif;?>

         <?php if(isset($erros_cliente) && is_array($erros_cliente) && in_array('cep', $erros_cliente)):?>
            <span class="resp">Por favor, digite um cep válido</span>
         <?php endif; ?>

      	<h2>Bairro:</h2>
      	 <?php if(isset($_POST['bairro'])):?>
          <input type="text" tabindex="1" name="bairro" value="<?=$_POST['bairro']?>"required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="bairro" required autofocus/>
        <?php endif;?>

         <?php if(isset($erros_cliente) && is_array($erros_cliente) && in_array('bairro', $erros_cliente)):?>
            <span class="resp">Por favor, digite um nome de bairro válido e que condiz com o cep</span>
         <?php endif; ?>

      	<h2>Rua:</h2>
      	 <?php if(isset($_POST['rua'])):?>
          <input type="text" tabindex="1" name="rua" value="<?=$_POST['rua']?>"required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="rua" required autofocus/>
        <?php endif;?>

         <?php if(isset($erros_cliente) && is_array($erros_cliente) && in_array('rua', $erros_cliente)):?>
            <span class="resp">Por favor, digite um nome de rua válido e que condiz com o cep</span>
         <?php endif; ?>

      	<h2>Número:</h2>
      	 <?php if(isset($_POST['numero'])):?>
          <input type="text" tabindex="1" name="numero" value="<?=$_POST['numero']?>"required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="numero" required autofocus/>
        <?php endif;?>

         <?php if(isset($erros_cliente) && is_array($erros_cliente) && in_array('numero', $erros_cliente)):?>
            <span class="resp">Por favor, digite um número de telefone válido</span>
         <?php endif; ?>

      	<h2>Complemento:</h2>
      	 <?php if(isset($_POST['complemento'])):?>
          <input type="text" tabindex="1" name="complemento" value="<?=$_POST['complemento']?>" autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="complemento" autofocus/>
        <?php endif;?>

         <?php if(isset($erros_cliente) && is_array($erros_cliente) && in_array('complemento', $erros_cliente)):?>
            <span class="resp">Por favor, digite um complemento válido</span>
         <?php endif; ?>

      	<h2>Cidade:</h2>
      	 <?php if(isset($_POST['cidade'])):?>
          <input type="text" tabindex="1" name="cidade" value="<?=$_POST['cidade']?>"required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="cidade" required autofocus/>
        <?php endif;?>

         <?php if(isset($erros_cliente) && is_array($erros_cliente) && in_array('cidade', $erros_cliente)):?>
            <span class="resp">Por favor, digite um nome de cidade válido e que condiz com o cep</span>
         <?php endif; ?>

      	<h2>Uf:</h2>
      	 <?php if(isset($_POST['uf'])):?>
          <input type="text" tabindex="1" name="uf" value="<?=$_POST['uf']?>"required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="uf" required autofocus/>
        <?php endif;?>

         <?php if(isset($erros_cliente) && is_array($erros_cliente) && in_array('uf', $erros_cliente)):?>
            <span class="resp">Por favor, digite uma uf válida e que condiz com o cep</span>
         <?php endif; ?>

          <input type="submit" value="Cadastrar cliente"/>

      <?php endif;?>




      <?php if((isset($erros_cliente) && $erros_cliente === true) || (isset($erros_negociacao) && $erros_negociacao === true)):?>
      	<h1>Negociação</h1>
        <?php if(isset($erros_negociacao) && is_string($erros_negociacao)):?>
          <span><?=$erros_negociacao?></span>
        <?php endif;?>
      	<h2>Valor do imóvel:</h2>	
      	 <?php if(isset($_POST['valor_imovel'])):?>
          <input type="text" tabindex="1" name="valor_imovel" value="<?=$_POST['valor_imovel']?>"required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="valor_imovel" required autofocus/>
        <?php endif;?>

         <?php if(isset($erros_negociacao) && is_array($erros_negociacao) && in_array('valor_imovel', $erros_negociacao)):?>
            <span class="resp">Por favor, digite um valor válido</span>
         <?php endif; ?>

      	<h2>Valor do financiamento:</h2>
      	 <?php if(isset($_POST['valor_financiamento'])):?>
          <input type="text" tabindex="1" name="valor_financiamento" value="<?=$_POST['valor_financiamento']?>"required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="valor_financiamento" required autofocus/>
        <?php endif;?>

        <?php if(isset($erros_negociacao) && is_array($erros_negociacao) && in_array('valor_financiamento', $erros_negociacao)):?>
          <span class="resp">Por favor, digite um valor válido</span>
        <?php endif; ?>

      	<h2>Fgts:</h2>
      	 <?php if(isset($_POST['fgts'])):?>
          <input type="text" tabindex="1" name="fgts" value="<?=$_POST['fgts']?>"autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="fgts" autofocus/>
        <?php endif;?>

        <?php if(isset($erros_negociacao) && is_array($erros_negociacao) && in_array('fgts', $erros_negociacao)):?>
          <span class="resp">Por favor, digite um valor válido</span>
        <?php endif; ?>

      	<h2>Cidade do imóvel:</h2>
      	 <?php if(isset($_POST['municipio'])):?>
          <input type="text" tabindex="1" name="municipio" value="<?=$_POST['municipio']?>"required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="municipio" required autofocus/>
        <?php endif;?>

        <?php if(isset($erros_negociacao) && is_array($erros_negociacao) && in_array('cidade', $erros_negociacao)):?>
          <span class="resp">Por favor, digite um município válido</span>
        <?php endif; ?>

      	<h2>Cartório:</h2>
      	 <?php if(isset($_POST['cartorio'])):?>
          <input type="text" tabindex="1" name="cartorio" value="<?=$_POST['cartorio']?>"required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="cartorio" required autofocus/>
        <?php endif;?>

        <?php if(isset($erros_negociacao) && is_array($erros_negociacao) && in_array('cartorio', $erros_negociacao)):?>
          <span class="resp">Por favor, digite um nome de cartório válido</span>
        <?php endif; ?>

        <input type="hidden" name="cpf" value="<?=$_POST['cpf']?>"/>

      	<h2>Banco:</h2>
         <?php if(isset($_POST['banco'])):?>
          <input type="text" tabindex="1" name="banco" value="<?=$_POST['banco']?>" list="bancos" required autofocus/>
        <?php else:?>
          <input type="text" tabindex="1" name="banco" list="bancos" required autofocus/>
        <?php endif;?>

        <?php if(isset($erros_negociacao) && is_array($erros_negociacao) && in_array('banco', $erros_negociacao)):?>
          <span class="resp">Por favor, digite um banco que cadastrado</span>
        <?php endif; ?>

  			<datalist id="bancos">
  				<?php foreach($bancos as $banco):?>
  					<option value="<?=$banco?>"></option>
  				<?php endforeach;?>
  			</datalist>
        <input type="submit" value="Cadastrar negociação"/>
      <?php	endif;?>

		
		
		
		
  	</form>
  
  </section>

</main>
</body>