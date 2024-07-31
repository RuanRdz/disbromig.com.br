<?php require_once("php7_mysql_shim.php");

# produto.php - Cadastro de novo produto

require("include/func.php");

conectar();

// verifica se usuario esta logado
validar();

$do = $_REQUEST["do"];

if (isset($do) && $do == "novo") {

	// recebe dados
	for ($i=1; $i<6; $i++) {
	
		$check[$i] = $_REQUEST["c".$i];
		
		// executa apenas os selecionados
		if ($check[$i] == 1) {
		
			$nome[$i] = addslashes(utf8_decode($_REQUEST["c".$i."_nome"]));
			$status[$i] = $_REQUEST["c".$i."_status"];
			
			if ($nome[$i] != "" && !is_null($nome[$i])) {
			
				// antes verifica se ja existe
				$existencia_sql = mysql_query("SELECT nome FROM caracteristicas WHERE nome='".$nome[$i]."'");
				
				if (mysql_num_rows($existencia_sql) == 0) {
					// executa query
					$salva = mysql_query("INSERT INTO caracteristicas (nome, status) VALUES ('".$nome[$i]."','".$status[$i]."')") or die ("Erro ao salvar itens no BD: ".mysql_error());
				}
				
			}
		
		}
		
	}

}

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

		for (i=1; i<6; i++) {

			if (document.getElementById('c'+i).checked == true && document.getElementById('c'+i+'_nome').value == "") { alert("Nenhum campo selecionado deve ficar em branco."); return false; }
			
		}
		
		return true;
	
	}
	
}

function ativa(that) {
	
	if (document.getElementById(that).checked == true) {
		document.getElementById(that+'_nome').disabled = false;
		document.getElementById(that+'_status').disabled = false;
	}
	else if (document.getElementById(that).checked == false) {
		document.getElementById(that+'_nome').disabled = true;
		document.getElementById(that+'_status').disabled = true;
	}

}

function salvar() {

	if (document.getElementById('c1').checked == true) { var c1 = document.getElementById('c1').value; }
	else { var c1 = 0; }
	
	if (document.getElementById('c2').checked == true) { var c2 = document.getElementById('c2').value; }
	else { var c2 = 0; }
	
	if (document.getElementById('c3').checked == true) { var c3 = document.getElementById('c3').value; }
	else { var c3 = 0; }
	
	if (document.getElementById('c4').checked == true) { var c4 = document.getElementById('c4').value; }
	else { var c4 = 0; }
	
	if (document.getElementById('c5').checked == true) { var c5 = document.getElementById('c5').value; }
	else { var c5 = 0; }
	
	
	var c1_nome = document.getElementById('c1_nome').value;
	var c2_nome = document.getElementById('c2_nome').value;
	var c3_nome = document.getElementById('c3_nome').value;
	var c4_nome = document.getElementById('c4_nome').value;
	var c5_nome = document.getElementById('c5_nome').value;
	
	var c1_status  = document.getElementById('c1_status').value;
	var c2_status  = document.getElementById('c2_status').value;
	var c3_status  = document.getElementById('c3_status').value;
	var c4_status  = document.getElementById('c4_status').value;
	var c5_status  = document.getElementById('c5_status').value;
	
	var div = document.getElementById('ctexto');

	new Ajax.Request('caracteristicas.php?do=novo', {
		
		method:'post',
		parameters: {c1: c1, c2: c2, c3: c3, c4: c4, c5: c5, c1_nome: c1_nome, c2_nome: c2_nome, c3_nome: c3_nome, c4_nome: c4_nome, c5_nome: c5_nome, c1_status: c1_status, c2_status: c2_status, c3_status: c3_status, c4_status: c4_status, c5_status: c5_status},
		onLoading: div.innerHTML = '<div class="ajax"><img src="../images/carregando.gif"><br>Salvando dados, por favor aguarde...</div>',
		onSuccess: function(transport) {
			
			if (200 == transport.status) {
				document.location = 'lista_caract.php';
				//div.innerHTML = transport.responseText; // DEBUG
			}
			else {
				div.innerHTML = "ERRO: " + transport.responseText;
			}
		}
	});

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
     	
        <h1>Nova caracter&iacute;stica t&eacute;cnica</h1>
        <p align="center">Selecione o checkbox para adicionar um novo item</p>
        
        <form name="novo-item" id="novo-item" onsubmit="return validar(this);" method="post" action="javascript:salvar();">

		<div class="form-cadastro">
        
        <fieldset id="field-caract" style="width:540px; margin:0 auto;">
            <legend>Caracter&iacute;sticas T&eacute;cnicas:</legend>
            
            <p>
            Adicionar o(s) seguinte(s) &iacute;tem(ns):
            </p>
            
            <input type="checkbox" name="c1" id="c1" onchange="ativa(this.id);" title="Clique aqui para ativar os campos ao lado" value="1" /> <input type="text" name="c1_nome" id="c1_nome" size="30" title="Insira aqui um nome para essa caracter&iacute;stica t&eacute;cnica" disabled="disabled" /> <select name="c1_status" disabled="disabled" id="c1_status"><option value="1" selected="selected">Ativado</option><option value="0">Desativado</option></select>
            <br />
            <input type="checkbox" name="c2" id="c2" onchange="ativa(this.id);" title="Clique aqui para ativar os campos ao lado" value="1" /> <input type="text" disa name="c2_nome" id="c2_nome" size="30" title="Insira aqui um nome para essa caracter&iacute;stica t&eacute;cnica" disabled="disabled" /> <select name="c2_status" disabled="disabled" id="c2_status"><option value="1" selected="selected">Ativado</option><option value="0">Desativado</option></select>
            <br />
            <input type="checkbox" name="c3" id="c3" onchange="ativa(this.id);" title="Clique aqui para ativar os campos ao lado" value="1" /> <input type="text" name="c3_nome" id="c3_nome" size="30" title="Insira aqui um nome para essa caracter&iacute;stica t&eacute;cnica" disabled="disabled" /> <select name="c3_status" disabled="disabled" id="c3_status"><option value="1" selected="selected">Ativado</option><option value="0">Desativado</option></select>
            <br />
            <input type="checkbox" name="c4" id="c4" onchange="ativa(this.id);" title="Clique aqui para ativar os campos ao lado" value="1" /> <input type="text" name="c4_nome" id="c4_nome" size="30" title="Insira aqui um nome para essa caracter&iacute;stica t&eacute;cnica" disabled="disabled" /> <select name="c4_status" disabled="disabled" id="c4_status"><option value="1" selected="selected">Ativado</option><option value="0">Desativado</option></select>
            <br />
            <input type="checkbox" name="c5" id="c5" onchange="ativa(this.id);" title="Clique aqui para ativar os campos ao lado" value="1" /> <input type="text" name="c5_nome" id="c5_nome" size="30" title="Insira aqui um nome para essa caracter&iacute;stica t&eacute;cnica" disabled="disabled" /> <select name="c5_status" disabled="disabled" id="c5_status"><option value="1" selected="selected">Ativado</option><option value="0">Desativado</option></select>
            <br />
            <span class="tips-caracteristicas"><i>(Ex.: Peso)</i></span>
            
        </fieldset>
        
        <p align="center">
          <input type="submit" value="Cadastrar &iacute;tem(ns)" title="Clique aqui para salvar os &iacute;tem(ns) acima no banco de dados" />
        </p>
        
        </div>
        
        </form>
        
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