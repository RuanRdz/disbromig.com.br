<?php require_once("php7_mysql_shim.php");

# Meus dados - dados do usuario logado

require("include/func.php");

conectar();

// verifica se usuario esta logado
validar();

$do = $_REQUEST["do"];

$meu_id_sql = mysql_query("SELECT id, email, chave FROM usuarios WHERE nome = '".$_COOKIE["usuario"]."'") or die(mysql_error());
	
$meu_id = mysql_result($meu_id_sql,0);
$meu_email = mysql_result($meu_id_sql,0,1);
$minha_chave = mysql_result($meu_id_sql,0,2);

if (isset($do) && $do == "alterar") {

	$n_nome = addslashes($_REQUEST["n_nome"]);
	$n_senha = addslashes($_REQUEST["n_senha"]);
	$n_email = addslashes($_REQUEST["n_email"]);
	
	// altera nome do usuario
	$altera_bd = "UPDATE usuarios SET chave='".$minha_chave."'";
	(!is_null($n_nome)) ? $altera_bd .= ", nome='".$n_nome."'" : "";
	(!is_null($n_email)) ? $altera_bd .= ", email='".$n_email."'" : "";
	(!is_null($n_senha) && $n_senha != "") ? $altera_bd .= ", senha='".md5($n_senha)."'" : "";
	$altera_bd .= " WHERE id='".$meu_id."'";
	
	// executa sql
	$salvar = mysql_query($altera_bd) or die (mysql_error());
	
	setcookie("key", "0", time()-3600, "/");
	setcookie("usuario", "0", time()-3600, "/");
	
	// atualiza cookie
	$cookie_A = md5("disbromig-".$n_nome.".com.br");
	$cookie_B = $n_nome;
		
	setcookie("key", "$cookie_A", time()+31536000, "/");
	setcookie("usuario", "$cookie_B", time()+31536000, "/");

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
		else if (nome.value == "<?=$_COOKIE["usuario"]; ?>" && email.value == "<?=$meu_email; ?>" && senha.value == "") {
		
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

	new Ajax.Request('perfil.php?do=alterar', {
		
		method:'post',
		parameters: {n_nome: n_nome, n_senha: n_senha, n_email: n_email},
		onLoading: div.innerHTML = '<div class="ajax"><img src="../images/carregando.gif"><br>Salvando dados, por favor aguarde...</div>',
		onSuccess: function(transport) {
			
			if (200 == transport.status) {
				document.location = 'painel.php';
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
     	
        <h1>Meus dados</h1>
        
        <form name="meusDados" id="meusDados" onsubmit="return validar(this);" method="post" action="javascript:salvar();">

		<div class="form-cadastro">
        
        <fieldset id="field-caract" style="width:500px; margin:0 auto;">
            <legend>Altere seu nome e senha de usu&aacute;rio:</legend>
            
            <table width="52%" border="0" cellspacing="2" cellpadding="4">
              <tr>
                <td width="32%" valign="top">Nome:</td>
                <td width="68%"><input type="text" name="n_nome" id="n_nome" title="Digite um novo nome." value="<?=$_COOKIE["usuario"]; ?>" /></td>
              </tr>
              <tr>
                <td width="32%" valign="top">E-mail:</td>
                <td width="68%"><input type="text" name="n_email" id="n_email" title="Digite um novo endereço de e-mail." value="<?=$meu_email; ?>" /></td>
              </tr>
              <tr>
                <td valign="top">Senha:</td>
                <td><input type="password" name="n_senha" id="n_senha" title="Nova Senha. Deixar em branco caso não queira alterar." /> 
                  <span class="tips-caracteristicas">(deixar em branco caso n&atilde;o deseje alterar).</span></td>
              </tr>
            </table>
            
          </fieldset>
        
        <p align="center">
          <input type="submit" value="Alterar dados" title="Salvar altera&ccedil;&otilde;es" />
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