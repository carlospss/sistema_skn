<?php
  /*
  PÁGINA DE PROCESSOS EM ANDAMENTO:
    VAI MOSTRAR OS PROCESSOS QUE ESTÃO ATRASADOS;
    VAI POSSIBILITAR QUE O ADMIN FILTRE OS PROCESSOS POR ETAPA E/OU POR BANCO
  */
  session_start();

  if(!isset($_SESSION['admin'])){

    header("location: http://" . $_SERVER['SERVER_NAME'] . "/sistema-skn/admin/");
  }

  require_once("../../../backend/classes/Admin.php");
  $bancos = file_get_contents("../../../backend/json/bancos.json");
  $bancos = json_decode($bancos, true);

  $admin = new Admin;

  if(isset($_GET['processos_com_atraso']) && isset($_GET['pagina'])){

    $processos_com_atraso = $admin->mostrar_processos_com_atraso();
  }

  if(isset($_GET['bancos']) && isset($_GET['pagina']) && empty($_GET['etapa'])){
    $processos_por_banco = $admin->filtrar_processos_por_banco($_GET['bancos']);
     $qtd_processos_por_banco = $admin->qtd_processos_por_banco($_GET['bancos']);
  }

  if(isset($_GET['etapa']) && isset($_GET['pagina']) && empty($_GET['bancos'])){
      $processos_por_etapa = $admin->filtrar_processos_por_etapa($_GET['etapa']);

  }
   if(isset($_GET['etapa']) && !empty($_GET['etapa']) && isset($_GET['pagina']) && !empty($_GET['bancos'])){

      $processos_por_etapa_e_por_banco = $admin->filtrar_processos_por_etapa_e_por_banco($_GET['etapa'], $_GET['bancos']);
      $qtd_processos_por_etapa_e_por_banco = $admin->qtd_processos_por_etapa_e_por_banco($_GET['etapa'], $_GET['bancos']);
  }



  

?>
<?php require_once("../../../frontend/HTML/estilo_painel.html");?>


<nav role='navigation'>
  <h1>Processos:</h1>
  <li class="dashboard" style="background-color: red;"><a href="?processos_com_atraso&pagina=1">Mostrar processos com atraso</a></li>
    <div>
      
    </div>
    <h3>Filtros:</h3>
    <form method= "GET" action class="filtros">
      <?php foreach($bancos as $banco):?>
        <div>
          <input type="checkbox" name="bancos[]" value="<?=$banco?>"><?=$banco?>
        </div>
      <?php endforeach;?>

      <select name = "etapa">
            <option value="">Selecionar etapa</option>
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
          <button type="submit" name="pagina" value="1">Filtrar</button>
    </form>
</nav>

