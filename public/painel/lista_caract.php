<?php /*require_once("php7_mysql_shim.php");*/

# lista_caract.php - Lista caracteristicas tecnicas cadastradas no sistema

require("include/func.php");

conectar();

// verifica se usuario esta logado
validar();

$do = $_REQUEST["do"];
$status = $_REQUEST["status"];
$cid = $_REQUEST["cid"]; // product id

# PAGINACAO
$consulta_total = mysql_query("SELECT COUNT(*) FROM caracteristicas");

$pagina = $_REQUEST['pagina'];

if (!$pagina) {
  $pagina = 1;
}

// numero de produtos por pagina

$num_por_pagina = 25; // padrao


$primeiro_registro = ($pagina*$num_por_pagina) - $num_por_pagina;

list($total_produtos) = mysql_fetch_array($consulta_total);

$total_paginas = $total_produtos/$num_por_pagina;

$prev = $pagina - 1;
$next = $pagina + 1;

if ($pagina > 1) {
		
	$anterior = "<a href=\"lista_caract.php?pagina=$prev\">&lt; Anterior</a>";
			
} else { // senão não há link para a página anterior
		
	$anterior = "Anterior";
			
}

if ($total_paginas > $pagina) {
		
	$proximo = "<a href=\"lista_caract.php?pagina=$next\">Pr&oacute;ximo &gt;</a>";	
			
} else { // senão não há link para a próxima página
		
	$proximo = "Pr&oacute;ximo";
			
}

$total_paginas = ceil($total_paginas);
$n_paginas = "";
		
for ($x=1; $x<=$total_paginas; $x++) {
		
	if ($x==$pagina) { // se estivermos na página corrente, não exibir o link para visualização desta página
	   $n_paginas .= " [$x] ";
		
	} else {
		  	
		$n_paginas .= " <a href=\"lista_caract.php?pagina=$x\">[$x]</a> ";
	}

}
# FIM DA PAGINACAO
# para exibir links (< anterior | 0-9 | proximo >) utilize o codigo: echo $anterior." | ".$n_paginas." | ".$proximo;


# REMOVER
if (isset($do) && $do == "remover") {

	$tipo = $_GET["tipo"];

	// remove grupo de caracteristicas
	if ($tipo == "grupo") {
		
		$lista = $_REQUEST["listar"];

		for ($i=1; $i<$lista+1; $i++) {
	
			$remover[$i] = $_POST["apagar_".$i];

			# apaga caracteristica(s) no banco de dados e todos seus registros (caracteristicas selecionadas)
			$busca_dados = mysql_query("SELECT * FROM caracteristicas WHERE id_caracteristicas='".$remover[$i]."'") or die (mysql_error());
			
			// nenhum id encontrado
			if (mysql_num_rows($busca_dados) == 0) { 
	
				//die ("<b>Erro:</b> Este or&ccedil;amento n&atilde;o existe no banco de dados");
	
			}
			// id encontrado, remover do banco
			else {

				// remove caracteristicas
				$remove_grupo_caract_sql = mysql_query("DELETE FROM caracteristicas WHERE id_caracteristicas = '".$remover[$i]."'") or die (mysql_error());

			}
		
		}

	}
	else {
	
		$apaga_caract = mysql_query("DELETE FROM caracteristicas WHERE id_caracteristicas='".$cid."'") or die (mysql_error());
	
	}
	
	header("Location: lista_caract.php");

}
else if (isset($do) && $do == "alterar" && isset($status)) {
#ALTERAR
	
	// altera status do produto	
	if ($status == "on") {
		$status_on_sql = mysql_query("UPDATE caracteristicas SET status='1' WHERE id_caracteristicas='".$cid."'") or die ("Erro ao alterar status de caracteristica: ".mysql_error());
		header("Location: lista_caract.php");
	}
	else if ($status == "off") {
		$status_on_sql = mysql_query("UPDATE caracteristicas SET status='0' WHERE id_caracteristicas='".$cid."'") or die ("Erro ao alterar status de caracteristica: ".mysql_error());
		header("Location: lista_caract.php");
	}
	
}

$consulta_todos_sql = mysql_query("SELECT * FROM caracteristicas ORDER BY nome ASC LIMIT $primeiro_registro, $num_por_pagina");

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
		
			alert("Você deve digitar um nome de usuário.");
			return false;
		
		}
		else if (novo_senha.value == "") {
		
			alert("Não é possível cadastrar um novo usuário sem uma senha.");
			return false;
		
		}		
		else { return true; }
		
	}

}

