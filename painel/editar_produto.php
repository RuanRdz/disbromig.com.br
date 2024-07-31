<?php /*require_once("php7_mysql_shim.php");*/

# editar_produto - altera dados de um produto cadastrado

require("include/func.php");

conectar();

// verifica se usuario esta logado
validar();

$do = isset($_REQUEST["do"]) ? $_REQUEST["do"] : '';
$msg = isset($_POST["msg"]) ? $_POST["msg"] : '';

$pid = $_REQUEST["pid"]; // recebe id do produto

$consulta_lista = mysqli_query($conn, "SELECT * FROM caracteristicas ORDER BY nome ASC"); // consulta caracteristicas

$lista_total = mysqli_num_rows($consulta_lista);

$consulta_caract_reg = mysqli_query($conn, "SELECT * FROM caract_reg WHERE produto = '".$pid."'") or die (mysqli_error($conn)); // consulta caracteristicas registradas

$consulta_sql = mysqli_query($conn, "SELECT * FROM produtos WHERE id = '".$pid."'") or die(mysqli_error($conn)); // consulta dados do produto no BD

$total = mysqli_num_rows($consulta_sql); // numero de registros encontrados com aquele ID

($total == 0) ? $mensagem = "<div align='center'>Nenhum produto foi encontrado com esse ID.<br><a href=\"javascript:history.go(-1);\">Voltar e tentar novamente</a></div><br />" : "";

// obtem dados do banco
while ($x=mysqli_fetch_array($consulta_sql)) {

	$chave = $x["chave"];
	$titulo = $x["titulo"];
	$codigo = $x["codigo"];
	$valor = $x["valor"];
	$categoria = $x["categoria"];
	$descricao = $x["descricao"];
	$foto = $x["foto"];
	$thumb = $x["thumb"];
	$promocao = $x["promocao"];
	$promoValor = $x["promoValor"];
	$status = $x["status"];
	$listagem = $x["listagem"];
	$novidades = $x["novidades"];
	
	$descricao = str_replace("<br />","\n",$descricao);
	$descricao = str_replace("<br>","\n",$descricao);
	
}

