<?php

# Login - Pagina de entrada para o painel de administracao

// chama funcoes do sistema
require("include/func.php");

// acao a tomar
$do = $_REQUEST["do"];

// realiza conexao com bd
conectar();

// verifica existencia de cookie
(isset($_COOKIE["key"]) && isset($_COOKIE["usuario"])) ? header("Location: painel.php") : "" ;

if ($do == "login") {

	$user = addslashes(strip_tags($_REQUEST["usuario"]));
	$senha = addslashes(strip_tags($_REQUEST["senha"]));
	
	$smd5 = md5($senha);
	
	$valida_usuario = mysqli_query($conn, "SELECT * FROM usuarios WHERE nome='".$user."' AND senha='".$smd5."' LIMIT 1") or die ("Erro ao validar usuário: ".mysqli_error($conn));

	if (mysqli_num_rows($valida_usuario) == 1) {
	
		// cria cookie para validar usuario no sistema
		$cookie_A = md5("disbromig-".$user.".com.br");
		$cookie_B = $user;
		
		setcookie("key", "$cookie_A", time()+31536000, "/");
		setcookie("usuario", "$cookie_B", time()+31536000, "/");
		
		// redireciona usuario para o painel de administracao
		header("Location: painel.php");
		
	}
	else if (mysqli_num_rows($valida_usuario) != 1) {
		
		// usuario inexistente ou dados invalidos
		header("Location: index.php?do=aviso");
		
	}
	
	
} // fim da acao login
else if ($do == "logout") {

	// efetua logout
	if (!$_COOKIE["key"] && !$_COOKIE["usuario"]) { die ("Voc&ecirc; n&atilde;o est&aacute; logado para sair do sistema."); }
	
	setcookie("key", "0", time()-3600, "/");
	setcookie("usuario", "0", time()-3600, "/");
	
	header("Location: index.php");
	exit;

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="css/painel2.css" />
<title>Disbromig - Painel de administra&ccedil;&atilde;o</title>

<script language="javascript">

function validar(form) {
	
	with (form) {
		if (form.usuario.value == "") { alert("Por favor entre com o nome de usuário"); return false; }
		else if (form.senha.value == "") { alert("Por favor entre com a senha"); return false; }
		else { return true; }
	}
	
}

</script>

</head>

<body>

<div id="painel-entrar">

	<div class="painel-titulo">
	  &Aacute;rea restrita, por favor identifique-se:
    </div>
      
    <div class="painel-texto">
    <form name="login" id="login" method="post" onsubmit="return validar(this);" action="?do=login">
    <table width="100%" border="0">
    	<tr>
        	<td>
              <label for="usuario">Nome de usu&aacute;rio:</label>
              <br />
              <input type="text" name="usuario" id="usuario" size="25" alt="Usuario" title="Nome de usu&aacute;rio" />
              <br />
              <label for="senha">Senha:</label>
              <br />
		      <input type="password" name="senha" id="senha" size="25" alt="Senha" title="Digite sua senha" />
            </td>
            <td>
            <input type="submit" id="painel-bt_entrar" value="Entrar" alt="Entrar" title="Entrar no painel de administra&ccedil;&atilde;o" />
            </td>
        </tr>
    </table>
    </form>
    
    <?php
	
		if (isset($_REQUEST["do"]) && $_REQUEST["do"] == "aviso") {
		
			print("<div class='invalido'>Dados inválidos ou usuário inexistente.</div>");
			
		}
		
	?>
    
    </div>

</div>

<?php

desconectar();

?>

</body>
</html>