<?php 

#visualizar.php - visualiza solicitacoes


require("include/func.php");

conectar();

// verifica se usuario esta logado
validar();

$sid = $_REQUEST["solicitacao"];

// busca informacoes no bd
$solicitacao_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE id_solicitacao='".$sid."'");

while ($r=mysqli_fetch_array($solicitacao_sql)) {
	extract($r);
}

$produtos = str_replace(",","<br />",$produtos);

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
        <li><a href="index.php?do=logout">Sair</a></li>
      </ul>
    
  </div>
  
  </div>
    
    <br />
    <br />
    
  <div id="conteudo">
        
     <div class="conteudo-texto" id="ctexto">
     	
        <h1>Solicita&ccedil;&atilde;o de <?=$nome ?></h1>
        <h4 align="center">Recebido dia <?=$data ?></h4>
        

		<div class="form-cadastro">

        <table width="88%" border="0" align="center" cellpadding="4" cellspacing="2">
         <tr>
           <td width="46%" valign="top">Nome:</td>
           <td width="54%"><?=$nome ?></td>
         </tr>
         <tr>
           <td valign="top">Empresa:</td>
           <td><?=$empresa ?></td>
         </tr>
         <tr>
           <td valign="top">Cidade:</td>
           <td><?=$cidade ?></td>
         </tr>
         <tr>
           <td valign="top">Estado:</td>
           <td><?=$estado ?></td>
         </tr>
         <tr>
           <td valign="top">Endere&ccedil;o:</td>
           <td><?=$endereco ?></td>
         </tr>
         <tr>
           <td valign="top">Telefone para contato:</td>
           <td><?=$telefone ?></td>
         </tr>
         <tr>
           <td valign="top">E-mail:</td>
           <td><a href="mailto:<?=$email ?>"><?=$email ?></a></td>
         </tr>
         <tr>
           <td valign="top">Produtos em interesse:</td>
           <td><?=$produtos ?></td>
         </tr>
         <tr>
           <td valign="top">Coment&aacute;rios:</td>
           <td><?=$comentarios ?></td>
         </tr>
       </table>
       
       <p align="center"><a href="javascript:history.go(-1);">&lsaquo; Voltar</a></p>
       
        </div>
                
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