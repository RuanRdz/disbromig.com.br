<?php /*require_once("php7_mysql_shim.php");*/

# Upload - upload de planilha excel

require("include/func.php");

conectar();

// verifica se usuario esta logado
validar();

// carrega login e senha de clientes (para download do xls)
$public_sql = mysqli_query($conn, "SELECT nome,senha FROM public WHERE id='1'");
while ($p=mysqli_fetch_array($public_sql)) {
	$p_login = $p["nome"];
	$p_senha = $p["senha"];
}

$do = $_REQUEST["do"];

if (isset($do) && $do == "up") {

// upload da planilha excel

	$arquivo = $_FILES["planilha"];
	
	if ($arquivo["type"] == "application/vnd.ms-excel") {
		$tamanho = filesize($arquivo["tmp_name"])/1024;
		// tamanho em bytes, se maior que 15mb nao ira continuar
		$tamanho_limite = 15728640;
		if ($tamanho > $tamanho_limite) {
			die ("Arquivo muito grande (".$tamanho."), o tamanho máximo permitido em bytes é de ".$tamanho_limite.", <a href='upload.php'>volte e tente novamente</a>.");
		}
		else {
			// realiza upload no servidor
			
			// remove todas as planilhas anteriores para liberar espaço
			if (file_exists($_SERVER['DOCUMENT_ROOT']."/estoque/planilha.xls")) {
				unlink($_SERVER['DOCUMENT_ROOT']."/estoque/planilha.xls");
			}
			//move_uploaded_file($arquivo['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/dl/".$arquivo['name']);
			move_uploaded_file($arquivo['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/estoque/planilha.xls");
			echo "<script language='javascript'>window.location='upload.php?do=resultado';</script>";
		}
	}
	else if ($arquivo["type"] != "application/vnd.ms-excel") {
		die ("A extensão do arquivo não é .xls, <a href='upload.php'>volte e tente novamente</a> com uma planilha do excel (*.xls).");
	}

}
else if ($do == "alteraLogin") {
	
	$n_login = $_REQUEST["loginPublic"];
	$n_senha = $_REQUEST["senhaPublic"];
	
	$atualiza_public_sql = mysqli_query($conn, "UPDATE public SET nome='".$n_login."', senha='".$n_senha."' WHERE id='1'") or die (mysqli_error($conn));
	
	echo "<script language='javascript'>alert('Dados alterados com sucesso!'); window.location='upload.php';</script>";
	exit;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>

<link rel="stylesheet" type="text/css" href="css/painel.css" />

<title>Disbromig - Painel de Administra&ccedil;&atilde;o</title>

<script language="javascript">

function validar(form) {
	
	with(form) {
	
		var xls = document.getElementById('planilha');
	
		if (xls.value == "") {
		
			alert("Você deve selecionar um arquivo em seu computador (Apenas planilhas do excel - *.XLS).");
			return false;
		
		}		
		else { return true; }
		
	}

}

function validarLogin(form) {
	
	with(form) {
		var login = document.getElementById("loginPublic");
		var senha = document.getElementById("senhaPublic");
		
		if (login.value == "") {
			alert("Você deve especificar um login.");
			return false;
		}
		else if (senha.value == "") {
			alert("Você deve especificar uma senha.");
			return false;
		}
		else {
			return true; 
		}
	}

}

</script>

</head>

<body>

<div id="geral">

    <div id="header">
        <div class="logo-disbromig"><a href="painel.php"><img src="../images/disbromig_logo.gif" border="0"></a></div>
        <div class="logo-brobras"><img src="../images/brobras_logo.gif" /></div>
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
     	
        <?php
		if ($do != "resultado" || !isset($do)) {
		?>
        <h1>Upload de planilha</h1>
        
        <form name="upPlanilha" id="upPlanilha" onsubmit="return validar(this);" enctype="multipart/form-data" method="post" action="upload.php?do=up">

		<div class="form-cadastro">
        
        <fieldset id="field-caract" style="width:600px; margin:0 auto;">
            <legend>Upload de nova planilha:</legend>
            
            <table width="106%" align="center" border="0" cellspacing="2" cellpadding="4">
              <tr>
                <td width="47%" valign="top">Planilha (Arquivo de Excel *.XLS)<br />
                <span class="td-usuarios">Obs.: A planilha atual ser&aacute; substitu&iacute;da por esta.</span></td>
                <td width="53%" valign="top"><input type="file" id="planilha" name="planilha" size="40" /></td>
              </tr>
            </table>
            
          </fieldset>
        
        <p align="center">
          <input type="submit" value="Enviar nova planilha" title="Enviar nova planilha" />
        </p>
        </form>
        
        <br />
        
        <hr />
        
        <br />
        
        <form method="post" onsubmit="return validarLogin(this);" action="upload.php?do=alteraLogin">
        <div align="center"><h3>Login e senha para download:</h3></div>
        <table align="center" border="0" width="500">
            <tr>
            	<td align="right"><label for="loginPublic">Login:</label></td>
                <td><input type="text" id="loginPublic" name="loginPublic" value="<?=$p_login;?>" /></td>
            </tr>
            <tr>
            	<td align="right"><label for="senhaPublic">Senha:</label></td>
                <td><input type="text" id="senhaPublic" name="senhaPublic" value="<?=$p_senha; ?>" /></td>
            </tr>
        </table>
        <p align="center"><input type="submit" value="Alterar dados de login/senha" /></p>
        </form>
        </div>
        
        
        <?php
		}
		else if ($do == "resultado") {
		?>
        <h1>Arquivo enviado com sucesso!</h1>
        
        <div align="center">
        <b>URL para download do arquivo:</b>
        <? echo "<form action='#'><input type='text' size='60' value='".$url."/estoque/'><br /><a href='".$url."/estoque/' target='_blank'> Abrir em nova janela</a></form>"; ?>
        </div>
        
        <br />
        
        <form method="post" onsubmit="return validarLogin(this);" action="upload.php?do=alteraLogin">
        <div align="center"><h3>Login e senha para download:</h3></div>
        <table align="center" border="0" width="500">
            <tr>
            	<td align="right"><label for="loginPublic">Login:</label></td>
                <td><input type="text" id="loginPublic" name="loginPublic" value="<?=$p_login;?>" /></td>
            </tr>
            <tr>
            	<td align="right"><label for="senhaPublic">Senha:</label></td>
                <td><input type="text" id="senhaPublic" name="senhaPublic" value="<?=$p_senha; ?>" /></td>
            </tr>
        </table>
        <p align="center"><input type="submit" value="Alterar dados de login/senha" /></p>
        </form>
        
        <?php
		}
		?>
        
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