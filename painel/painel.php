<?php /*require_once("php7_mysql_shim.php");*/

# Painel de administracao

require("include/func.php");

conectar();

// verifica se usuario esta logado
validar();

// consulta ultimas informacoes recebidas
$informacoes_sql = mysql_query("SELECT * FROM solicitacoes ORDER BY id_solicitacao DESC LIMIT 3") or die (mysql_error());

$num_info = mysql_num_rows(mysql_query("SELECT * FROM solicitacoes"));

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="stylesheet" type="text/css" href="css/painel2.css" />

<title>Disbromig - Painel de Administra&ccedil;&atilde;o</title>

</head>

<body>

<div id="geral">

    <div id="header">
        <div class="logo-disbromig"><a href="painel.php"><img src="../images/layout/disbromig_logo.jpg" border="0"></a></div>
    </div>
    
    <div id="separador"></div>
    
    <div id="menu">
    
     <div class="menu">
    
      <ul>
        <li><a href="lista_produtos.php">Produtos<!--[if gte IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
        <ul>
        	<li><a href="produto.php">Novo Produto</a></li>
            <li><a href="lista_produtos.php">Listar / Editar</a></li>
            <li><a href="caracteristicas.php">Nova Caract.</a></li>
            <li><a href="lista_caract.php">Listar Caract.</a></li>
        </ul>
        <!--[if lte IE 6]></td></tr></table></a><![endif]-->
	    </li>
        <li><a href="upload.php">Upload .XLS</a></li>
        <li><a href="solicitacoes.php">Solicita&ccedil;&otilde;es</a></li>
        <li><a href="usuarios.php">Usu&aacute;rios</a></li>
		
        <li><a href="perfil.php">Meus dados</a></li>
        <li><a href="index.php2?do=logout">Sair</a></li>
      </ul>
    
  </div>
  
  </div>
    
    <br />
    <br />
    
  <div id="conteudo">
    
    <?php
    
	// se nao houver novas solicitacoes nao exibir bloco
	if ($num_info != 0) {
	
	?>
		<div id="promocoes">
        	
       	<div class="promo-titulo">Informa&ccedil;&otilde;es Solicitadas</div>
            
      	<div class="promo-texto">
        	<?php
			
			while ($x=mysql_fetch_array($informacoes_sql)) {
				
				echo "<p>-&rsaquo; <a href='visualizar.php?solicitacao=".$x["id_solicitacao"]."'>";
				echo ucwords($x["nome"]);
				echo "</a> <br /> (<a href='mailto:".$x["email"]."'>".limitar($x["email"],30)."</a>)
					<br />
					<b>Empresa:</b> ".$x["empresa"]."</b>
					<br />
					<i>".$x["data"]."</i>
					<br />
					<a href='visualizar.php?solicitacao=".$x["id_solicitacao"]."'>Ver detalhes</a>
					</p>
					";
			
			}
			
			?>
         <div class="promo-footer"><a href="solicitacoes.php">Visualizar solicita&ccedil;&otilde;es antigas</a></div>
        </div>
            
        </div>
     <?
	  } # fecha num_info != 0
	 ?>
        
     <div class="conteudo-texto" style="font-size:.8em; padding:20px 0 15px 50px;">
     	<h2>Painel de administra&ccedil;&atilde;o</h2>
       <p>
       	Bem-vindo(a) <?=$_COOKIE["usuario"] ?>, para come&ccedil;ar escolha uma op&ccedil;&atilde;o abaixo:</p>
       <p>Produtos:</p>
       <ul>
         <li><a href="produto.php"> Cadastrar novo produto</a> </li>
         <li><a href="lista_produtos.php"> Listar produtos</a> </li>
         <li><a href="caracteristicas.php"> Cadastrar nova caracter&iacute;stica t&eacute;cnica</a></li>
         <li><a href="lista_caract.php"> Listar / Editar caracter&iacute;sticas t&eacute;cnicas</a></li>
       </ul>
       <p>Solicita&ccedil;&otilde;es:</p>
       <ul>
         <li><a href="solicitacoes.php">Ver informa&ccedil;&otilde;es solicitadas</a></li>
       </ul>
       <p>Estoque:</p>
       <ul>
         <li><a href="upload.php">Upload de planilha</a></li>
       </ul>
       <p>Usu&aacute;rios do sistema:</p>
       <ul>
         <li><a href="usuarios.php">Cadastrar, alterar e listar usu&aacute;rios</a></li>
       </ul>
       <p><a href="perfil.php">Alterar meus dados</a>       <br />
         <a href="index.php?do=logout">Sair do sistema</a></p>
     </div>
     
  </div>
     
     <br />
     
     <div id="rodape">
     	R. Heliodora, 100 - Vila Darcy Vargas &ndash; Contagem  &ndash; Minas Gerais &ndash; Brasil &ndash; CEP 32372-230
        <br />
        Fone: (031) 3393-2484 - <a href="mailto:disbromig@disbromig.com.br">disbromig@disbromig.com.br</a> - <a href="mailto:brobras@disbromig.com.br">brobras@disbromig.com.br</a>
     </div><p align='right'><span class='foton'>Desenvolvimento: <a href="http://www.foton.com.br" target='_blank'>F&oacute;ton</a></span></p>

</div>

</body>
</html>

<?php

desconectar();

?>