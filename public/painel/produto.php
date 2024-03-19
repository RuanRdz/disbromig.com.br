<?php /*require_once("php7_mysql_shim.php");*/

# produto.php - Cadastro de novo produto

require("include/func.php");

conectar();

// verifica se usuario esta logado
validar();

$do = $_REQUEST["do"];
$msg = $_REQUEST["msg"];

$consulta_lista = mysqli_query($conn, "SELECT * FROM caracteristicas ORDER BY nome ASC");

$lista_total = mysqli_num_rows($consulta_lista);

if (isset($do) && $do == "novo") {

	// cadastra novo produto no BD

	$titulo = addslashes($_REQUEST["titulo"]);
	$codigo = addslashes($_REQUEST["codigo"]);
	$categoria = addslashes($_REQUEST["categoria"]);
	$descricao = addslashes(nl2br($_REQUEST["desc"]));
	$promo = addslashes($_REQUEST["promo"]);
	$promoValor = addslashes($_REQUEST["promo-valor"]);
	$valor = addslashes($_REQUEST["valor"]);
	$status = addslashes($_REQUEST["status"]);
	$listagem = $_REQUEST["listagem"];
	$novidades = $_REQUEST["novidades"];
	
	for ($l=1;$l<$lista_total+1; $l++) {
	
		$caract_id[$l] = $_REQUEST["c-".$l];
		$caract_valor[$l] = $_REQUEST["valor-".$l];
		
	}

	$foto = $_FILES["foto"];

	$tamanho_foto = $foto["size"];
	
	/*print_r($foto);
	die;*/

	// valida tamanho
	if ($tamanho_foto > 407936) {
		print "<script language='javascript'> alert('A foto n�o pode ser maior que 400 kb'); window.history.go(-1); </script>\n";
		exit;
	}

	if (isset($foto) && $foto["error"] != 4) {
		
		$imgID = uniqid();
			
		// valida tipo (gif, jpg, ou png)
		if ((preg_match("/.gif$/i", $foto["type"])) || (preg_match("/.jpg$/i", $foto["type"])) || (preg_match("/.jpeg$/i", $foto["type"])) || (preg_match("/.png$/i", $foto["type"]))) {
			
			// salva imagem
			move_uploaded_file($foto['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$foto['name']);
			
			// imagem a ser utilizada para redimensionamento
			if (preg_match("/.gif$/i", $foto["type"])) { $img = imagecreatefromgif($_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$foto['name']); }
			else if (preg_match("/.jpg$/i", $foto["type"]) || preg_match("/.jpeg$/i", $foto["type"])) { $img = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$foto['name']); }
			else if (preg_match("/.png$/i", $foto["type"])) { $img = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$foto['name']); }
			
	
			// obtem width e height
			$width = imagesx($img);
			$height = imagesy($img);
			
			// cria thumb
			$porcentagem = 0.25;
			$n_width = $width * $porcentagem;
			$n_height = $height * $porcentagem;
			
			$thumb = imagecreatetruecolor($n_width, $n_height);
			imagecopyresampled($thumb, $img, 0, 0, 0, 0, $n_width, $n_height, $width, $height);
			
			if (preg_match("/.gif$/i", $foto["type"])) { imagegif($thumb,$_SERVER['DOCUMENT_ROOT']."/images/produtos/thumbs/".$imgID.$foto["name"]); }
			else if (preg_match("/.jpg$/i", $foto["type"]) || preg_match("/.jpeg$/i", $foto["type"])) { imagejpeg($thumb,$_SERVER['DOCUMENT_ROOT']."/images/produtos/thumbs/".$imgID.$foto["name"]); }
			else if (preg_match("/.png$/i", $foto["type"])) {
				imagealphablending($thumb, true);
				$color = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
				$t = imagecolortransparent($thumb);
				imagefill($thumb, 0, 0, $color);
				imagesavealpha($thumb, true);
				imagepng($thumb,$_SERVER['DOCUMENT_ROOT']."/images/produtos/thumbs/".$imgID.$foto["name"]);
			}
			
			$end_foto = "/images/produtos/".$imgID.$foto["name"];
			$end_thumb = "/images/produtos/thumbs/".$imgID.$foto["name"];
			
			// altera tamanho da imagem se for muito grande
			# width
			if ($width > 400) {
				
				$imgratio=$width/$height;
           		if ($imgratio>1){
            		$newwidth = 300;
              		$newheight = 300/$imgratio;
           		}else{
                	$newheight = 300;
                 	$newwidth = 300*$imgratio;
           		}
				
				// salva no diretorio
				
				$nova_img = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresampled($nova_img, $img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				
				unlink($_SERVER['DOCUMENT_ROOT'].$end_foto);
				
				if (preg_match("/.gif$/i", $foto["type"])) { imagegif($nova_img,$_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$foto["name"]); }
				else if (preg_match("/.jpg$/i", $foto["type"]) || preg_match("/.jpeg$/i", $foto["type"])) { imagejpeg($nova_img,$_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$foto["name"]); }
				else if (preg_match("/.png$/i", $foto["type"])) {
					imagealphablending($nova_img, false);
					$color = imagecolorallocatealpha($nova_img, 0, 0, 0, 127);
					imagefill($nova_img, 0, 0, $color);
					imagecolortransparent($nova_img);
					imagesavealpha($nova_img, true);
					imagepng($nova_img,$_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$foto["name"]);
				}
				
				$end_foto = "/images/produtos/".$imgID.$foto["name"];				
				
			}
			# height
			else if ($height > 400) {
				
				$imgratio=$width/$height;
           		if ($imgratio>1){
            		$newwidth = 300;
              		$newheight = 300/$imgratio;
           		}else{
                	$newheight = 300;
                 	$newwidth = 300*$imgratio;
           		}
				
				// salva no diretorio
				
				$nova_img = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresampled($nova_img, $img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				
				unlink($_SERVER['DOCUMENT_ROOT'].$end_foto);
				
				if (preg_match("/.gif$/i", $foto["type"])) { imagegif($nova_img,$_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$foto["name"]); }
				else if (preg_match("/.jpg$/i", $foto["type"]) || preg_match("/.jpeg$/i", $foto["type"])) { imagejpeg($nova_img,$_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$foto["name"]); }
				else if (preg_match("/.png$/i", $foto["type"])) {
					imagealphablending($nova_img, false);
					$color = imagecolorallocatealpha($nova_img, 0, 0, 0, 127);
					imagefill($nova_img, 0, 0, $color);
					imagecolortransparent($nova_img);
					imagesavealpha($nova_img, true);
					imagepng($nova_img,$_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$foto["name"]);
				}
				
				$end_foto = "/images/produtos/".$imgID.$foto["name"];				
				
			} # fim - altera img se for muito grande
			
					
			}
			else { die("A imagem forncedia n�o � v�lida, seu formato deve ser JPG, GIF ou PNG.<br><a href='javascript:history.go(-1)'>Clique aqui para voltar</a><br><br>".$foto["error"]); }
	
	}
	
	
	// verifica existencia de campos nulos (necessarios)
	if (is_null($titulo) || $titulo == "") {
		
		header("Location: produto.php?do=aviso&msg=titulo");
		exit;
		
	}
	else if (is_null($codigo) || $codigo == "") {
	
		header("Location: produto.php?do=aviso&msg=codigo");
		exit;
	
	}
	else if (is_null($categoria) || $categoria == "0") {
	
		header("Location: produto.php?do=aviso&msg=categoria");
		exit;
	
	}
	
	$chave_uniq = uniqid();
	
	// salva produto no BD
	$adicionar = mysqli_query($conn, "INSERT INTO produtos (chave, titulo, codigo, valor, categoria, descricao, foto, thumb, promocao, promoValor, listagem, novidades, status) VALUES ('$chave_uniq','$titulo','$codigo','$valor','$categoria','$descricao','$end_foto','$end_thumb','$promo','$promoValor','$listagem','$novidades','$status')") or die ("Erro ao cadastrar novo produto: ".mysqli_error($conn));
	
	if ($adicionar) {
		
		// id do novo produto
		$produto_id_sql = mysqli_query($conn, "SELECT id FROM produtos WHERE chave='".$chave_uniq."'");
		$prod_id = mysql_result($produto_id_sql,0);
		
		# ADICIONA NOVAS CARACTERISTICAS TECNICAS NA TABELA caracteristcas E DEPOIS caract_reg SE HOUVER NOVAS
		
		$count = $_REQUEST["count"];
		
		for ($z=1; $z<$count+1; $z++) {
		
			$nova_caract[$z] = $_REQUEST["nova-caract_".$z];

			if ($nova_caract[$z] == 1) {

				$nova_caract_field[$z] = addslashes($_REQUEST["nova-caract-field_".$z]);
				$nova_caract_valor[$z] = addslashes($_REQUEST["nova-caract-valor_".$z]);
				
				if ($nova_caract_field[$z] != "") {
				
					# Verifica se ja existe uma caracteristica igual a esta no bd
					$consulta_caract = mysqli_query($conn, "SELECT * FROM caracteristicas WHERE nome='".$nova_caract_field[$z]."'") or die (mysqli_error($conn));
					
					if (mysqli_num_rows($consulta_caract) == 0) {
				
						// Salva nova caracteristica na tabela caracteristicas
						$nova_caract_sql = mysqli_query($conn, "INSERT INTO caracteristicas (nome, status) VALUES ('".$nova_caract_field[$z]."','1')") or die (mysqli_error($conn));
						
						// Agora salva na caract_reg
						$nova_caractReg_sql = mysqli_query($conn, "INSERT INTO caract_reg (produto, caract, valor) VALUES ('".$prod_id."','".$nova_caract_field[$z]."','".$nova_caract_valor[$z]."')") or die (mysqli_error($conn));
						
					}
				
				}
				
			}
		
		}
		
		# CONTINUA...

		for ($c=1; $c<count($caract_id)+1; $c++) {
		
			if (!is_null($caract_id[$c])) {
		
				// busca caracteristicas
				$caracteristicas_sql = mysqli_query($conn, "SELECT nome FROM caracteristicas WHERE id_caracteristicas = ".$caract_id[$c]."");
				
				$caract_texto = mysql_result($caracteristicas_sql,0);
				
				// salva caracteristicas
				$caract_sql = mysqli_query($conn, "INSERT INTO caract_reg (produto, caract, valor) VALUES ('".$prod_id."', '".$caract_texto."','".$caract_valor[$c]."')");
			
			}
		
		}
		
		// redireciona usuario
		header("Location: lista_produtos.php");
		
	}



}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="stylesheet" type="text/css" href="css/painel2.css" />

<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>

<title>Disbromig - Painel de Administra&ccedil;&atilde;o</title>

<script language="javascript">

function validar(form) {
	
	with(form) {
	
		if (form.titulo.value == "") { alert('Digite um t�tulo para este produto.'); return false; }
		else if (form.codigo.value == "") { alert('Digite o c�digo do produto.'); return false; }
		else if (form.categoria.selected == "1") { alert('Informe o valor do produto.'); return false; }
		else if (document.getElementById('promo').checked == true && document.getElementById('promo-valor').value == "") { alert("Informe a porcentagem de promo��o para este produto."); return false; }
		else { 
			
			var form = $('novo-produto');
	
			var countTotal = form.getInputs('hidden','nova_caract_count');
			
			document.getElementById('count').value = countTotal.length;
			
			return true;
			
		}
	
	}
	
}

function liberaCampo() {

	var campo = document.getElementById('promo-valor');
	
	if (campo.disabled == true) { campo.disabled = false; }
	else if (campo.disabled == false) { campo.disabled = true; }

}

function ativaNovoCampo(val) {

	var form = $('novo-produto');
	
	var campos = form.getInputs('hidden','nova_caract_count');
	
	var n = 0;
	
	for (n=0; n < campos.length+1; n++) {

		if (document.getElementById('nova-caract_'+ n).checked == true ) {
		
			document.getElementById('nova-caract-field_'+ n).disabled = false;
			document.getElementById('nova-caract-valor_'+ n).disabled = false;
			
		}
		
	}

}

function novoItem() {

	var form = $('novo-produto');
	
	var fields = document.getElementById("fields");
	
	var novas = form.getInputs('hidden','nova_caract_count');
	
	var campo = "<input type='hidden' name='nova_caract_count' value='"+novas.length+"'>&nbsp;<input type='checkbox' name='nova-caract_"+adicionar(novas.length)+"' id='nova-caract_"+adicionar(novas.length)+"' value='1'> <input type='text' name='nova-caract-field_"+adicionar(novas.length)+"' id='nova-caract-field_"+adicionar(novas.length)+"' value='Nome' onclick=\"this.value=''\"> : <input type='text' name='nova-caract-valor_"+adicionar(novas.length)+"' id='nova-caract-valor_"+adicionar(novas.length)+"'><br />";
	
	// finalmente insere novo campo
	new Insertion.After(fields,campo);
		
}

function adicionar(valor) {
	
	var res = valor + 1;
	return res;

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
        
     <div class="conteudo-texto">
     	
        <h1>Cadastrar novo produto</h1>
        <p align="center">Preencha todos os campos marcados por um *</p>
        
        <form name="novo-produto" id="novo-produto" onsubmit="return validar(this);" enctype="multipart/form-data" method="post" action="?do=novo">

		<div class="form-cadastro">
        
        <fieldset id="field-titulo">
        <legend><label for="titulo">T&iacute;tulo</label></legend>
       	<input name="titulo" type="text" id="titulo" title="T&iacute;tulo" size="70" maxlength="255" />
        *
        <? (isset($do) && $do == "aviso" && isset($msg) && $msg == "titulo") ? print("<div class='invalido'>Voc� deve preencher o campo t�tulo.</div>") : ""; ?>
        </fieldset>
        
        <fieldset id="field-cod">
        <legend><label for="codigo">C&oacute;digo</label></legend>
        <input name="codigo" type="text" id="codigo" title="C&oacute;digo" size="70" maxlength="20" />
        *
        <? (isset($do) && $do == "aviso" && isset($msg) && $msg == "codigo") ? print("<div class='invalido'>Voc� deve preencher o campo c�digo.</div>") : ""; ?>
        </fieldset>
        
        <fieldset id="field-valor">
        <legend><label for="valor">Valor:</label></legend>
        R$ <input name="valor" type="text" id="valor" title="Informe o valor deste produto" size="70" />
        </fieldset>
        
        <fieldset id="field-cat">
        <legend><label for="categoria">Categoria:</label></legend>
       	<select name="categoria" id="categoria" title="Selecione uma categoria para este produto">
        	<option selected="selected" value="0">- - -</option>
            <option value="1">Ferramentas</option>
            <option value="2">M&aacute;quinas</option>
        </select>
        *
        <? (isset($do) && $do == "aviso" && isset($msg) && $msg == "categoria") ? print("<div class='invalido'>Voc� deve selecionar uma categoria.</div>") : ""; ?>
        </fieldset>
        
        <fieldset id="field-desc">
        <legend><label for="desc">Descri&ccedil;&atilde;o:</label></legend>
        <textarea name="desc" id="desc" rows="6" cols="55"></textarea>
        </fieldset>
        
        <fieldset id="field-foto">
        <legend><label for="foto">Foto:</label></legend>
        <input name="foto" type="file" id="foto" title="Selecione uma foto para este produto" size="55" />
        </fieldset>
        
        <fieldset id="field-promo">
        <legend>Promo&ccedil;&atilde;o</legend>
        <input type="checkbox" id="promo" name="promo" value="1" onchange="liberaCampo();" /> <label for="promo">Produto em promo&ccedil;&atilde;o:</label>
        <input type="text" id="promo-valor" name="promo-valor" size="10" maxlength="8" disabled="disabled" /> %
        <br />
        <span class="tips-caracteristicas">(N&uacute;mero inteiro e sem o s&iacute;mbolo %, ex.: 15, 40, 60)</span>
        <br /><br />
        <b>Exibir produto na listagem de produtos?</b>
        <br />
        <input type="radio" id="listagem-1" name="listagem" value="1" checked="checked" /> <label for="listagem-1">Sim</label><br />
        <input type="radio" id="listagem-2" name="listagem" value="0" /> <label for="listagem-2">N&atilde;o</label>
        </fieldset>
        
        <fieldset id="field-novidades">
        <legend>Novidades</legend>
        <b>Exibir produto na p&aacute;gina de novidades?</b>
        <br />
        <input type="radio" id="novidades-1" name="novidades" value="1" checked="checked" /> <label for="novidadesm-1">Sim</label><br />
        <input type="radio" id="novidades-2" name="novidades" value="0" /> <label for="novidades-2">N&atilde;o</label>
        </fieldset>
        
        <fieldset id="field-status">
        <legend>Status</legend>
        <input type="radio" name="status" value="1" checked="checked" id="status-on" /> <label for="status-on">Produto Ativado</label><br />
        <input type="radio" name="status" value="0" id="status-off" /> <label for="status-off">Produto Desativado</label>
        </fieldset>
        
        <fieldset id="field-caract">
            <legend>Caracter&iacute;sticas T&eacute;cnicas:</legend>
            
            <div class="lista-caracteristicas">
            
            <br />
            
            <span class="link-cadastrar"><a href="#field-caract" onclick="javascript:novoItem();">Cadastrar novo &iacute;tem</a></span>
            
            <br /><br />
            
            <div id="fields"></div>
            
			<?php
			
				// listagem de caracteristicas tecnicas registradas
				
				$n = 1;
				
				while ($x=mysqli_fetch_array($consulta_lista)) {
				
					$c_id = $x["id_caracteristicas"];
					$c_nome = $x["nome"];
					
					echo "
					<table border='0' cellspacing='2' cellpadding='2'>
					<tr>
					<td width='450'><input type=\"checkbox\" name=\"c-".$n."\" id=\"c-".$n."\" value=".$c_id."> <label for=\"c-".$n."\">".$c_nome."</label>:</td>
					<td width='220'><input type=\"text\" id=\"valor-".$n."\" name=\"valor-".$n."\" size=\"15\"></td>
					</tr>
					</table>";
				
					$n++;
				
				}
				
			?>

            </div>          
            
        </fieldset>
        
         </div>
        
        <p align="center">
        <input type="hidden" name="count" id="count" />
          <input type="submit" value="Cadastrar Produto" />
        </p>
        
        </div>
        
        </form>
        
     </div>
     
 
     
     <br />
     
     <div id="rodape">
     	R. Heliodora, 100 - Vila Darcy Vargas &ndash; Contagem  &ndash; Minas Gerais &ndash; Brasil &ndash; CEP 32372-230
        <br />
        Fone: (031) 3393-2484 - <a href="mailto:disbromig@disbromig.com.br">disbromig@disbromig.com.br</a> - <a href="mailto:brobras@disbromig.com.br">brobras@disbromig.com.br</a>
     </div><p align='right'><span class='foton'>Desenvolvimento: <a href="http://www.foton.com.br" target='_blank'>F&oacute;ton</a></span></p>

</div>
 </div>
</body>
</html>

<?php

desconectar();

?>