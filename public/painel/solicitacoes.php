<?php 
# solicitacoes.php - lista solicitacoes realizadas pelo formulario no site
 
require("include/func.php");

conectar();

// verifica se usuario esta logado
validar();

$do = $_REQUEST["do"];
$sid = $_REQUEST["sid"]; // id

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
	$num_por_pagina = 10; // padrao
}


$primeiro_registro = ($pagina*$num_por_pagina) - $num_por_pagina;

# paginacao continua mais abaixo

# REMOVER
if (isset($do) && $do == "remover") {

	$tipo = $_GET["tipo"];

	// remove grupo de produtos
	if ($tipo == "grupo") {
		
		$lista = $_REQUEST["listar"];

		for ($i=1; $i<$lista+1; $i++) {
	
			$remover[$i] = $_POST["apagar_".$i];

			# apaga produto(s) no banco de dados e todos seus registros (caracteristicas selecionadas)
			$busca_dados = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE id_solicitacao='".$remover[$i]."'") or die (mysqli_error($conn));
			
			// nenhum id encontrado
			if (mysqli_num_rows($busca_dados) == 0) { 
	
				//die ("<b>Erro:</b> Este or&ccedil;amento n&atilde;o existe no banco de dados");
	
			}
			// id encontrado, remover do banco
			else {

				// remove
				$remove_grupo_solicitacoes_sql = mysqli_query($conn, "DELETE FROM solicitacoes WHERE id_solicitacao = '".$remover[$i]."' LIMIT 1") or die (mysqli_error($conn));

			}
		
		}

	}
	else {
	
		$apaga_sql = mysqli_query($conn, "DELETE FROM solicitacoes WHERE id_solicitacao='".$sid."'") or die ("Erro ao remover solicita&ccedil;&atilde;o do sistema: ".$mysqli_error($conn));
	
	}
	
	header("Location: solicitacoes.php");

}
else if (isset($do) && $do == "pesquisar") {
#PESQUISAR

	$chave = $_REQUEST["chave"]; // palavra-chave
	$campo = $_REQUEST["campo"]; // campo a pesquisar
	$filtro = $_REQUEST["filtro"]; // filtragem
	
	switch($campo) {
	
		case "nome":
			
			if ($filtro == "qualquer") {
				// consulta o banco procurando por nomes que contenham as letras da palavra chave
				$consulta_todos_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE nome LIKE '%".$chave."%' ORDER BY nome ASC LIMIT $primeiro_registro, $num_por_pagina");
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "SELECT COUNT(*) FROM solicitacoes WHERE nome LIKE '%".$chave."%'");
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE nome LIKE '%".$chave."%'");
				$resultados = mysqli_num_rows($resultados_sql);
			}
			else if ($filtro == "exata") {
				// consulta o banco procurando por nomes que correspondem exatamente a palavra chave
				$consulta_todos_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE nome = '".$chave."' ORDER BY nome ASC LIMIT $primeiro_registro, $num_por_pagina");
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "SELECT COUNT(*) FROM solicitacoes WHERE nome = '".$chave."'");
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE nome = '".$chave."'");
				$resultados = mysqli_num_rows($resultados_sql);
			}
			
		break;
		case "email":
			
			if ($filtro == "qualquer") {
				// consulta o banco procurando por titulos que contenham as letras da palavra chave
				$consulta_todos_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE email LIKE '%".$chave."%' ORDER BY email ASC LIMIT $primeiro_registro, $num_por_pagina");
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "SELECT COUNT(*) FROM solicitacoes WHERE email LIKE '%".$chave."%'");
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE email LIKE '%".$chave."%'");
				$resultados = mysqli_num_rows($resultados_sql);
			}
			else if ($filtro == "exata") {
				// consulta o banco procurando por titulos que correspondem exatamente a palavra chave
				$consulta_todos_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE email = '".$chave."' ORDER BY email ASC LIMIT $primeiro_registro, $num_por_pagina");
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "SELECT COUNT(*) FROM solicitacoes WHERE email = '".$chave."'");
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE email = '".$chave."'");
				$resultados = mysqli_num_rows($resultados_sql);
			}
			
		break;
		case "empresa":
			
			if ($filtro == "qualquer") {
				// consulta o banco procurando por titulos que contenham as letras da palavra chave
				$consulta_todos_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE empresa LIKE '%".$chave."%' ORDER BY empresa ASC LIMIT $primeiro_registro, $num_por_pagina");
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "SELECT COUNT(*) FROM solicitacoes WHERE empresa LIKE '%".$chave."%'");
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE empresa LIKE '%".$chave."%'");
				$resultados = mysqli_num_rows($resultados_sql);
			}
			else if ($filtro == "exata") {
				// consulta o banco procurando por titulos que correspondem exatamente a palavra chave
				$consulta_todos_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE empresa = '".$chave."' ORDER BY empresa ASC LIMIT $primeiro_registro, $num_por_pagina");
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "SELECT COUNT(*) FROM solicitacoes WHERE empresa = '".$chave."'");
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE empresa = '".$chave."'");
				$resultados = mysqli_num_rows($resultados_sql);
			}
			
		break;
		case "produtos":
			
			if ($filtro == "qualquer") {
				// consulta o banco procurando por titulos que contenham as letras da palavra chave
				$consulta_todos_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE produtos LIKE '%".$chave."%' ORDER BY produtos ASC LIMIT $primeiro_registro, $num_por_pagina");
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "SELECT COUNT(*) FROM solicitacoes WHERE produtos LIKE '%".$chave."%'");
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE produtos LIKE '%".$chave."%'");
				$resultados = mysqli_num_rows($resultados_sql);
			}
			else if ($filtro == "exata") {
				// consulta o banco procurando por titulos que correspondem exatamente a palavra chave
				$consulta_todos_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE produtos = '".$chave."' ORDER BY produtos ASC LIMIT $primeiro_registro, $num_por_pagina");
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "SELECT COUNT(*) FROM solicitacoes WHERE produtos = '".$chave."'");
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE produtos = '".$chave."'");
				$resultados = mysqli_num_rows($resultados_sql);
			}
			
		break;
		case "tudo":
		
			if ($filtro == "qualquer") {
				// consulta o banco procurando em todas os campos que contenham as letras da palavra chave
				$consulta_todos_sql = mysqli_query($conn, "(SELECT * FROM solicitacoes WHERE id_solicitacao LIKE '%".$chave."%') UNION (SELECT * FROM solicitacoes WHERE email LIKE '%".$chave."%') UNION (SELECT * FROM solicitacoes WHERE nome LIKE '%".$chave."%') UNION (SELECT * FROM solicitacoes WHERE empresa LIKE '%".$chave."%') UNION (SELECT * FROM solicitacoes WHERE produtos LIKE '%".$chave."%') ORDER BY id_solicitacao DESC") or die (mysqli_error($conn));
				
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "(SELECT COUNT(*) FROM solicitacoes WHERE id_solicitacao LIKE '%".$chave."%') UNION (SELECT COUNT(*) FROM solicitacoes WHERE email LIKE '%".$chave."%') UNION (SELECT COUNT(*) FROM solicitacoes WHERE nome LIKE '%".$chave."%') UNION (SELECT COUNT(*) FROM solicitacoes WHERE empresa LIKE '%".$chave."%') UNION (SELECT COUNT(*) FROM solicitacoes WHERE produtos LIKE '%".$chave."%')");
			
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE id_solicitacao LIKE '%".$chave."%' UNION SELECT * FROM solicitacoes WHERE email LIKE '%".$chave."%' UNION SELECT * FROM solicitacoes WHERE nome LIKE '%".$chave."%' UNION SELECT * FROM solicitacoes WHERE empresa LIKE '%".$chave."%' UNION SELECT * FROM solicitacoes WHERE produtos LIKE '%".$chave."%'");
				$resultados = mysqli_num_rows($resultados_sql);
			}
			else if ($filtro == "exata") {
				// consulta o banco procurando por ids que correspondem exatamente a palavra chave
				$consulta_todos_sql = mysqli_query($conn, "(SELECT * FROM solicitacoes WHERE id_solicitacao = '".$chave."') UNION (SELECT * FROM solicitacoes WHERE email = '".$chave."') UNION (SELECT * FROM solicitacoes WHERE nome = '".$chave."') UNION (SELECT * FROM solicitacoes WHERE empresa = '".$chave."') UNION (SELECT * FROM solicitacoes WHERE produtos = '".$chave."') ORDER BY id_solicitacao DESC") or die (mysqli_error($conn));
							
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysqli_query($conn, "(SELECT COUNT(*) FROM solicitacoes WHERE id_solicitacao = '".$chave."') UNION (SELECT COUNT(*) FROM solicitacoes WHERE email = '".$chave."') UNION (SELECT COUNT(*) FROM solicitacoes WHERE nome = '".$chave."') UNION (SELECT COUNT(*) FROM solicitacoes WHERE empresa = '".$chave."') UNION (SELECT COUNT(*) FROM solicitacoes WHERE produtos = '".$chave."')");
				
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysqli_query($conn, "(SELECT * FROM solicitacoes WHERE id_solicitacao = '".$chave."') UNION (SELECT * FROM solicitacoes WHERE email = '".$chave."') UNION (SELECT * FROM solicitacoes WHERE nome = '".$chave."') UNION (SELECT * FROM solicitacoes WHERE empresa = '".$chave."') UNION (SELECT * FROM solicitacoes WHERE produtos = '".$chave."')");
				$resultados = mysqli_num_rows($resultados_sql);
			}
		
		break;
	
	}

}
else if (!isset($do) && $do != "pesquisar") {
	$consulta_total = mysqli_query($conn, "SELECT COUNT(*) FROM solicitacoes");
	$consulta_todos_sql = mysqli_query($conn, "SELECT * FROM solicitacoes ORDER BY id_solicitacao DESC LIMIT $primeiro_registro, $num_por_pagina");
}

