<?php

    
    session_start();

      if(!isset($_SESSION['admin'])){

      header("location: http://" . $_SERVER['SERVER_NAME'] . "/sistema-skn/admin/");
    }
    
    if(isset($_GET['inicio']) && isset($_GET['final'])){
      require_once("../../../backend/classes/Admin.php");
      $admin = new Admin;
      $relatorio = $admin->gerar_relatorio($_GET['inicio'], $_GET['final']);

      $valor_total = 0;

      $inicio = DateTime::createFromFormat('Y-m-d', $_GET['inicio']);
      $fim = DateTime::createFromFormat('Y-m-d', $_GET['final']);
    }



?>

<?php require_once("../../../frontend/HTML/estilo_painel.html");?>

<nav role='navigation'>
    <h1>Gerar relatório:</h1>
  
    <form method= "GET" action>
      <h3>Data de início:</h3>
      <input type="date" name="inicio">
      <h3>Data de finalização:</h3>
      <input type="date" name="final">
      <button type="submit" value="Gerar relatório">Gerar relatório</button>
    </form>
</nav>

<main role="main">
  <section class="panel important">
    <?php if(isset($relatorio)):?>
      <h2>Relatório</h2>
      <h3>Período: de <?=$inicio->format('d/m/Y')?> a <?=$fim->format('d/m/Y')?></h3>
      <table class = "relatorio">
        <tr>
          <th>Nome:</th>
          <th>Cpf:</th>
          <th>Banco:</th>
          <th>Valor imóvel:</th>
          <th>Valor financiamento:</th>
          <th>Etapa:</th>
          <th>Data finalização:</th>
        </tr>

          <?php for($i=0;$i<count($relatorio);$i++):?>
              <?php $valor_total+=$relatorio[$i][4];?>
              <tr>
                <td><?=$relatorio[$i][0]?></td>
                <td><?=$relatorio[$i][1]?></td>
                <td><?=$relatorio[$i][2]?></td>
                <td>R$ <?=number_format($relatorio[$i][3],2,",",".")?></td>
                <td>R$ <?=number_format($relatorio[$i][4],2,",",".")?></td>
                <td><?=$relatorio[$i][5]?></td>
                <td><?=$relatorio[$i][6]?></td>
              </tr>
          <?php endfor;?>
        </table>
        <div><b style="color:black;">Soma de todos os valores de financiamento: R$ <?=number_format($valor_total,2,",",".")?></b></div>
    </main>
  </section>
  <?php endif;?>
</section>
</main>
</body>