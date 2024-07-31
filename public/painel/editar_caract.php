<?php 

# editar_caract - altera dados de uma caracteristica cadastrada

require("include/func.php");

conectar();

// verifica se usuario esta logado
validar();

$do = $_REQUEST["do"];
$msg = $_REQUEST["msg"];

$cid = $_REQUEST["cid"]; // recebe id

$consulta_sql = mysqli_query($conn, "SELECT * FROM caracteristicas WHERE id_caracteristicas = '".$cid."'") or die(mysqli_error($conn)); // consulta dados do produto no BD

$total = mysqli_num_rows($consulta_sql); // numero de registros encontrados com aquele ID

($total == 0) ? $mensagem = "<div align='center'>Nenhuma caracter&iacute;stica foi encontrado com esse ID.<br><a href=\"javascript:history.go(-1);\">Voltar e tentar novamente</a></div><br />" : "";

// obtem dados do banco
while ($x=mysqli_fetch_array($consulta_sql)) {

	$nome = $x["nome"];
	$status = $x["status"];
	
}

if (isset($do) && $do == "alterar") {

	$n_nome = addslashes($_REQUEST["n_nome"]);
	$n_status = $_REQUEST["n_status"];
	$caract_id = $_REQUEST["caract_id"];
	
	// verifica existencia de campos nulos (necessarios)
	if (is_null($n_nome) || $n_nome == "") {
		
		header("Location: editar_caract.php?do=aviso&msg=nome");
		exit;
		
	}
	
	// altera nome do usuario
	$altera_bd = "UPDATE caracteristicas SET nome='".$n_nome."', status='".$n_status."' WHERE id_caracteristicas='".$caract_id."'";

	//die($altera_bd); // DEBUG
	
	// executa sql
	$salvar = mysqli_query($conn, $altera_bd) or die (mysqli_error($conn));
		
	// redireciona
	header("Location: lista_caract.php");

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
	
		if (form.n_nome.value == "") { alert('Entre com o nome'); return false; }
		else { return true; }
	
	}
	
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
     	
        <h1>Alterar dados de <u><?=$nome ?></u></h1>
        
        <?=$mensagem ?>
        
        <form name="alteraDados" id="alteraDados" onsubmit="return validar(this);" method="post" action="editar_caract.php?do=alterar&cid=<?=$_REQUEST["cid"] ?>">
        
		<div class="form-cadastro">
        
        <fieldset id="field-nome" style="width:540px; margin:0 auto;">
        <legend><label for="n_nome">Nome</label></legend>
       	<input name="n_nome" type="text" id="n_nome" title="Nome" size="35" maxlength="255" value="<?=$nome ?>" />
        *
        <? (isset($do) && $do == "aviso" && isset($msg) && $msg == "nome") ? print("<div class='invalido'>Você deve preencher o campo nome.</div>") : ""; ?>
        </fieldset>
        
        <fieldset id="field-status" style="width:540px; margin:0 auto;">
        <legend>Status</legend>
        <input type="radio" name="n_status" value="1" id="status_on" <? ($status == 1) ? print("checked='checked'") : ""; ?> /> <label for="status_on">Ativado</label>
        <input type="radio" name="n_status" value="0" id="status_off" <? ($status == 0) ? print("checked='checked'") : ""; ?> /> <label for="status_off">Desativado</label>
        </fieldset>
        
        </div>
       
        <p align="center">
          <input type="hidden" name="caract_id" value="<?=$cid ?>" />
          <input type="submit" id="go" value="Atualizar caracter&iacute;stica" title="Salvar altera&ccedil;&otilde;es" />
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