list($total_produtos) = mysqli_fetch_array($consulta_total);

$total_paginas = $total_produtos/$num_por_pagina;

$prev = $pagina - 1;
$next = $pagina + 1;

if ($pagina > 1) {
		
	if (isset($do) && $do == "pesquisar") {
			// continua a paginacao para a pesquisa realizada
			$n_paginas .= " <a href=\"solicitacoes.php?do=pesquisar&chave=".$_REQUEST["chave"]."&campo=".$_REQUEST["campo"]."&filtro=".$_REQUEST["filtro"]."&pagina=$x&listar=".$_REQUEST["listar"]."\">[$x]</a> ";
	}
	else if (isset($_REQUEST["listar"]) && is_numeric($_REQUEST["listar"])) {
		$anterior = "<a href=\"solicitacoes.php?pagina=$prev&listar=".$_REQUEST["listar"]."\">&lt; Anterior</a>";
	}
	else { 
		$anterior = "<a href=\"solicitacoes.php?pagina=$prev\">&lt; Anterior</a>";
	}
			
} else { // senão não há link para a página anterior
		
	$anterior = "Anterior";
			
}

if ($total_paginas > $pagina) {
		
	if (isset($do) && $do == "pesquisar") {
			// continua a paginacao para a pesquisa realizada
			$n_paginas .= " <a href=\"solicitacoes.php?do=pesquisar&chave=".$_REQUEST["chave"]."&campo=".$_REQUEST["campo"]."&filtro=".$_REQUEST["filtro"]."&pagina=$x&listar=".$_REQUEST["listar"]."\">[$x]</a> ";
	}
	else if (isset($_REQUEST["listar"]) && is_numeric($_REQUEST["listar"])) {
		$proximo = " <a href=\"solicitacoes.php?pagina=$next&listar=".$_REQUEST["listar"]."\">Pr&oacute;ximo &gt;</a>";
	}
	else {
		$proximo = "<a href=\"solicitacoes.php?pagina=$next\">Pr&oacute;ximo &gt;</a>";
	}
			
			
} else { // senão não há link para a próxima página
		
	$proximo = "Pr&oacute;ximo";
			
}

