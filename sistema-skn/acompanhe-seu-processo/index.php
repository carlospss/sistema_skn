<?php

	session_start();

	if(!isset($_SESSION['id'])){
		header("location: http://" . $_SERVER['HTTP_HOST'] . "/sistema-skn/login/");
	}

	require_once("../backend/classes/Cliente.php");

	$cliente = new Cliente;

	$dados_negociacao = $cliente->filtrar_dados($_SESSION['id']);
  $etapas = $cliente->acompanhar_processo($_SESSION['id']);

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<script type="text/javascript">
   document.addEventListener('DOMContentLoaded', function() {
      const elementoAlvo = document.getElementById('atual');
      elementoAlvo.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
</script>

<style>
* {
  box-sizing: border-box;
}

body {
  font-family: Helvetica, sans-serif;
}
header{

  margin-bottom: 200px;
  line-height: 55px;
}

h1,h2{

  text-align: center;
}
h2,
h3{
  font-weight: 300;
  margin: 0;
}
h3, p{

  display: inline;
}
/* The actual timeline (the vertical ruler) */
.timeline {
  position: relative;
  max-width: 1200px;
  margin: 0 auto;
}

.etapa_atual{

  background-color: #29FD53;
  color:black;
  font-weight: bold;
}

.etapa{

  background-color: #B5B2B3;
  color:black;
  font-weight: bold;

}
#dt_hr{

  background-color: white;
}
/* The actual timeline (the vertical ruler) */
.timeline::after {
  content: '';
  position: absolute;
  width: 6px;
  background-color: black;
  top: 0;
  bottom: 0;
  left: 50%;
  margin-left: -3px;
}

/* Container around content */
.container {
  padding: 10px 40px;
  position: relative;
  background-color: white;
  width: 50%;
}

/* The circles on the timeline */
.container::after {
  content: '';
  position: absolute;
  width: 25px;
  height: 25px;
  right: -17px;
  background-color: white;
  border: 4px solid #FF9F55;
  top: 15px;
  border-radius: 50%;
  z-index: 1;
}

/* Place the container to the left */
.left {
  left: 0;
}

/* Place the container to the right */
.right {
  left: 50%;
}

/* Add arrows to the left container (pointing right) */
.left::before {
  content: " ";
  height: 0;
  position: absolute;
  top: 22px;
  width: 0;
  z-index: 1;
  right: 30px;
  border: medium solid black;
  border-width: 10px 0 10px 10px;
  border-color: transparent transparent transparent white;
}

/* Add arrows to the right container (pointing left) */
.right::before {
  content: " ";
  height: 0;
  position: absolute;
  top: 22px;
  width: 0;
  z-index: 1;
  left: 30px;
  border: medium solid black;
  border-width: 10px 10px 10px 0;
  border-color: transparent white transparent transparent;
}

/* Fix the circle for containers on the right side */
.right::after {
  left: -16px;
}

/* The actual content */
.content {
  padding: 20px 30px;
  background-color: white;
  position: relative;
  border-radius: 6px;
}

/* Media queries - Responsive timeline on screens less than 600px wide */
@media screen and (max-width: 600px) {
  /* Place the timelime to the left */
  .timeline::after {
  left: 31px;
  }
  
  /* Full-width containers */
  .container {
  width: 100%;
  padding-left: 70px;
  padding-right: 25px;
  }
  
  /* Make sure that all arrows are pointing leftwards */
  .container::before {
  left: 60px;
  border: medium solid black;
  border-width: 10px 10px 10px 0;
  border-color: transparent white transparent transparent;
  }

  /* Make sure all circles are at the same spot */
  .left::after, .right::after {
  left: 15px;
  }
  
  /* Make all right containers behave like the left ones */
  .right {
  left: 0%;
  }
}
</style>
</head>
<body>
<header>

  <h1>Acompanhe seu processo</h1>
  <h2>Seja bem vindo ao portal do cliente! Aqui você consegue acompanhar o processo da sua negociação.</h2>

  <h2>Nome do cliente: <?=$dados_negociacao['nome']?></h2>
  <h2>Cpf: <?=$dados_negociacao['cpf']?></h2>
  <h2>código da negociação: <?=$dados_negociacao['id_negociacao']?></h2>
</header>

<div class="timeline">
 
    <div class="container left">
     <?php if(isset($etapas[0])):?>
      <?php if(1 == count($etapas)):?>
        <span id="atual"></span>
      <?php endif;?>
      <div class="etapa_atual">
        <div id="dt_hr">
          <h3>Data:</h3>
          <p><?=$etapas[0][1]?></p>
          <h3>Hora:</h3>
          <p><?=$etapas[0][2]?></p>
        </div>

        <h2>Análise de Crédito</h2>
        <h3>Despacho:</h3>
        <p><?=$etapas[0][0]?></p>
      </div>
  <?php else:?>
      <div class="etapa">
        <h2>Análise de Crédito</h2>
        <h3>Agurdandando despacho...</h3>
      </div>
  <?php endif;?>
  </div>


  <div class="container right">
    <?php if(isset($etapas[1])):?>
      <?php if(2 == count($etapas)):?>
        <span id="atual"></span>
      <?php endif;?>
      <div class="etapa_atual">
         <div id="dt_hr">
          <h3>Data:</h3>
          <p><?=$etapas[1][1]?></p>
          <h3>Hora:</h3>
          <p><?=$etapas[1][2]?></p>
        </div>
        <h2>Aguardando documentação Completa</h2>
        <h3>Despacho:</h3>
        <p><?=$etapas[1][0]?></p>
      </div>
   <?php else:?>
      <div class="etapa">
        <h2>Aguardando documentação completa</h2>
        <h3>Agurdandando despacho...</h3>
      </div>
    <?php endif;?>
  </div>


  <div class="container left">
    <?php if(isset($etapas[2])):?>
      <?php if(3 == count($etapas)):?>
        <span id="atual"></span>
      <?php endif;?>
      <div class="etapa_atual">
         <div id="dt_hr">
          <h3>Data:</h3>
          <p><?=$etapas[2][1]?></p>
          <h3>Hora:</h3>
          <p><?=$etapas[2][2]?></p>
        </div>
        <h2>Vistoria do Imóvel</h2>
        <h3>Despacho:</h3>
        <p><?=$etapas[2][0]?></p>
      </div>
   <?php else:?>
      <div class="etapa">
        <h2>Vistoria do Imóvel</h2>
        <h3>Agurdandando despacho...</h3>
      </div>
    <?php endif;?>
  </div>


  <div class="container right">
    <?php if(isset($etapas[3])):?>
      <?php if(4 == count($etapas)):?>
        <span id="atual"></span>
      <?php endif;?>
      <div class="etapa_atual">
         <div id="dt_hr">
          <h3>Data:</h3>
          <p><?=$etapas[3][1]?></p>
          <h3>Hora:</h3>
          <p><?=$etapas[3][2]?></p>
        </div>
        <h2>Débito de FGTS</h2>
        <h3>Despacho:</h3>
        <p><?=$etapas[3][0]?></p>
      </div>
   <?php else:?>
      <div class="etapa">
        <h2>Débito de FGTS</h2>
        <h3>Agurdandando despacho...</h3>
      </div>
    <?php endif;?>
  </div>


  <div class="container left">
    <?php if(isset($etapas[4])):?>
      <?php if(5 == count($etapas)):?>
        <span id="atual"></span>
      <?php endif;?>
      <div class="etapa_atual">
         <div id="dt_hr">
          <h3>Data:</h3>
          <p><?=$etapas[4][1]?></p>
          <h3>Hora:</h3>
          <p><?=$etapas[4][2]?></p>
        </div>
        <h2>Análise jurídica</h2>
        <h3>Despacho:</h3>
        <p><?=$etapas[4][0]?></p>
      </div>
   <?php else:?>
      <div class="etapa">
        <h2>Análise Jurídica</h2>
        <h3>Agurdandando despacho...</h3>
      </div>
    <?php endif;?>
  </div>


  <div class="container right">
    <?php if(isset($etapas[5])):?>
      <?php if(6 == count($etapas)):?>
        <span id="atual"></span>
      <?php endif;?>
      <div class="etapa_atual">
         <div id="dt_hr">
          <h3>Data:</h3>
          <p><?=$etapas[5][1]?></p>
          <h3>Hora:</h3>
          <p><?=$etapas[5][2]?></p>
        </div>
        <h2>Interveniente Quitante</h2>
        <h3>Despacho:</h3>
        <p><?=$etapas[5][0]?></p>
      </div>
   <?php else:?>
      <div class="etapa">
        <h2>Interveniente Quitante</h2>
        <h3>Agurdandando despacho...</h3>
      </div>
    <?php endif;?>
  </div>

  <div class="container left">
    <?php if(isset($etapas[6])):?>
      <?php if(7 == count($etapas)):?>
        <span id="atual"></span>
      <?php endif;?>
      <div class="etapa_atual">
        <div id="dt_hr">
          <h3>Data:</h3>
          <p><?=$etapas[6][1]?></p>
          <h3>Hora:</h3>
          <p><?=$etapas[6][2]?></p>
        </div>
        <h2>Emissão de Contrato</h2>
        <h3>Despacho:</h3>
        <p><?=$etapas[6][0]?></p>
      </div>
   <?php else:?>
      <div class="etapa">
        <h2>Emissão de Contrato</h2>
        <h3>Agurdandando despacho...</h3>
      </div>
    <?php endif;?>
  </div>

  <div class="container right">
    <?php if(isset($etapas[7])):?>
      <?php if(8 == count($etapas)):?>
        <span id="atual"></span>
      <?php endif;?>
      <div class="etapa_atual">
         <div id="dt_hr">
          <h3>Data:</h3>
          <p><?=$etapas[7][1]?></p>
          <h3>Hora:</h3>
          <p><?=$etapas[7][2]?></p>
        </div>
        <h2>Aguardando documento complementares</h2>
        <h3>Despacho:</h3>
        <p><?=$etapas[7][0]?></p>
      </div>
   <?php else:?>
      <div class="etapa">
        <h2>Aguardando documento complementares</h2>
        <h3>Agurdandando despacho...</h3>
      </div>
    <?php endif;?>
  </div>

  <div class="container left">
    <?php if(isset($etapas[8])):?>
      <?php if(9 == count($etapas)):?>
        <span id="atual"></span>
      <?php endif;?>
      <div class="etapa_atual">
         <div id="dt_hr">
          <h3>Data:</h3>
          <p><?=$etapas[8][1]?></p>
          <h3>Hora:</h3>
          <p><?=$etapas[8][2]?></p>
        </div>
        <h2>Registro em cartório</h2>
        <h3>Despacho:</h3>
        <p><?=$etapas[8][0]?></p>
      </div>
   <?php else:?>
      <div class="etapa">
        <h2>Registro em cartório</h2>
        <h3>Agurdandando despacho...</h3>
      </div>
    <?php endif;?>
  </div>

  <div class="container right">
    <?php if(isset($etapas[9])):?>
      <?php if(10 == count($etapas)):?>
        <span id="atual"></span>
      <?php endif;?>
      <div class="etapa_atual">
         <div id="dt_hr">
          <h3>Data:</h3>
          <p><?=$etapas[9][1]?></p>
          <h3>Hora:</h3>
          <p><?=$etapas[9][2]?></p>
        </div>
        <h2>Finalizado</h2>
        <h3>Despacho:</h3>
        <p><?=$etapas[9][0]?></p>
      </div>
   <?php else:?>
      <div class="etapa">
        <h2>Finalizado</h2>
        <h3>Agurdandando despacho...</h3>
      </div>
    <?php endif;?>
  </div>


</div>

</body>
</html>
