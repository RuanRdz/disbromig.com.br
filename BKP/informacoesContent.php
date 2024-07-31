<?php require_once("php7_mysql_shim.php");

# informacoes.php - pagina com formulario para solicitacao de informacoes
require("painel/include/func.php");

conectar();

// carrega swift mailer
require_once "lib/Swift.php";
require_once "lib/Swift/Connection/SMTP.php";
require_once "lib/Swift/Authenticator/LOGIN.php";

// configuracao do email, smtp

//$smtp = new Swift_Connection_SMTP("smtp.disbromig.com.br"); # endereco smtp
//$smtp->setUsername("disbromig=disbromig.com.br"); # usuario (email)
//$smtp->setPassword("dis2030"); # senha 


$swift = new Swift($smtp);


$do = $_REQUEST["do"];

if (isset($do) && $do == "novo") {

	$nome = utf8_decode($_REQUEST["nome"]);
	$empresa = utf8_decode($_REQUEST["empresa"]);
	$cidade = utf8_decode($_REQUEST["cidade"]);
	$estado = utf8_decode($_REQUEST["estado"]);
	$endereco = utf8_decode($_REQUEST["endereco"]);
	$telefone = utf8_decode($_REQUEST["telefone"]);
	$email = utf8_decode($_REQUEST["email"]);
	$produtos = utf8_decode($_REQUEST["produtos"]);
	$comentarios = utf8_decode($_REQUEST["comentarios"]);
	
	$lista_produtos = explode(",",$produtos);
	$date = date("d/m/Y");



$corpo = <<< EOHTML

<html>
<body>
<br>
<table width='88%' border='0' align='center' cellpadding='4' cellspacing='2'>
         <tr>
           <td width='46%' valign='top'>Nome:</td>
           <td width='54%'>$nome</td>
         </tr>
         <tr>
           <td valign='top'>Empresa:</td>
           <td>$empresa</td>
         </tr>
         <tr>
           <td valign='top'>Cidade:</td>
           <td>$cidade</td>
         </tr>
         <tr>
           <td valign='top'>Estado:</td>
           <td>$estado</td>
         </tr>
         <tr>
           <td valign='top'>Endere&ccedil;o:</td>
           <td>$endereco</td>
         </tr>
         <tr>
           <td valign='top'>Telefone para contato:</td>
           <td>$telefone</td>
         </tr>
         <tr>
           <td valign='top'>E-mail:</td>
           <td><a href='mailto:$email>'>$email</a></td>
         </tr>
         <tr>
           <td valign='to$corpop'>Produtos em interesse:</td>
           <td>$produtos</td>
         </tr>
         <tr>
           <td valign='top'>Coment&aacute;rios:</td>
           <td>$comentarios</td>
         </tr>
       </table>
</body>
</html>


EOHTML;
/////////////////////////////////////////////////////////////////////////////
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'To: SITE <disbromig@disbromig.com.br>' . "\r\n";
	$headers .= 'From: SITE <disbromig@disbromig.com.br>' . "\r\n";
	//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
	//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
	mail("disbromig@disbromig.com.br",  "Disbromig - SITE",$corpo, $headers);
//By Hostnet
/////////////////////////////////////////////////////////////////////////////

	/*/ compoe email
	$message = new Swift_Message($assunto, $corpo_inteiro, "text/html");
	$recipients = new Swift_RecipientList();
	//$recipients->addTo("rodrigo@foton.com.br"); #debug
	$recipients->addTo($disbromig_mail);
	// envia email
	$swift->batchSend($message, $recipients, new Swift_Address("disbromig@disbromig.com.br", "Disbromig"));*/
	
	// salva no bd
	$nova_solicitacao_sql = mysql_query("INSERT INTO solicitacoes (nome, empresa, cidade, estado, endereco, telefone, email, produtos, comentarios, data) VALUES ('".$nome."','".$empresa."','".$cidade."','".$estado."','".$endereco."','".$telefone."','".$email."','".$produtos."','".$comentarios."','".$date."')") or die (mysql_error());

}

desconectar();

?>

<div id="contentBox">

	<div id="contentText">
	
	<div id="conteudo">
         
     <div class="conteudo-texto" id="ctexto">
       <img src="images/layout/faleconosco.png">
       <p class="tips-caracteristicas">* = campos obrigat&oacute;rios</p>
       <form name="informacoes" onsubmit="return validar(this);" method="post" action="javascript:salvar();">
       <table width="62%" border="0" align="center" cellpadding="4" cellspacing="2">
         <tr>
           <td width="38%" valign="top"><div align="right"><label for="nome">Nome:</label></div></td>
           <td width="67%"><input type="text" name="nome" id="nome" size="35" /> *</td>
         </tr>
         <tr>
           <td valign="top"><div align="right"><label for="empresa">Empresa:</label></div></td>
           <td><input type="text" name="empresa" id="empresa" size="35" /> *</td>
         </tr>
         <tr>
           <td valign="top"><div align="right"><label for="cidade">Cidade:</label></div></td>
           <td><input type="text" name="cidade" id="cidade" size="35" /></td>
         </tr>
         <tr>
           <td valign="top"><div align="right"><label for="estado">Estado:</label></div></td>
           <td><input type="text" name="estado" id="estado" size="35" /></td>
         </tr>
         <tr>
           <td valign="top"><div align="right"><label for="endereco">Endere&ccedil;o:</label></div></td>
           <td><input type="text" name="endereco" id="endereco" size="35" /></td>
         </tr>
         <tr>
           <td valign="top"><div align="right"><label for="telefone">Telefone para contato:</label></div></td>
           <td><input type="text" name="telefone" id="telefone" size="35" /> *</td>
         </tr>
         <tr>
           <td valign="top"><div align="right"><label for="email">E-mail:</label></div></td>
           <td><input type="text" name="email" id="email" size="35" /> *</td>
         </tr>
         <tr>
           <td valign="top"><div align="right"><label for="produtos">Produtos em interesse:</label></div></td>
           <td><textarea name="produtos" id="produtos" cols="35" rows="4"></textarea><br />
           <span class="tips-caracteristicas">Digite as primeiras letras do produto desejado.<br />Para adicionar outro produto, separe por v&iacute;rgulas.</span></td>
         </tr>
         <tr>
           <td valign="top"><div align="right"><label for="comentarios">Coment&aacute;rios:</label></div></td>
           <td><textarea name="comentarios" id="comentarios" cols="35" rows="3"></textarea></td>
         </tr>
       </table>
       <p align="center"><input type="submit" value="Enviar &gt;&gt;" class="botao" /></p>
       </form>
    </div>
     
  </div>
	
	</div>
	
</div>