$total_paginas = ceil($total_paginas);
$n_paginas = "";
		
for ($x=1; $x<=$total_paginas; $x++) {
		
	if ($x==$pagina) { // se estivermos na página corrente, não exibir o link para visualização desta página
	   $n_paginas .= " [$x] ";
		
	} else {
		  	
		if (isset($do) && $do == "pesquisar") {
			// continua a paginacao para a pesquisa realizada
			$n_paginas .= " <a href=\"solicitacoes.php?do=pesquisar&chave=".$_REQUEST["chave"]."&campo=".$_REQUEST["campo"]."&filtro=".$_REQUEST["filtro"]."&pagina=$x&listar=".$_REQUEST["listar"]."\">[$x]</a> ";
		}
		else if (isset($_REQUEST["listar"]) && is_numeric($_REQUEST["listar"])) {
			$n_paginas .= " <a href=\"solicitacoes.php?pagina=$x&listar=".$_REQUEST["listar"]."\">[$x]</a> ";
		}
		else {
			$n_paginas .= " <a href=\"solicitacoes.php?pagina=$x\">[$x]</a> ";
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

function confirma() {

	var ok = window.confirm('Você tem certeza que deseja remover o(s) produto(s) selecionado(s) do sistema?');
	
	if (ok) {
		//document.apagaSolicitacoes.submit();
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
	
		count = document.apagaSolicitacoes.elements.length;

		for (i=0; i < count; i++) {
		
		    if (document.apagaSolicitacoes.elements[i].checked == 1) { document.apagaSolicitacoes.elements[i].checked = 0; }
		    else { document.apagaSolicitacoes.elements[i].checked = 1; }
	
		}

	}
	else if (document.getElementById('checkAll').checked == false) {
		
		count = document.apagaSolicitacoes.elements.length;

		for (i=0; i < count; i++) {
		
		    if (document.apagaSolicitacoes.elements[i].checked == 1) { document.apagaSolicitacoes.elements[i].checked = 0; }
		    else { document.apagaSolicitacoes.elements[i].checked = 1; }
	
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
     	
      <h1>Solicita&ccedil;&otilde;es</h1>
        
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
            <option value="nome" <? (isset($_REQUEST["campo"]) && $_REQUEST["campo"] == "nome") ? print("selected='selected'") : ""; ?> >Cliente</option>
            <option value="empresa" <? (isset($_REQUEST["campo"]) && $_REQUEST["campo"] == "empresa") ? print("selected='selected'") : ""; ?> >Empresa</option>
            <option value="email" <? (isset($_REQUEST["campo"]) && $_REQUEST["campo"] == "email") ? print("selected='selected'") : ""; ?> >E-mail</option>
            <option value="produtos" <? (isset($_REQUEST["campo"]) && $_REQUEST["campo"] == "produtos") ? print("selected='selected'") : ""; ?> >Produtos</option>
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
        
        <table width="80%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr>
            <td width="50%" class="cad-produto"></td>
            <td width="50%" class="cad-produto">
            <div align="right">Listar: 
            	<select name="listar" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
  		  		<option value="solicitacoes.php?listar=25" <? (isset($_REQUEST["listar"]) && $_REQUEST["listar"] == "25") ? print("selected='selected'") : ""; ?>>25</option>
          		<option value="solicitacoes.php?listar=40" <? (isset($_REQUEST["listar"]) && $_REQUEST["listar"] == "40") ? print("selected='selected'") : ""; ?>>40</option>
          		<option value="solicitacoes.php?listar=50" <? (isset($_REQUEST["listar"]) && $_REQUEST["listar"] == "50") ? print("selected='selected'") : ""; ?>>50</option>
          		<option value="solicitacoes.php?listar=100" <? (isset($_REQUEST["listar"]) && $_REQUEST["listar"] == "100" || $_REQUEST["listar"] == "" || !isset($_REQUEST["listar"])) ? print("selected='selected'") : ""; ?>>100</option>
          		<option value="solicitacoes.php?listar=150" <? (isset($_REQUEST["listar"]) && $_REQUEST["listar"] == "150") ? print("selected='selected'") : ""; ?>>150</option>
          		<option value="solicitacoes.php?listar=200" <? (isset($_REQUEST["listar"]) && $_REQUEST["listar"] == "200") ? print("selected='selected'") : ""; ?>>200</option>
          		</select>
          </div>
          </td>
          </tr>
      </table>
      <form name="apagaSolicitacoes" id="apagaSolicitacoes" onsubmit="return confirma();" method="post" action="?do=remover&tipo=grupo&listar=<? isset($_REQUEST["listar"]) ? print($_REQUEST["listar"]) : print("10"); ?>">
          <table width="80%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr>
          	<td width="28" bgcolor="#006B8A" class="tbl-usuarios"><input type="checkbox" value="1" name="checkAll" id="checkAll" onclick="selecionaTudo(this.value);" /></td>
            <td width="35" bgcolor="#006B8A" class="tbl-usuarios">ID</td>
            <td width="161" bgcolor="#006B8A" class="tbl-usuarios">Nome</td>
            <td width="204" bgcolor="#006B8A" class="tbl-usuarios">Empresa</td>
            <td width="156" bgcolor="#006B8A" class="tbl-usuarios">E-mail</td>
            <td width="72" bgcolor="#006B8A" class="tbl-usuarios">Data</td>
            <td width="42" bgcolor="#006B8A" class="tbl-usuarios">A&ccedil;&atilde;o</td>
          </tr>
          
          <?php
		  		
				# numero de solicitacoes encontradas
				if (mysqli_num_rows($consulta_todos_sql) == 0) {
					// nenhum, exibir msg
					print("<p align='center'>Nenhum solicita&ccedil;&atilde;o encontrada.</p>");
				}
				else {
				
					$a = 1;
			  
					while ($r=mysqli_fetch_array($consulta_todos_sql)) {
					
						($a%2 == 1) ? print("<tr bgcolor='#ffffff' onMouseOver=\"this.bgColor='#FFE5CC'\" onMouseOut=\"this.bgColor='#ffffff'\">") : print("<tr bgcolor='#CFE4E9' onMouseOver=\"this.bgColor='#FFE5CC'\" onMouseOut=\"this.bgColor='#CFE4E9'\">");
					
						echo "<td><input type='checkbox' name='apagar_".$a."' id='apagar_".$a."' value='".$r["id_solicitacao"]."'></td>
							<td><span class='td-usuarios'>".$r["id_solicitacao"]."</span></td>
							<td><span class='td-usuarios'><a href='visualizar.php?solicitacao=".$r["id_solicitacao"]."'>".$r["nome"]."</a></span></td>
							<td><span class='td-usuarios'>".$r["empresa"]."</span></td>
							<td><span class='td-usuarios'>".$r["email"]."</span></td>
							<td><span class='td-usuarios'>".$r["data"]."</span></td>
							<td><span class='td-usuarios'><a href='visualizar.php?solicitacao=".$r["id_solicitacao"]."'><img src='../images/visualizar.gif' border='0' alt='Visualizar' title='Visualizar'></a> <a onclick='return confirma();' href=\"?do=remover&sid=".$r["id_solicitacao"]."\"><img src='../images/deletar.gif' border='0' title='Remover produto' alt='Remover'></a></td></tr>";
	
						$a++;
						
					}
				}
				
		   ?>
           
        </table>
        
        <br />

        <div align="center"><input type="submit" name="apagar" id="apagar" style="width:auto; height:auto;" value="Remover Solicita&ccedil;&otilde;es" /></div>

        </form>
        
        <br />
        
        <div class="navegacao"><? echo $anterior." | ".$n_paginas." | ".$proximo; ?></div>
        
        <table width="80%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr>
            <td width="38%" class="cad-usuarios">
            <div class="legenda">
            <img src="../images/deletar.gif" /> Apagar solicita&ccedil;&atilde;o
            <br />
            <img src="../images/visualizar.gif" /> Visualiza solicita&ccedil;&atilde;o
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