<?php /*require_once("php7_mysql_shim.php");*/

# lista_produtos.php - Lista produtos cadastrados

require("include/func.php");

conectar();

// verifica se usuario esta logado
validar();

$do = $_REQUEST["do"];
$status = $_REQUEST["status"];
$pid = $_REQUEST["pid"]; // product id

# PAGINACAO
$pagina = isset($_REQUEST['pagina']) ? $_REQUEST['pagina'] : '';

if (!$pagina) {
  $pagina = 1;
}

// numero de produtos por pagina

if (isset($_REQUEST["listar"]) && is_numeric($_REQUEST["listar"])) {
	$num_por_pagina = $_REQUEST["listar"]; // padronizado pelo usuario
}
else if (!isset($_REQUEST["listar"]) || $_REQUEST["listar"] == "") {
	$num_por_pagina = 100; // padrao
}

$primeiro_registro = ($pagina*$num_por_pagina) - $num_por_pagina;
// paginacao continua apos consultas sql das pesquisas


# REMOVER
if (isset($do) && $do == "remover") {

	$tipo = $_GET["tipo"];

	// remove grupo de produtos
	if ($tipo == "grupo") {
		
		$lista = $_REQUEST["listar"];

		for ($i=1; $i<$lista+1; $i++) {
	
			$remover[$i] = $_POST["apagar_".$i];

			# apaga produto(s) no banco de dados e todos seus registros (caracteristicas selecionadas)
			$busca_dados = mysqli_query($conn, "SELECT * FROM produtos WHERE id='".$remover[$i]."'") or die (mysqli_error($conn));
			
			// nenhum id encontrado
			if (mysqli_num_rows($busca_dados) == 0) { 
	
				//die ("<b>Erro:</b> Este or&ccedil;amento n&atilde;o existe no banco de dados");
	
			}
			// id encontrado, remover do banco
			else {
			
				while($h=mysqli_fetch_array($busca_dados)) {
					$endereco_thumb = $h["thumb"];
					$endereco_img = $h["foto"];
					
					// remove imagens
					if ($endereco_thumb != "") { unlink($_SERVER['DOCUMENT_ROOT'].$endereco_thumb); }
					if ($endereco_img != "") { unlink($_SERVER['DOCUMENT_ROOT'].$endereco_img); }
			
				}

				// remove produto
				$remove_grupo_produtos_sql = mysqli_query($conn, "DELETE FROM produtos WHERE id = '".$remover[$i]."' LIMIT 1") or die (mysqli_error($conn));
				$apaga_caract = mysqli_query($conn, "DELETE FROM caract_reg WHERE produto='".$remover[$i]."'") or die(mysqli_error($conn));

			}
		
		}

	}
	else {
		
		$dados_prod_sql = mysqli_query($conn, "SELECT * FROM produtos WHERE id='".$pid."'");
		
		while ($j=mysqli_fetch_array($dados_prod_sql)) {
			
			$endereco_thumb = $j["thumb"];
			$endereco_img = $j["foto"];
			//($endereco_thumb == "") ? die ('null') : die('erro');
			// remove imagens
			if ($endereco_thumb != "") { unlink($_SERVER['DOCUMENT_ROOT'].$endereco_thumb); }
			if ($endereco_img != "") { unlink($_SERVER['DOCUMENT_ROOT'].$endereco_img); }
			
		}
		
		$apaga_sql = mysqli_query($conn, "DELETE FROM produtos WHERE id='".$pid."'") or die ("Erro ao remover produto do sistema: ".$mysqli_error($conn));
		$apaga_caract = mysqli_query($conn, "DELETE FROM caract_reg WHERE produto='".$pid."'") or die (mysqli_error($conn));
	
	}
	
	header("Location: lista_produtos.php");

}
else if (isset($do) && $do == "alterar" && isset($status)) {
#ALTERAR
	
	// altera status do produto	
	if ($status == "on") {
		$status_on_sql = mysqli_query($conn, "UPDATE produtos SET status='1' WHERE id='".$pid."'") or die ("Erro ao alterar status do produto: ".mysqli_error($conn));
		header("Location: lista_produtos.php");
	}
	else if ($status == "off") {
		$status_on_sql = mysqli_query($conn, "UPDATE produtos SET status='0' WHERE id='".$pid."'") or die ("Erro ao alterar status do produto: ".mysqli_error($conn));
		header("Location: lista_produtos.php");
	}
	
}
else if (isset($do) && $do == "pesquisar") {
#PESQUISAR

	$chave = $_REQUEST["chave"]; // palavra-chave
	$campo = $_REQUEST["campo"]; // campo a pesquisar
	$filtro = $_REQUEST["filtro"]; // filtragem
	
	switch($campo) {
	
		case "titulo":
			
			if ($filtro == "qualquer") {
				// consulta o banco procurando por titulos que contenham as letras da palavra chave
				$consulta_todos_sql = mysqli_query($conn, "SELECT * FROM produtos WHERE titulo LIKE '%".$chave."%' ORDER BY titulo ASC LIMIT $primeiro_registro, $num_por_pagina");
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "SELECT COUNT(*) FROM produtos WHERE titulo LIKE '%".$chave."%'");
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "SELECT * FROM produtos WHERE titulo LIKE '%".$chave."%'");
				$resultados = mysqli_num_rows($resultados_sql);
			}
			else if ($filtro == "exata") {
				// consulta o banco procurando por titulos que correspondem exatamente a palavra chave
				$consulta_todos_sql = mysqli_query($conn, "SELECT * FROM produtos WHERE titulo = '".$chave."' ORDER BY titulo ASC LIMIT $primeiro_registro, $num_por_pagina");
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "SELECT COUNT(*) FROM produtos WHERE titulo = '".$chave."'");
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "SELECT * FROM produtos WHERE titulo = '".$chave."'");
				$resultados = mysqli_num_rows($resultados_sql);
			}
			
		break;
		case "codigo":
			
			if ($filtro == "qualquer") {
				// consulta o banco procurando por titulos que contenham as letras da palavra chave
				$consulta_todos_sql = mysqli_query($conn, "SELECT * FROM produtos WHERE codigo LIKE '%".$chave."%' ORDER BY codigo ASC LIMIT $primeiro_registro, $num_por_pagina");
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "SELECT COUNT(*) FROM produtos WHERE codigo LIKE '%".$chave."%'");
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "SELECT * FROM produtos WHERE codigo LIKE '%".$chave."%'");
				$resultados = mysqli_num_rows($resultados_sql);
			}
			else if ($filtro == "exata") {
				// consulta o banco procurando por titulos que correspondem exatamente a palavra chave
				$consulta_todos_sql = mysqli_query($conn, "SELECT * FROM produtos WHERE codigo = '".$chave."' ORDER BY codigo ASC LIMIT $primeiro_registro, $num_por_pagina");
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "SELECT COUNT(*) FROM produtos WHERE codigo = '".$chave."'");
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "SELECT * FROM produtos WHERE codigo = '".$chave."'");
				$resultados = mysqli_num_rows($resultados_sql);
			}
			
		break;
		case "tudo":
		
			if ($filtro == "qualquer") {
				// consulta o banco procurando em todas os campos que contenham as letras da palavra chave
				$consulta_todos_sql = mysqli_query($conn, "(SELECT * FROM produtos WHERE id LIKE '%".$chave."%') UNION (SELECT * FROM produtos WHERE codigo LIKE '%".$chave."%') UNION (SELECT * FROM produtos WHERE titulo LIKE '%".$chave."%') UNION (SELECT * FROM produtos WHERE valor LIKE '%".$chave."%') UNION (SELECT * FROM produtos WHERE promocao LIKE '%".$chave."%') ORDER BY titulo ASC LIMIT $primeiro_registro, $num_por_pagina") or die (mysqli_error($conn));
				
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "(SELECT COUNT(*) FROM produtos WHERE id LIKE '%".$chave."%') UNION (SELECT COUNT(*) FROM produtos WHERE codigo LIKE '%".$chave."%') UNION (SELECT COUNT(*) FROM produtos WHERE titulo LIKE '%".$chave."%') UNION (SELECT COUNT(*) FROM produtos WHERE valor LIKE '%".$chave."%') UNION (SELECT COUNT(*) FROM produtos WHERE promocao LIKE '%".$chave."%')");
			
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "SELECT * FROM produtos WHERE id LIKE '%".$chave."%' UNION SELECT * FROM produtos WHERE codigo LIKE '%".$chave."%' UNION SELECT * FROM produtos WHERE titulo LIKE '%".$chave."%' UNION SELECT * FROM produtos WHERE valor LIKE '%".$chave."%' UNION SELECT * FROM produtos WHERE promocao LIKE '%".$chave."%'");
				$resultados = mysqli_num_rows($resultados_sql);
			}
			else if ($filtro == "exata") {
				// consulta o banco procurando por ids que correspondem exatamente a palavra chave
				$consulta_todos_sql = mysqli_query($conn, "(SELECT * FROM produtos WHERE id = '".$chave."') UNION (SELECT * FROM produtos WHERE codigo = '".$chave."') UNION (SELECT * FROM produtos WHERE titulo = '".$chave."') UNION (SELECT * FROM produtos WHERE valor = '".$chave."') UNION (SELECT * FROM produtos WHERE promocao = '".$chave."') ORDER BY titulo ASC LIMIT $primeiro_registro, $num_por_pagina") or die (mysqli_error($conn));
							
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "(SELECT COUNT(*) FROM produtos WHERE id = '".$chave."') UNION (SELECT COUNT(*) FROM produtos WHERE codigo = '".$chave."') UNION (SELECT COUNT(*) FROM produtos WHERE titulo = '".$chave."') UNION (SELECT COUNT(*) FROM produtos WHERE valor = '".$chave."') UNION (SELECT COUNT(*) FROM produtos WHERE promocao = '".$chave."')");
				
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "(SELECT * FROM produtos WHERE id = '".$chave."') UNION (SELECT * FROM produtos WHERE codigo = '".$chave."') UNION (SELECT * FROM produtos WHERE titulo = '".$chave."') UNION (SELECT * FROM produtos WHERE valor = '".$chave."') UNION (SELECT * FROM produtos WHERE promocao = '".$chave."')");
				$resultados = mysqli_num_rows($resultados_sql);
			}
		
		break;
	
	}

}
else if (!isset($pesquisar)) {
	$consulta_total = mysqli_query($conn, "SELECT COUNT(*) FROM produtos");
	$consulta_todos_sql = mysqli_query($conn, "SELECT * FROM produtos ORDER BY titulo ASC LIMIT $primeiro_registro, $num_por_pagina") or die (mysqli_error($conn));
}

