<?php

  session_start();

    if(!isset($_SESSION['admin'])){

    header("location: http://" . $_SERVER['SERVER_NAME'] . "/sistema-skn/admin/");
  }

	$bancos = file_get_contents("../../../backend/json/bancos.json");
	$bancos = json_decode($bancos, true);

  	require_once("../../../backend/classes/Admin.php");
  	$admin = new Admin;

  	print_r($_POST);
  	if(isset($_POST['valor_imovel']) && isset($_POST['valor_financiamento']) && isset($_POST['fgts']) && isset($_POST['cartorio']) && isset($_POST['banco']) && isset($_POST['municipio']) && isset($_POST['cpf'])) {

      $erros_negociacao = $admin->cadastrar_negociacao($_POST['valor_imovel'], $_POST['valor_financiamento'], $_POST['fgts'], $_POST['cartorio'], $_POST['banco'], $_POST['municipio'], $_POST['cpf'], $bancos);

  }


?>

<?php require_once("../../../frontend/HTML/estilo_painel.html");?>
<nav role='navigation'>
  <h1>Cadastrar nova negociacao:</h1>
</nav>

<main role="main">
  
  <section class="panel important">
  	<form method="POST" action>

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

        <input type="hidden" name="cpf" value="<?=$_GET['cpf']?>"/>

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



  	</form>
  
  </section>

</main>
</body>