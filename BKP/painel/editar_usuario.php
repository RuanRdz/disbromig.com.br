<?php require_once("php7_mysql_shim.php");

# editar_usuario - altera dados de usuario cadastrado no sistema

require("include/func.php");

conectar();

// verifica se usuario esta logado
validar();

$do = $_REQUEST["do"];
$msg = $_REQUEST["msg"];

$uid = $_REQUEST["uid"]; // recebe id do usuario

$consulta_sql = mysql_query("SELECT * FROM usuarios WHERE id = '".$uid."'") or die(mysql_error()); // consulta dados do usuario no BD

$total = mysql_num_rows($consulta_sql); // numero de registros encontrados com aquele ID

($total == 0) ? $mensagem = "<div align='center'>Nenhum usu&aacute;rio foi encontrado com esse ID.<br><a href=\"javascript:history.go(-1);\">Voltar e tentar novamente</a></div><br />" : "";

// obtem nome e email
while ($x=mysql_fetch_array($consulta_sql)) {

	$unome = $x["nome"];
	$uemail = $x["email"];

}

if (isset($do) && $do == "alterar") {

	$n_nome = addslashes($_REQUEST["n_nome"]);
	$n_senha = addslashes($_REQUEST["n_senha"]);
	$n_email = addslashes($_REQUEST["n_email"]);
	
	$validacao_sql = mysql_query("SELECT nome, email FROM usuarios") or die (mysql_error());
	
	while ($v=mysql_fetch_array($validacao_sql)) {
	
		if ($n_nome == $v["nome"] && $v["nome"] != $unome) {
			header("Location: editar_usuario.php?do=aviso&msg=nome&uid=".$_REQUEST["uid"]."");
			exit;
		}
		else if ($n_email == $v["email"] && $v["email"] != $uemail) {
			header("Location: editar_usuario.php?do=aviso&msg=email&uid=".$_REQUEST["uid"]."");
			exit;
		}
	
	}
	
	// altera nome do usuario
	$altera_bd = "UPDATE usuarios SET chave='".$minha_chave."'";
	(!is_null($n_nome)) ? $altera_bd .= ", nome='".$n_nome."'" : "";
	(!is_null($n_email)) ? $altera_bd .= ", email='".$n_email."'" : "";
	(!is_null($n_senha) && $n_senha != "") ? $altera_bd .= ", senha='".md5($n_senha)."'" : "";
	$altera_bd .= " WHERE id='".$uid."'";
	
	// executa sql
	$salvar = mysql_query($altera_bd) or die (mysql_error());
	
	// redireciona
	header("Location: usuarios.php");

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
	
		var nome = document.getElementById('n_nome');
		var senha = document.getElementById('n_senha');
		var email = document.getElementById('n_email');
	
		if (nome.value == "") {
		
			alert("Você deve digitar um novo nome de usuário.");
			return false;
		
		}
		else if (email.value == "") {
		
			alert("Você deve digitar um endereço de e-mail válido.");
			return false;
		
		}
		else if (nome.value == "<?=$unome; ?>" && email.value == "<?=$uemail; ?>" && senha.value == "") {
		
			var ok = window.confirm("Parece que nenhum dado foi alterado, deseja continuar assim mesmo?");
			
			if (ok) { return true; }
			else { return false; }
		
		}
		
		else { return true; }
		
	}

}

function salvar() {

	var n_nome = document.getElementById('n_nome').value;
	var n_senha = document.getElementById('n_senha').value;
	var n_email = document.getElementById('n_email').value;
	
	var div = document.getElementById('ctexto');

	new Ajax.Request('editar_usuario.php?do=alterar', {
		
		method:'post',
		parameters: {n_nome: n_nome, n_senha: n_senha, n_email: n_email},
		onLoading: div.innerHTML = '<div class="ajax"><img src="../images/carregando.gif"><br>Salvando dados, por favor aguarde...</div>',
		onSuccess: function(transport) {
			
			if (200 == transport.status) {
				document.location = 'usuarios.php';
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
     	
        <h1>Alterar dados de usu&aacute;rio</h1>
        
        <?=$mensagem ?>
        
        <form name="alteraDados" id="alteraDados" onsubmit="return validar(this);" method="post" action="?do=alterar&uid=<?=$_REQUEST["uid"] ?>">

		<div class="form-cadastro">
        
        <fieldset id="field-caract" style="width:500px; margin:0 auto;">
            <legend>Dados de <?=$unome ?>:</legend>
            
            <table width="65%" border="0" cellspacing="2" cellpadding="4">
              <tr>
                <td width="39%" valign="top">Nome:</td>
                <td width="61%"><input type="text" name="n_nome" id="n_nome" title="Digite um novo nome." value="<?=$unome; ?>" />
                <? (isset($do) && $do == "aviso" && isset($msg) && $msg == "nome") ? print("<div class='invalido'>N&atilde;o &eacute; permitido mais de um usu&aacute;rio com mesmo nome no sistema.</div>") : ""; ?>
                </td>
              </tr>
              <tr>
                <td width="39%" valign="top">E-mail:</td>
                <td width="61%"><input type="text" name="n_email" id="n_email" title="Digite um novo endereço de e-mail." value="<?=$uemail; ?>" />
                <? (isset($do) && $do == "aviso" && isset($msg) && $msg == "email") ? print("<div class='invalido'>N&atilde;o &eacute; permitido mais de um usu&aacute;rio com mesmo endere&ccedil;o de e-mail no sistema.</div>") : ""; ?>
                </td>
              </tr>
              <tr>
                <td valign="top">Nova Senha:</td>
                <td><input type="password" name="n_senha" id="n_senha" title="Nova Senha. Deixar em branco caso não queira alterar." /> 
                  <br /><span class="tips-caracteristicas">(deixar em branco caso n&atilde;o deseje alterar).</span></td>
              </tr>
            </table>
            
          </fieldset>
        
        <p align="center">
          <input type="submit" id="go" value="Alterar dados" title="Salvar altera&ccedil;&otilde;es" />
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