# continua paginacao
list($total_produtos) = mysqli_fetch_array($consulta_total) or die (mysqli_error($conn));

$total_paginas = $total_produtos/$num_por_pagina;

$prev = $pagina - 1;
$next = $pagina + 1;

if ($pagina > 1) {
		
	if (isset($do) && $do == "pesquisar") {
		// continua a paginacao para a pesquisa realizada
		$anterior = "<a href=\"lista_produtos.php?do=pesquisar&chave=".$_REQUEST["chave"]."&campo=".$_REQUEST["campo"]."&filtro=".$_REQUEST["filtro"]."&pagina=$prev&listar=".$_REQUEST["listar"]."\">&lt; Anterior</a>";
	}
	else if (isset($_REQUEST["listar"]) && is_numeric($_REQUEST["listar"])) {
		$anterior = "<a href=\"lista_produtos.php?pagina=$prev&listar=".$_REQUEST["listar"]."\">&lt; Anterior</a>";
	}
	else { 
		$anterior = "<a href=\"lista_produtos.php?pagina=$prev&listar=".$_REQUEST["listar"]."\">&lt; Anterior</a>";
	}
			
} else { // sen�o n�o h� link para a p�gina anterior
		
	$anterior = "Anterior";
			
}

if ($total_paginas > $pagina) {
		
	if (isset($do) && $do == "pesquisar") {
		// continua a paginacao para a pesquisa realizada
		$proximo = "<a href=\"lista_produtos.php?do=pesquisar&chave=".$_REQUEST["chave"]."&campo=".$_REQUEST["campo"]."&filtro=".$_REQUEST["filtro"]."&pagina=$next&listar=".$_REQUEST["listar"]."\">Pr&oacute;ximo &gt;</a>";
	}
	else if (isset($_REQUEST["listar"]) && is_numeric($_REQUEST["listar"])) {
		$proximo = " <a href=\"lista_produtos.php?pagina=$next&listar=".$_REQUEST["listar"]."\">Pr&oacute;ximo &gt;</a>";
	}
	else {
		$proximo = "<a href=\"lista_produtos.php?pagina=$next&listar=".$_REQUEST["listar"]."\">Pr&oacute;ximo &gt;</a>";
	}
			
			
} else { // sen�o n�o h� link para a pr�xima p�gina
		
	$proximo = "Pr&oacute;ximo";
			
}