if (isset($do) && $do == "alterar") {

	$pid = $_REQUEST["pid"];
	$n_titulo = addslashes($_REQUEST["titulo"]);
	$n_codigo = addslashes($_REQUEST["codigo"]);
	$n_valor = addslashes($_REQUEST["n_valor"]);
	$n_categoria = addslashes($_REQUEST["categoria"]);
	$n_desc = addslashes(str_replace("\n","<br />",$_REQUEST["desc"]));
	$n_foto = $_FILES["foto"];
	$n_promo = isset($_REQUEST["promo"]) ? addslashes($_REQUEST["promo"]) : '';
	$n_promoValor = isset($_REQUEST["promo-valor"]) ? addslashe ($_REQUEST["promo-valor"]) : '';
	$n_status = addslashes($_REQUEST["status"]);
	$n_listagem = $_REQUEST["listagem"];
	$remover_foto = isset($_REQUEST["remover_foto"]) ?addslashes($_REQUEST["remover_foto"]) : '';
	$thumb = addslashes($_REQUEST["thumb"]);
	$info_foto = addslashes($_REQUEST["info_foto"]);
	$minha_chave = addslashes($_REQUEST["minha_chave"]);
	$n_novidades = $_REQUEST["novidades"];
	
	(isset($n_promo) && $n_promo == "1") ? $n_promo = "1" : $n_promo = "0";
	
	
	# IMAGEM
	
	if (isset($remover_foto) && $remover_foto == "1") {
	
		// apaga thumb antiga
		unlink($_SERVER['DOCUMENT_ROOT'].$thumb);
		// apaga imagem antiga
		unlink($_SERVER['DOCUMENT_ROOT'].$info_foto);
	
	}
	
	$tamanho_foto = $n_foto["size"];
	
	/*print_r($foto);
	die;*/

	// valida tamanho
	if ($tamanho_foto > 407936) {
		print "<script language='javascript'> alert('A foto não pode ser maior que 400 kb'); window.history.go(-1); </script>\n";
		exit;
	}

	if (isset($n_foto) && $n_foto["error"] != 4) {
			
			// valida tipo (gif, jpg, ou png)
			if ((preg_match("/.gif$/i", $n_foto["type"])) || (preg_match("/.jpg$/i", $n_foto["type"])) || (preg_match("/.jpeg$/i", $n_foto["type"])) || (preg_match("/.png$/i", $n_foto["type"]))) {
			
			$imgID = uniqid();
			
			// salva imagem
			move_uploaded_file($n_foto['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$n_foto['name']);
			
			// imagem a ser utilizada para redimensionamento
			if (preg_match("/.gif$/i", $n_foto["type"])) { $img = imagecreatefromgif($_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$n_foto['name']); }
			else if (preg_match("/.jpg$/i", $n_foto["type"]) || preg_match("/.jpeg$/i", $n_foto["type"])) { $img = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$n_foto['name']); }
			else if (preg_match("/.png$/i", $n_foto["type"])) { $img = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$n_foto['name']); }
			
	
			// obtem width e height
			$width = imagesx($img);
			$height = imagesy($img);
			
			// cria thumb
			$porcentagem = 0.25;
			$n_width = $width * $porcentagem;
			$n_height = $height * $porcentagem;
			
			$n_thumb = imagecreatetruecolor($n_width, $n_height);
			imagecopyresampled($n_thumb, $img, 0, 0, 0, 0, $n_width, $n_height, $width, $height);
			
			if (preg_match("/.gif$/i", $n_foto["type"])) { imagegif($n_thumb,$_SERVER['DOCUMENT_ROOT']."/images/produtos/thumbs/".$imgID.$n_foto["name"]); }
			else if (preg_match("/.jpg$/i", $n_foto["type"]) || preg_match("/.jpeg$/i", $n_foto["type"])) { imagejpeg($n_thumb,$_SERVER['DOCUMENT_ROOT']."/images/produtos/thumbs/".$imgID.$n_foto["name"]); }
			else if (preg_match("/.png$/i", $n_foto["type"])) {
				imagealphablending($n_thumb, true);
				$color = imagecolorallocatealpha($n_thumb, 0, 0, 0, 127);
				$t = imagecolortransparent($n_thumb);
				imagefill($n_thumb, 0, 0, $color);
				imagesavealpha($n_thumb, true);
				imagepng($n_thumb,$_SERVER['DOCUMENT_ROOT']."/images/produtos/thumbs/".$imgID.$n_foto["name"]);
			}
			
			$end_foto = "/images/produtos/".$imgID.$n_foto["name"];
			$end_thumb = "/images/produtos/thumbs/".$imgID.$n_foto["name"];
					
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
				
				if (preg_match("/.gif$/i", $n_foto["type"])) { imagegif($nova_img,$_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$n_foto["name"]); }
				else if (preg_match("/.jpg$/i", $n_foto["type"]) || preg_match("/.jpeg$/i", $n_foto["type"])) { imagejpeg($nova_img,$_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$n_foto["name"]); }
				else if (preg_match("/.png$/i", $n_foto["type"])) {
					imagealphablending($nova_img, false);
					$color = imagecolorallocatealpha($nova_img, 0, 0, 0, 127);
					imagefill($nova_img, 0, 0, $color);
					imagecolortransparent($nova_img);
					imagesavealpha($nova_img, true);
					imagepng($nova_img,$_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$n_foto["name"]);
				}
				
				$end_foto = "/images/produtos/".$imgID.$n_foto["name"];				
				
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
				
				if (preg_match("/.gif$/i", $n_foto["type"])) { imagegif($nova_img,$_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$n_foto["name"]); }
				else if (preg_match("/.jpg$/i", $n_foto["type"]) || preg_match("/.jpeg$/i", $n_foto["type"])) { imagejpeg($nova_img,$_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$n_foto["name"]); }
				else if (preg_match("/.png$/i", $n_foto["type"])) {
					imagealphablending($nova_img, true);
					$color = imagecolorallocatealpha($nova_img, 0, 0, 0, 127);
					$t = imagecolortransparent($nova_img);
					imagefill($nova_img, 0, 0, $color);
					imagesavealpha($nova_img, true);
					imagepng($nova_img,$_SERVER['DOCUMENT_ROOT']."/images/produtos/".$imgID.$n_foto["name"]);
				}
				
				$end_foto = "/images/produtos/".$imgID.$n_foto["name"];				
				
			} # fim - altera img se for muito grande
			
			}
			else { die("A imagem forncedia não é válida, seu formato deve ser JPG, GIF ou PNG.<br><a href='javascript:history.go(-1)'>Clique aqui para voltar</a>"); }
	
	}
	
	# EOF IMAGEM
	
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
	
	// altera produto
	$altera_bd = "UPDATE produtos SET titulo='".$n_titulo."', codigo='".$n_codigo."', valor='".$n_valor."', categoria='".$n_categoria."', descricao='".$n_desc."'";

	if (isset($n_foto) && $n_foto["error"] != 4) { $altera_bd .= ", foto='".$end_foto."', thumb='".$end_thumb."'"; }

	$altera_bd .= ", promocao='".$n_promo."', promoValor='".$n_promoValor."', listagem='".$n_listagem."', novidades='".$n_novidades."', status='".$n_status."' WHERE id='".$pid."'";

	//die($altera_bd); // DEBUG
	
	// executa sql
	$salvar = mysqli_query($conn, $altera_bd) or die (mysqli_error($conn));
	
	if ($salvar) {

		// agora salva as caracteristicas tecnicas
		
		// antes de prosseguir apaga todos os registros de caracteristicas tenicas para esse produto na tabela caract_reg
		$clean_caract_reg = mysqli_query($conn, "DELETE FROM caract_reg WHERE produto='".$pid."'") or die (mysqli_error($conn));
		
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
						$nova_caractReg_sql = mysqli_query($conn, "INSERT INTO caract_reg (produto, caract, valor) VALUES ('".$pid."','".$nova_caract_field[$z]."','".$nova_caract_valor[$z]."')") or die (mysqli_error($conn));
						
						
					}
				
				}
				
			}
		
		}
		
		$caract_id = [];
		$caract_valor = [];
		
		// continua...
		for ($l=1;$l<$lista_total+1; $l++) {
            if(isset($_POST["c-".$l])) {
			    $caract_id[$l] = $_POST["c-".$l];
			    $caract_valor[$l] = $_POST["valor-".$l];
            }
		}
		
        foreach($caract_id as $c => $valor) {
			if (isset($caract_id[$c]) && !is_null($caract_id[$c])) {
		
				// busca caracteristicas
				$caracteristicas_sql = mysqli_query($conn, "SELECT nome FROM caracteristicas WHERE id_caracteristicas = ".$caract_id[$c]."");
				
				$caract_texto = mysqli_fetch_assoc($caracteristicas_sql);

				if ($caract_valor[$c] != "" || !is_null($caract_valor[$c]) || !empty($caract_valor[$c])) {
					// salva caracteristicas
					$caract_sql = mysqli_query($conn, "INSERT INTO caract_reg (produto, caract, valor) VALUES ('".$pid."', '".$caract_texto['nome']."','".$caract_valor[$c]."')");
				}
			
			}
		
		}
		
	} # fim caracteristicas tecnicas
	//print_r($caract_valor);
	
	// redireciona
	header("Location: lista_produtos.php");

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
	
		if (form.titulo.value == "") { alert('Digite um título para este produto.'); return false; }
		else if (form.codigo.value == "") { alert('Digite o código do produto.'); return false; }
		else if (form.categoria.selected == "1") { alert('Informe o valor do produto.'); return false; }
		else if (document.getElementById('promo').checked == true && document.getElementById('promo-valor').value == "") { alert("Informe a porcentagem de promoção para este produto."); return false; }
		else { 
			
			var form = $('alteraDados');
	
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

function novoItem() {

	var form = $('alteraDados');
	
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
        
     <div class="conteudo-texto" id="ctexto">
     	
        <h1>Alterar dados de <u><?=$titulo ?></u></h1>
        
        <?=$mensagem ?>
        
        <form name="alteraDados" id="alteraDados" onsubmit="return validar(this);" enctype="multipart/form-data" method="post" action="editar_produto.php?do=alterar&pid=<?=$_REQUEST["pid"] ?>">
        
		<div class="form-cadastro">
        
        <fieldset id="field-titulo">
        <legend><label for="titulo">T&iacute;tulo</label></legend>
       	<input name="titulo" type="text" id="titulo" title="T&iacute;tulo" size="70" maxlength="255" value="<?=$titulo ?>" />
        *
        <? (isset($do) && $do == "aviso" && isset($msg) && $msg == "titulo") ? print("<div class='invalido'>Você deve preencher o campo título.</div>") : ""; ?>
        </fieldset>
        
        <fieldset id="field-cod">
        <legend><label for="codigo">C&oacute;digo</label></legend>
        <input name="codigo" type="text" id="codigo" title="C&oacute;digo" size="70" maxlength="20" value="<?=$codigo ?>" />
        *
        <? (isset($do) && $do == "aviso" && isset($msg) && $msg == "codigo") ? print("<div class='invalido'>Você deve preencher o campo código.</div>") : ""; ?>
        </fieldset>
        
        <fieldset id="field-valor">
        <legend><label for="valor">Valor:</label></legend>
        R$ <input name="n_valor" type="text" id="valor" title="Informe o valor deste produto" size="70" value="<?=$valor ?>" />
        </fieldset>
        
        <fieldset id="field-cat">
        <legend><label for="categoria">Categoria:</label></legend>
       	<select name="categoria" id="categoria" title="Selecione uma categoria para este produto">
            <option value="1" <? ($categoria == 1) ? print("selected='selected'") : ""; ?>>Ferramentas</option>
            <option value="2" <? ($categoria == 2) ? print("selected='selected'") : ""; ?>>M&aacute;quinas</option>
        </select>
        *
        <? (isset($do) && $do == "aviso" && isset($msg) && $msg == "categoria") ? print("<div class='invalido'>Você deve selecionar uma categoria.</div>") : ""; ?>
        </fieldset>
        
        <fieldset id="field-desc">
        <legend><label for="desc">Descri&ccedil;&atilde;o:</label></legend>
        <textarea name="desc" id="desc" rows="6" cols="55"><?=$descricao ?></textarea>
        </fieldset>
        
        <fieldset id="field-foto">
        <legend>
        <? (isset($thumb) && $thumb != "") ? print("<label for=\"foto\">Alterar Foto:</label></legend><img src='".$url.$thumb."'><br><input type='checkbox' name='remover_foto' value='1' id='remover_foto'> <label for='remover_foto'>Remover essa foto</label><br /><br />Alterar foto:") : print("<label for=\"foto\">Adicionar Foto:</label></legend>"); ?>
        <input name="foto" type="file" id="foto" title="Selecione uma foto para este produto" size="55" />
        <input type="hidden" name="thumb" value="<?=$thumb ?>" />
        <input type="hidden" name="info_foto" value="<?=$foto ?>" />
        </fieldset>
        
        <fieldset id="field-promo">
        <legend>Promo&ccedil;&atilde;o</legend>
        <input type="checkbox" id="promo" name="promo" value="1" <? (isset($promocao) && $promocao == "1") ? print("checked='checked'") : print("onchange=\"liberaCampo();\""); ?> /> <label for="promo">Produto em promo&ccedil;&atilde;o:</label>
        <input type="text" id="promo-valor" name="promo-valor" size="10" maxlength="8" <? (isset($promocao) && $promocao == "1") ? print("value='".$promoValor."'") : print("disabled=\"disabled\""); ?> /> %
        <br /><br />
        <b>Exibir produto na listagem de produtos?</b>
        <br />
        <input type="radio" id="listagem-1" name="listagem" value="1" <? (isset($listagem) && $listagem == 1) ? print("checked='checked'") : ""; ?> /> <label for="listagem-1">Sim</label><br />
        <input type="radio" id="listagem-2" name="listagem" value="0" <? (isset($listagem) && $listagem == 0) ? print("checked='checked'") : ""; ?> /> <label for="listagem-2">N&atilde;o</label>
        </fieldset>
        
        <fieldset id="field-novidades">
        <legend>Novidades</legend>
        <b>Exibir produto na p&aacute;gina de novidades?</b>
        <br />
        <input type="radio" id="novidades-1" name="novidades" value="1" <? (isset($novidades) && $novidades == 1) ? print("checked='checked'") : ""; ?> /> <label for="novidadesm-1">Sim</label><br />
        <input type="radio" id="novidades-2" name="novidades" value="0" <? (isset($novidades) && $novidades == 0) ? print("checked='checked'") : ""; ?> /> <label for="novidades-2">N&atilde;o</label>
        </fieldset>
        
        <fieldset id="field-status">
        <legend>Status</legend>
        <input type="radio" name="status" value="1" <? (isset($status) && $status == "1") ? print("checked=\"checked\"") : ""; ?> id="status-on" /> <label for="status-on">Produto Ativado</label><br />
        <input type="radio" name="status" value="0" <? (isset($status) && $status == "0") ? print("checked=\"checked\"") : ""; ?> id="status-off" /> <label for="status-off">Produto Desativado</label>
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
				
				$w = 1;
				
				while ($s=mysqli_fetch_array($consulta_caract_reg)) {
				
					//if (is_null($s["caract"])) { $caract[$w] = "0"; $valores[$w] = "0"; }
					//else {
						$caract[$w] = $s["caract"]; 
						$valores[$w] = $s["valor"];
					//}
					
				$w++;
				
				}

				$n = 1;

				while ($x=mysqli_fetch_array($consulta_lista)) {
				
					$c_id = $x["id_caracteristicas"];
					$c_nome = $x["nome"];
					
					echo "
					<table border='0' cellspacing='2' cellpadding='2'>
					<tr>
					<td width='450'><input type=\"checkbox\" name=\"c-".$n."\" id=\"c-".$n."\" value=".$c_id."";

					if (!empty($caract)) {

						if (in_array($c_nome,$caract)) {
						
							$key = array_search($c_nome, $caract);
						
							echo " checked='checked'> <label for=\"c-".$n."\">".$c_nome."</label>:</td>
							<td width='220'><input type=\"text\" id=\"valor-".$n."\" name=\"valor-".$n."\" size=\"15\" value=\"".$valores[$key]."\"";
						}
						else {
							echo "> <label for=\"c-".$n."\">".$c_nome."</label>:</td>
							<td width='220'><input type=\"text\" id=\"valor-".$n."\" name=\"valor-".$n."\" size=\"15\"";
						}
					}
					else {
						echo "> <label for=\"c-".$n."\">".$c_nome."</label>:</td>
						<td width='120'><input type=\"text\" id=\"valor-".$n."\" name=\"valor-".$n."\" size=\"15\"";
					}
					
					echo "></td>
					</tr>
					</table>";
				
					$n++;
				
				}
				
			?>
            
            </div>
            
        </fieldset>
        
        </div>
       
        <p align="center">
          <input type="hidden" name="minha_chave" value="<?=$chave ?>" />
          <input type="hidden" name="count" id="count" />
          <input type="submit" id="go" value="Atualizar produto" title="Salvar altera&ccedil;&otilde;es" />
        </p>
        
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