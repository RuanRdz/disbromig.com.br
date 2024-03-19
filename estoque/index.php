<?php

# /dl/index.php
# Autentica cliente para download de planilha excel

require("../painel/include/func.php");
 
conectar();

/*if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Disbromig"');
    header('HTTP/1.0 401 Unauthorized');

    $titulo = "Acesso Negado";
	
	$mensagem = "<p><b>A Disbromig alterou a senha de acesso ao estoque. Solicite-nos por favor a nova senha e limpe o cache de seu navegador para ter acesso novamente &agrave; planilha de estoque.</b></p>";

  } else {
  	
	// busca login e senha no bd
	$login_sql = mysql_query("SELECT nome,senha FROM public WHERE id='1'");
	
	while ($l=mysql_fetch_array($login_sql)) {
		$login_bd = $l["nome"];
		$senha_bd = $l["senha"];
	}
	
	if ($_SERVER['PHP_AUTH_USER'] == $login_bd && $_SERVER['PHP_AUTH_PW'] == $senha_bd) {
		
		// avisa o browser para nao utilizar o cache
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		
		//echo "<a href='planilha.xls'>Download de nosso estoque</a><p><a href='".$url."'>&lsaquo; Voltar</a></p>";
*/
		$titulo = "Download de Nosso Estoque Atualizado";
		$mensagem = "<p><strong>Atrav&eacute;s do link abaixo, voc&ecirc; poder&aacute; baixar um planilha excel contendo o estoque atualizado da Disbromig, lan&ccedil;ado diariamente por nossa equipe</strong>.</p>
       <p>&nbsp;</p>
       <p align='center'><a href='planilha.xls'>Download de nosso estoque</a></p>
       <p align='center'>&nbsp;</p>";
		
		/*header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="planilha.xls"');*/
	/*	
	}
	else {
		
		$titulo = "Acesso Negado";
		$mensagem = "<p><b>A Disbromig alterou a senha de acesso ao estoque. Solicite-nos por favor a nova senha e limpe o cache de seu navegador para ter acesso novamente &agrave; planilha de estoque.</b></p>";

	}
  }
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="description" content="A Disbromig Ferramentas Pneumáticas é uma distribuidora autorizada da marca Brobrás, atua distribuindo ferramentas pneumáticas para os setores de Mineração, Siderúrgicas, Fundições, Metalúrgicas, Eletro-Eletrônicas, Eletro-Domésticos, Indústrias Alimentícias, Auto Peças, Indústrias Automobilísticas, Cimenteira e Construção Civil.">
<meta http-equiv="cache-control" content="no-cache" />
<meta name="keywords" content="bomba imersão centrífuga desincrustadores êmbolos esmerilhadeiras ferramentaria furadeira lixadeiras pistolas  marteletes socadores talhas turbinas troles vibradores">

<meta name="author" content="Disbromig Ferramentas Pneumáticas/ www.disbromig.com.br">

<meta name="distribution" content="Global">

<meta name="copyright" content="Disbromig Ferramentas Pneumáticas © 2007 ">

<meta name="rating" content="General">

<meta name="resource-type" content="document">

<meta name="document-rights" content="copywritten, photos, images and systems">

<meta name="document-type" content="Public">

<meta name="document-rating" content="Safe for Kids">

<link rel="stylesheet" type="text/css" href="../css/padrao.css" />

<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>

<title>Download de Nosso Estoque Atualizado - Disbromig</title></head>

<body>

<div id="geral">

    <div id="header">
        <div class="logo-disbromig"><a href="../index.php"><img src="http://www.disbromig.com.br/images/layout/newheader2.jpg" border="0"></a></div>

    </div>
    
  <div id="separador"></div>
  
  <div id="menu">
    
  <div class="menu">
    
       <ul>
        <li><a href="../produtos.php">Produtos<!--[if gte IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->        <ul>
            <li><a href="../produtos.php">Listar Todos</a></li>
            <li><a href="../produtos.php?do=pesquisar&amp;campo=titulo&amp;filtro=qualquer&amp;chave=bomba">Bombas</a></li>
            <li><a href="../produtos.php?do=pesquisar&amp;campo=titulo&amp;filtro=qualquer&amp;chave=desincrustador">Desincrustadores</a></li>
            <li><a href="../produtos.php?do=pesquisar&amp;campo=titulo&amp;filtro=qualquer&amp;chave=esmerilhadeira">Esmerilhadeiras</a></li>
            <li><a href="../produtos.php?do=pesquisar&amp;campo=titulo&amp;filtro=qualquer&amp;chave=furadeira">Furadeiras</a></li>
            <li><a href="../produtos.php?do=pesquisar&amp;campo=titulo&amp;filtro=qualquer&amp;chave=lixadeira">Lixadeiras</a></li>
            <li><a href="../produtos.php?do=pesquisar&amp;campo=titulo&amp;filtro=qualquer&amp;chave=martelete">Marteletes</a></li>
            <li><a href="../produtos.php?do=pesquisar&amp;campo=titulo&amp;filtro=qualquer&amp;chave=motor*motorizado">Motores</a></li>
            <li><a href="../produtos.php?do=pesquisar&amp;campo=titulo&amp;filtro=qualquer&amp;chave=socador">Socadores</a></li>
            <li><a href="../produtos.php?do=pesquisar&amp;campo=titulo&amp;filtro=qualquer&amp;chave=talha">Talhas</a></li>
            <li><a href="../produtos.php?do=pesquisar&amp;campo=titulo&amp;filtro=qualquer&amp;chave=trole">Troles</a></li>
            <li><a href="../produtos.php?do=pesquisar&amp;campo=titulo&amp;filtro=qualquer&amp;chave=vibrador">Vibradores</a></li>
            <li><a href="../produtos.php?do=pesquisar&amp;campo=titulo&amp;filtro=qualquer&amp;chave=acess&oacute;rio">Acessórios</a></li>
        </ul>
        <!--[if lte IE 6]></td></tr></table></a><![endif]-->
	    </li>    
		<li><a href="../promocoes.php">Promo&ccedil;&otilde;es</a></li>
        <li><a href="../novidades.php">Novidades</a></li>
        <li><a href="../informacoes.php">Fale Conosco</a></li>
        <li><a href="../quem_somos.html">Quem somos</a></li>
        <li><a href="../representantes.html">Representantes</a></li>
         <li><a href="../index.php">Principal</a></li>
      </ul>

  </div>
  
  </div>
  
  <br />
  <br />
    
  <div id="conteudo">
         
     <div class="conteudo-texto">
       <h1 class="titulo"><?=$titulo ?></h1>
       <?=$mensagem ?>
       <p align="center"><a href='<?=$url ?>'>P&aacute;gina Inicial</a></p>
       <p>&nbsp;</p>
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