$total_paginas = ceil($total_paginas);
$n_paginas = "";
		
for ($x=1; $x<=$total_paginas; $x++) {
		
	if ($x==$pagina) { // se estivermos na p�gina corrente, n�o exibir o link para visualiza��o desta p�gina
	   $n_paginas .= " [$x] ";
		
	} else {
		  	
		if (isset($do) && $do == "pesquisar") {
			// continua a paginacao para a pesquisa realizada
			$n_paginas .= " <a href=\"lista_produtos.php?do=pesquisar&chave=".$_REQUEST["chave"]."&campo=".$_REQUEST["campo"]."&filtro=".$_REQUEST["filtro"]."&pagina=$x&listar=".$_REQUEST["listar"]."\">[$x]</a> ";
		}
		else if (isset($_REQUEST["listar"]) && is_numeric($_REQUEST["listar"])) {
			$n_paginas .= " <a href=\"lista_produtos.php?pagina=$x&listar=".$_REQUEST["listar"]."\">[$x]</a> ";
		}
		else {
			$n_paginas .= " <a href=\"lista_produtos.php?pagina=$x&listar=".$_REQUEST["listar"]."\">[$x]</a> ";
		}

	}

}
# FIM DA PAGINACAO
# para exibir links (< anterior | 0-9 | proximo >) utilize o codigo: echo $anterior." | ".$n_paginas." | ".$proximo;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>