function confirma() {

	var ok = window.confirm('Você tem certeza que deseja remover o(s) produto(s) selecionado(s) do sistema?');
	
	if (ok) {
		//document.apagaCaract.submit();
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
	
		count = document.apagaCaract.elements.length;

		for (i=0; i < count; i++) {
		
		    if (document.apagaCaract.elements[i].checked == 1) { document.apagaCaract.elements[i].checked = 0; }
		    else { document.apagaCaract.elements[i].checked = 1; }
	
		}

	}
	else if (document.getElementById('checkAll').checked == false) {
		
		count = document.apagaCaract.elements.length;

		for (i=0; i < count; i++) {
		
		    if (document.apagaCaract.elements[i].checked == 1) { document.apagaCaract.elements[i].checked = 0; }
		    else { document.apagaCaract.elements[i].checked = 1; }
	
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
     	
      <h1>Editar caracter&iacute;sticas</h1>
      
      <br />

      <form name="apagaCaract" id="apagaCaract" onsubmit="return confirma();" method="post" action="?do=remover&tipo=grupo&listar=<? isset($_REQUEST["listar"]) ? print($_REQUEST["listar"]) : print("10"); ?>">
          <table width="60%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr>
          	<td><span class="cad-produto"><a href="caracteristicas.php" title="Clique aqui para cadastrar uma nova caracter&iacute;stica.">[Nova Caracter&iacute;stica]</a></span></td>
          </tr>
          </table>
          <table width="60%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr>
          	<td width="23" bgcolor="#006B8A" class="tbl-usuarios"><input type="checkbox" value="1" name="checkAll" id="checkAll" onclick="selecionaTudo(this.value);" /></td>
            <td width="19" bgcolor="#006B8A" class="tbl-usuarios">ID</td>
            <td width="275" bgcolor="#006B8A" class="tbl-usuarios">Nome</td>
            <td width="79" bgcolor="#006B8A" class="tbl-usuarios">A&ccedil;&atilde;o</td>
          </tr>
          
          <?php
		  	
				$a = 1;
		  
				while ($r=mysql_fetch_array($consulta_todos_sql)) {
				
					($a%2 == 1) ? print("<tr bgcolor='#ffffff' onMouseOver=\"this.bgColor='#FFE5CC'\" onMouseOut=\"this.bgColor='#ffffff'\">") : print("<tr bgcolor='#CFE4E9' onMouseOver=\"this.bgColor='#FFE5CC'\" onMouseOut=\"this.bgColor='#CFE4E9'\">");
				
					echo "<td><input type='checkbox' name='apagar_".$a."' id='apagar_".$a."' value='".$r["id_caracteristicas"]."'></td>
						<td><span class='td-usuarios'>".$r["id_caracteristicas"]."</span></td>
						<td><span class='td-usuarios'><a href='editar_caract.php?cid=".$r["id_caracteristicas"]."'>".$r["nome"]."</a></span></td>
						<td><span class='td-usuarios'><a href=\"editar_caract.php?cid=".$r["id_caracteristicas"]."\"><img src='../images/editar.gif' border='0' title='Editar caracter&iacute;stica' alt='Editar'></a> <a onclick='return confirma();' href=\"?do=remover&cid=".$r["id_caracteristicas"]."\"><img src='../images/deletar.gif' border='0' title='Remover caracter&iacute;stica' alt='Remover'></a> ";
					
					($r["status"] == 1) ? $stat = "<a href='?do=alterar&status=off&cid=".$r["id_caracteristicas"]."'><img src='../images/status_on.gif' border='0' alt='Desativar' title='Clique para desativar'></a>" : $stat = "<a href='?do=alterar&status=on&cid=".$r["id_caracteristicas"]."'><img src='../images/status_off.gif' border='0' alt='Ativar' title='Clique para ativar'></a>";
					
					echo $stat;
					
					echo "</span></td></tr>";

					$a++;
					
				}
				
		   ?>
           
        </table>
        
        <br />

        <div align="center"><input type="submit" name="apagar" id="apagar" style="width:auto; height:auto;" value="Remover Caracter&iacute;stica(s)" /></div>

      </form>
        
        <br />
        
        <div class="navegacao"><? echo $anterior." | ".$n_paginas." | ".$proximo; ?></div>
        
        <table width="80%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr>
            <td width="38%" class="cad-usuarios">
            <div class="legenda">
            <img src="../images/editar.gif" /> Editar caracter&iacute;stica
            <br />
            <img src="../images/deletar.gif" /> Apagar caracter&iacute;stica do sistema
            <br />
            <img src="../images/status_on.gif" /> Caracter&iacute;stica Ativada
            <br />
            <img src="../images/status_off.gif" /> Caracter&iacute;stica Desativada
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