<main role="main">
  <!--PROCESSOS COM ATRASO-->
  <section class="panel important">
    <?php if(isset($processos_com_atraso)):?>
      <h2>Processos em atraso</h2>
      <h2>Quantidade:<?=count($processos_com_atraso)?></h2>
      <table> 
        <tr>
          <th>Nome:</th>
          <th>Cpf:</th>
          <th>Etapa atual:</th>
          <th>Data despacho</th>
          <th></th>
        </tr>

        <?php if(isset($_GET['pagina'])):?>
          <?php for($i=($_GET['pagina']-1)*10;$i<($_GET['pagina']-1)+10;$i++):?>
            <?php if(isset($processos_com_atraso[$i])):?>
              <tr>
                <td class = "negociacoes_atraso"><?=$processos_com_atraso[$i][0]?></td>
                <td class = "negociacoes_atraso"><?=$processos_com_atraso[$i][1]?></td>
                <td class = "negociacoes_atraso"><?=$processos_com_atraso[$i][2]?></td>
                <td class = "negociacoes_atraso"><?=$processos_com_atraso[$i][3]?></td>
                 <td class = "negociacoes_atraso"><a href="http://<?=$_SERVER['SERVER_NAME']?>/sistema-skn/admin/painel/cliente/?cpf=<?=$processos_com_atraso[$i][1]?>">Todos os dados desse cliente</a></td>
              </tr>
            <?php endif; ?>
          <?php endfor;?>
        </table>
        <?php if($_GET['pagina'] > 1):?>
          <button><a href="?processos_com_atraso&pagina=<?=$_GET['pagina']-1?>">Página anterior</a></button>
        <?php endif;?>
        <button><a href="?processos_com_atraso&pagina=<?=$_GET['pagina']+1?>">Próxima página</a></button>
      <?php endif;?>
    <?php endif;?>

    <!--PROCESSOS POR BANCO-->
    <?php if(isset($processos_por_banco)):?>
      <h2>Processos por banco</h2>
      <h2>Quantidades:</h2>

      <?php for($i=0;$i<count($qtd_processos_por_banco);$i++):?>
        <h2><?=$qtd_processos_por_banco[$i][0]?>:<?=$qtd_processos_por_banco[$i][1]?></h2>
      <?php endfor;?>
      <h2>Total: <?=count($processos_por_banco)?></h2>
      <table class = "negociacoes_banco"> 
        <tr>
          <th>Nome:</th>
          <th>Cpf:</th>
          <th>Banco:</th>
          <th>Etapa:</th>
          <th></th>
        </tr>

        <?php if(isset($_GET['pagina'])):?>
          <?php for($i=($_GET['pagina']-1)*10;$i<($_GET['pagina']-1)+10;$i++):?>
            <?php if(isset($processos_por_banco[$i])):?>
              <tr>
                <td class="negociacoes_normal"><?=$processos_por_banco[$i][0]?></td>
                <td class="negociacoes_normal"><?=$processos_por_banco[$i][1]?></td>
                <td class="negociacoes_normal"><?=$processos_por_banco[$i][2]?></td>
                <td class="negociacoes_normal"><?=$processos_por_banco[$i][3]?></td>
                 <td class="negociacoes_normal"><a href="http://<?=$_SERVER['SERVER_NAME']?>/sistema-skn/admin/painel/cliente/?cpf=<?=$processos_por_banco[$i][1]?>">Ver todos os dados</a></td>
              </tr>
            <?php else:?>
                <?php break;?>
            <?php endif; ?>
          <?php endfor;?>
        </table>

        <?php
          $url = $_SERVER['REQUEST_URI'];
          $url = preg_replace("/&pagina=[0-9]+/", "", $url);
        ?>

        <?php if($_GET['pagina'] > 1):?>
          <button><a href="<?=$url?>&pagina=<?=$_GET['pagina']-1?>">Página anterior</a></button>
        <?php endif;?>
        <button><a href="<?=$url?>&pagina=<?=$_GET['pagina']+1?>">Próxima página</a></button>
      <?php endif;?>
    <?php endif;?>

      <!--PROCESSOS POR ETAPA-->
     <?php if(isset($processos_por_etapa)):?>
      <h2>Processos por etapa: <?=$_GET['etapa']?></h2>
      <h2>Quantidade:<?=count($processos_por_etapa)?></h2>
      <table class = "negociacoes_banco"> 
        <tr>
          <th>Nome:</th>
          <th>Cpf:</th>
          <th>Etapa:</th>
          <th></th>
        </tr>

        <?php if(isset($_GET['pagina'])):?>
          <?php for($i=($_GET['pagina']-1)*10;$i<($_GET['pagina']-1)+10;$i++):?>
            <?php if(isset($processos_por_etapa[$i])):?>
              <tr>
                <td class="negociacoes_normal"><?=$processos_por_etapa[$i][0]?></td>
                <td class="negociacoes_normal"><?=$processos_por_etapa[$i][1]?></td>
                <td class="negociacoes_normal"><?=$processos_por_etapa[$i][2]?></td>
                <td class="negociacoes_normal"><a href="http://<?=$_SERVER['SERVER_NAME']?>/sistema-skn/admin/painel/cliente/?cpf=<?=$processos_por_etapa[$i][1]?>">Todos os dados desse cliente</a></td>
              </tr>
            <?php else:?>
                <?php break;?>
            <?php endif; ?>
          <?php endfor;?>
        </table>

        <?php
          $url = $_SERVER['REQUEST_URI'];
          $url = preg_replace("/&pagina=[0-9]+/", "", $url);
        ?>

        <?php if($_GET['pagina'] > 1):?>
          <button><a href="<?=$url?>&pagina=<?=$_GET['pagina']-1?>">Página anterior</a></button>
        <?php endif;?>
        <button><a href="<?=$url?>&pagina=<?=$_GET['pagina']+1?>">Próxima página</a></button>
      <?php endif;?>
    <?php endif;?>




     <!--PROCESSOS POR ETAPA E POR BANCO-->
     <?php if(isset($processos_por_etapa_e_por_banco)):?>
      <h2>Processos por banco e por etapa: <?=$_GET['etapa']?></h2>
      <h2>Quantidades:</h2>
      <?php for($i=0;$i<count($qtd_processos_por_etapa_e_por_banco);$i++):?>
        <h2><?=$qtd_processos_por_etapa_e_por_banco[$i][0]?>:<?=$qtd_processos_por_etapa_e_por_banco[$i][1]?></h2>
      <?php endfor;?>
      <h2>Total:<?=count($processos_por_etapa_e_por_banco)?></h2>
      <table class = "negociacoes_banco"> 
        <tr>
          <th>Nome:</th>
          <th>Cpf:</th>
          <th>Banco:</th>
          <th>Etapa:</th>
          <th></th>
        </tr>

        <?php if(isset($_GET['pagina'])):?>
          <?php for($i=($_GET['pagina']-1)*10;$i<($_GET['pagina']-1)+10;$i++):?>
            <?php if(isset($processos_por_etapa_e_por_banco[$i])):?>
              <tr>
                <td class="negociacoes_normal"><?=$processos_por_etapa_e_por_banco[$i][0]?></td>
                <td class="negociacoes_normal"><?=$processos_por_etapa_e_por_banco[$i][1]?></td>
                <td class="negociacoes_normal"><?=$processos_por_etapa_e_por_banco[$i][2]?></td>
                <td class="negociacoes_normal"><?=$processos_por_etapa_e_por_banco[$i][3]?></td>
                <td class="negociacoes_normal"><a href="http://<?=$_SERVER['SERVER_NAME']?>/sistema-skn/admin/painel/cliente/?cpf=<?=$processos_por_etapa_e_por_banco[$i][1]?>">Todos os dados desse cliente</a></td>
              </tr>
            <?php else:?>
                <?php break;?>
            <?php endif; ?>
          <?php endfor;?>
        </table>

        <?php
          $url = $_SERVER['REQUEST_URI'];
          $url = preg_replace("/&pagina=[0-9]+/", "", $url);
        ?>

        <?php if($_GET['pagina'] > 1):?>
          <button><a href="<?=$url?>&pagina=<?=$_GET['pagina']-1?>">Página anterior</a></button>
        <?php endif;?>
        <button><a href="<?=$url?>&pagina=<?=$_GET['pagina']+1?>">Próxima página</a></button>
      <?php endif;?>
    <?php endif;?>
      
       

  </section>

</main>
</body>