<link rel="stylesheet" type="text/css" href="css/painel2.css" />

<title>Disbromig - Painel de Administra&ccedil;&atilde;o</title>

<script language="javascript">

function validar(form) {
	
	with(form) {
	
		var novo_nome = form.nome;
		var novo_senha = form.senha;
		var novo_email = form.email;
	
		if (novo_nome.value == "") {
		
			alert("Voc� deve digitar um nome de usu�rio.");
			return false;
		
		}
		else if (novo_senha.value == "") {
		
			alert("N�o � poss�vel cadastrar um novo usu�rio sem uma senha.");
			return false;
		
		}		
		else { return true; }
		
	}

}

function confirma() {

	var ok = window.confirm('Voc� tem certeza que deseja remover o(s) produto(s) selecionado(s) do sistema?');
	
	if (ok) {
		//document.apagaProdutos.submit();
		return true;
	}
	else { 
		return false;		
	}

}

// seleciona check box de todos os produtos
function selecionaTudo(val) {

	var n = <?=$num_por_pagina?>;

	if (document.getElementById('checkAll').checked == true) {
	
		count = document.apagaProdutos.elements.length;

		for (i=0; i < count; i++) {
		
		    if (document.apagaProdutos.elements[i].checked == 1) { document.apagaProdutos.elements[i].checked = 0; }
		    else { document.apagaProdutos.elements[i].checked = 1; }
	
		}

	}
	else if (document.getElementById('checkAll').checked == false) {
		
		count = document.apagaProdutos.elements.length;

		for (i=0; i < count; i++) {
		
		    if (document.apagaProdutos.elements[i].checked == 1) { document.apagaProdutos.elements[i].checked = 0; }
		    else { document.apagaProdutos.elements[i].checked = 1; }
	
		}
	}
}

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

</script>

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
     	
      <h1>Produtos cadastrados</h1>
        
    <form name="pesquisar" method="post" action="?do=pesquisar&listar=<?=$_REQUEST["listar"]; ?>">
    <table width="422" border="0" align="center" cellpadding="4" cellspacing="2">
      <tr>
        <td width="119"><div align="right" class="pesquisar-txt">Palavra:</div></td>
        <td width="281">
          <input name="chave" id="chave" type="text" size="20" />
          <input name="submit" type="submit" style="width:auto; height:auto;" value="Pesquisar" />
        </td>
      </tr>
      <tr>
        <td><div align="right" class="pesquisar-txt">Dentro de: </div></td>
        <td>
          <select name="campo">
            <option value="tudo" <?
            if (isset($_REQUEST["campo"]) && $_REQUEST["campo"] == "tudo") {
				print("selected='selected'");
			}
			else if (!isset($_REQUEST["campo"]) || $_REQUEST["a"] != "pesquisar") {
				print("selected='selected'");
			}
			?>>Tudo</option>
            <option value="titulo" <? (isset($_REQUEST["campo"]) && $_REQUEST["campo"] == "titulo") ? print("selected='selected'") : ""; ?> >T&iacute;tulo</option>
            <option value="codigo" <? (isset($_REQUEST["campo"]) && $_REQUEST["campo"] == "codigo") ? print("selected='selected'") : ""; ?> >C&oacute;digo</option>
          </select>
        <span class="pesquisar-txt">Filtro:</span>
          <select name="filtro">
            <option value="qualquer" <?
            if (isset($_REQUEST["filtro"]) && $_REQUEST["filtro"] == "qualquer") {
				print("selected='selected'");
			}
			else if (!isset($_REQUEST["filtro"]) || $_REQUEST["a"] != "pesquisar") {
				print("selected='selected'");
			}
			?>>Todas as letras</option>
            <option value="exata" <? (isset($_REQUEST["filtro"]) && $_REQUEST["filtro"] == "exata") ? print("selected='selected'") : ""; ?>>Palavra exata</option>
          </select>
          </td>
        </tr>
    </table>
  </form>
        
        <table width="90%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr>
            <td width="50%" class="cad-produto"><span class="cad-produto"><a href="produto.php" title="Clique aqui para cadastrar um novo produto.">[Novo Produto]</a></span></td>
            <td width="50%" class="cad-produto">
            <div align="right">Listar: 
            	<select name="listar" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
  		  		<option value="lista_produtos.php?listar=25" <? (isset($_REQUEST["listar"]) && $_REQUEST["listar"] == "25") ? print("selected='selected'") : ""; ?>>25</option>
          		<option value="lista_produtos.php?listar=40" <? (isset($_REQUEST["listar"]) && $_REQUEST["listar"] == "40") ? print("selected='selected'") : ""; ?>>40</option>
          		<option value="lista_produtos.php?listar=50" <? (isset($_REQUEST["listar"]) && $_REQUEST["listar"] == "50") ? print("selected='selected'") : ""; ?>>50</option>
          		<option value="lista_produtos.php?listar=100" <? (isset($_REQUEST["listar"]) && $_REQUEST["listar"] == "100" || $_REQUEST["listar"] == "" || !isset($_REQUEST["listar"])) ? print("selected='selected'") : ""; ?>>100</option>
          		<option value="lista_produtos.php?listar=150" <? (isset($_REQUEST["listar"]) && $_REQUEST["listar"] == "150") ? print("selected='selected'") : ""; ?>>150</option>
          		<option value="lista_produtos.php?listar=200" <? (isset($_REQUEST["listar"]) && $_REQUEST["listar"] == "200") ? print("selected='selected'") : ""; ?>>200</option>
          		</select>
          </div>
          </td>
          </tr>
      </table>
    <form name="apagaProdutos" id="apagaProdutos" onsubmit="return confirma();" method="post" action="?do=remover&tipo=grupo&listar=<? isset($_REQUEST["listar"]) ? print($_REQUEST["listar"]) : print("10"); ?>">
          <table width="90%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr>
          	<td width="29" bgcolor="#006B8A" class="tbl-usuarios"><input type="checkbox" value="1" name="checkAll" id="checkAll" onclick="selecionaTudo(this.value);" /></td>
            <td width="36" bgcolor="#006B8A" class="tbl-usuarios">ID</td>
            <td width="97" bgcolor="#006B8A" class="tbl-usuarios">C&oacute;d.</td>
            <td width="296" bgcolor="#006B8A" class="tbl-usuarios">T&iacute;tulo</td>
            <td width="97" bgcolor="#006B8A" class="tbl-usuarios">Categoria</td>
            <td width="87" bgcolor="#006B8A" class="tbl-usuarios">Valor</td>
            <td width="76" bgcolor="#006B8A" class="tbl-usuarios">Promo&ccedil;&atilde;o</td>
            <td width="77" bgcolor="#006B8A" class="tbl-usuarios">A&ccedil;&atilde;o</td>
          </tr>
          
          <?php
		  	
				# numero de produtos encontrado
				if (mysqli_num_rows($consulta_todos_sql) == 0) {
					// nenhum, exibir msg
					print("<p align='center'>Nenhum produto encontrado.</p>");
				}
				else {
				
					$a = 1;
			  
					while ($r=mysqli_fetch_array($consulta_todos_sql)) {
					
						($a%2 == 1) ? print("<tr bgcolor='#ffffff' onMouseOver=\"this.bgColor='#FFE5CC'\" onMouseOut=\"this.bgColor='#ffffff'\">") : print("<tr bgcolor='#CFE4E9' onMouseOver=\"this.bgColor='#FFE5CC'\" onMouseOut=\"this.bgColor='#CFE4E9'\">");
					
						echo "<td><input type='checkbox' name='apagar_".$a."' id='apagar_".$a."' value='".$r["id"]."'></td>
							<td><span class='td-usuarios'>".$r["id"]."</span></td>
							<td><span class='td-usuarios'><b><a href='editar_produto.php?pid=".$r["id"]."'>".$r["codigo"]."</a></b></span></td>
							<td><span class='td-usuarios'><a href='editar_produto.php?pid=".$r["id"]."'>".$r["titulo"]."</a></span></td>
							<td><span class='td-usuarios'>";
							
						($r["categoria"] == 1) ? $cat = "Ferramentas" : $cat = "M&aacute;quinas";
						
						echo $cat;
						
						echo "</span></td>
							<td><span class='td-usuarios'> R$ ".$r["valor"]."</span></td>
							<td><span class='td-usuarios'>";
							
						($r["promocao"] == 1) ? $promo = $r["promoValor"]." %" : $promo = "-";
						
						echo $promo;
							
						echo "</span></td>
							<td><span class='td-usuarios'><a href=\"editar_produto.php?pid=".$r["id"]."\"><img src='../images/editar.gif' border='0' title='Editar produto' alt='Editar'></a> <a onclick='return confirma();' href=\"?do=remover&pid=".$r["id"]."\"><img src='../images/deletar.gif' border='0' title='Remover produto' alt='Remover'></a> ";
						
						($r["status"] == 1) ? $stat = "<a href='?do=alterar&status=off&pid=".$r["id"]."'><img src='../images/status_on.gif' border='0' alt='Desativar' title='Clique para desativar'></a>" : $stat = "<a href='?do=alterar&status=on&pid=".$r["id"]."'><img src='../images/status_off.gif' border='0' alt='Ativar' title='Clique para ativar'></a>";
						
						echo $stat;
						
						echo "</span></td></tr>";
	
						$a++;
						
					}
				}
				
		   ?>
           
        </table>
        
        <br />

        <div align="center"><input type="submit" name="apagar" id="apagar" style="width:auto; height:auto;" value="Remover Produtos Selecionados" /></div>

        </form>
        
        <br />
        
        <div class="navegacao"><? echo $anterior." | ".$n_paginas." | ".$proximo; ?></div>
        
        <table width="80%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr>
            <td width="38%" class="cad-usuarios">
            <div class="legenda">
            <img src="../images/editar.gif" /> Editar dados do usu&aacute;rio
            <br />
            <img src="../images/deletar.gif" /> Apagar usu&aacute;rio do sistema
            <br />
            <img src="../images/status_on.gif" /> Produto Ativado
            <br />
            <img src="../images/status_off.gif" /> Produto Desativado
            </div>
            </td>
          </tr>
      </table>
        
      <p align="left">&nbsp;</p>
        
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