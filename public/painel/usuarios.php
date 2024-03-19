<?php /*require_once("php7_mysql_shim.php");*/

# usuarios.php - Usuarios do sistema

require("include/func.php");

conectar();

// verifica se usuario esta logado
validar();

$do = $_REQUEST["do"];

$consulta_todos_sql = mysqli_query($conn, "SELECT * FROM usuarios ORDER BY id ASC");

if (isset($do) && $do == "novo") {

	$nome = addslashes($_REQUEST["nome"]);
	$senha = addslashes($_REQUEST["senha"]);
	$email = addslashes($_REQUEST["email"]);
	
	// encripta senha
	$senha = md5($senha);
	
	$chave = md5("disbromig-".$nome.".com.br");
	
	// verifica se ja ha alguem com mesmo nome e/ou email
	$valida_nome = mysqli_query($conn, "SELECT nome FROM usuarios WHERE nome='".$nome."'");
	
	if (mysqli_num_rows($valida_nome) == 1) { exit; }
	
	$valida_email = mysqli_query($conn, "SELECT * FROM usuarios WHERE email='".$email."'");
	
	if (mysqli_num_rows($valida_email) == 1) { exit;}

	// salva novo usuario no BD
	$novo_usuario_sql = mysqli_query($conn, "INSERT INTO usuarios (nome, email, senha, chave) VALUES ('".$nome."','".$email."','".$senha."','".$chave."')") or die ("Erro ao inserir novo usu�rio no Banco de Dados: ".mysqli_error($conn));

}
else if (isset($do) && $do == "remover") {

	$uid = $_REQUEST["uid"];
	
	$apaga_sql = mysqli_query($conn, "DELETE FROM usuarios WHERE id='".$uid."'") or die ("Erro ao remover usu�rio do sistema: ".$mysqli_error($conn));
	
	header("Location: usuarios.php");

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>
<script type="text/javascript" src="../js/mover.js"></script>

<link rel="stylesheet" type="text/css" href="css/painel2.css" />

<title>Disbromig - Painel de Administra&ccedil;&atilde;o</title>

<script language="javascript">

function validar(form) {
	
	with(form) {
	
		var novo_nome = form.nome;
		var novo_senha = form.senha;
		var novo_email = form.email;
	
		if (novo_nome.value == "") {
		
			alert("Voc� deve digitar um nome de usu�rio.");
			return false;
		
		}
		else if (novo_senha.value == "") {
		
			alert("N�o � poss�vel cadastrar um novo usu�rio sem uma senha.");
			return false;
		
		}		
		else { return true; }
		
	}

}

function confirma() {

	var ok = window.confirm('Voc� tem certeza que deseja remover este usu�rio do sistema?');
	
	if (ok) { return true; }
	else { return false; }

}

function telaCad(opcao) {
	
	var cad = document.getElementById('cadastro-usuario');
	
	if (opcao == "exibir") {
	
		cad.style.visibility = 'visible';
		
	}
	else if (opcao == "fechar") {
	
		cad.style.visibility = 'hidden';
		
	}

}

function salvar() {

	var new_nome = document.getElementById('nome').value;
	var new_senha = document.getElementById('senha').value;
	var new_email = document.getElementById('email').value;
	
	var div = document.getElementById('ctexto');

	new Ajax.Request('usuarios.php?do=novo', {
		
		method:'post',
		parameters: {nome: new_nome, senha: new_senha, email: new_email},
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
     	
      <h1>Usu&aacute;rios do sistema</h1>
        
        <br />
        
        
        <!-- DIV c/ form para cadastro de novo usuario -->
        
        <div id="cadastro-usuario">
        
	        <span onmousedown="dragStart(event, 'cadastro-usuario')">
	
     			<div id="cadastro-titulo">Cadastrar Usu&aacute;rio &nbsp; <a href="#" onclick="javascript:telaCad('fechar');" title="Clique aqui para fechar essa janela.">X</a></div>
    
            </span>
            
            <div id="cadastro-corpo">
                
                <p>Preencha todos os campos abaixo:</p>
                <p>* = Campos obrigat&oacute;rios. </p>


                <form method="post" name="cadUser" id="cadUser" onsubmit="return validar(this);" action="javascript:salvar();">
                <table>
                <tr>
                <td><label for="nome">Nome:</label></td>
                <td><input type="text" name="nome" id="nome" title="Nome do novo usu&aacute;rio" /> *</td>
                </tr>
                <tr>
                <td><label for="email">E-Mail:</label></td>
                <td><input type="text" name="email" id="email" title="Endere&ccedil;o de e-mail" /></td>
                </tr>
                <tr>
                <td><label for="senha">Senha:</label></td>
                <td><input type="password" name="senha" id="senha" title="Senha" /> *</td>
                </tr>
                </table>
                <br /><i>O sistema n&atilde;o cadastra mais de um <br />usu&aacute;rio com mesmo nome e/ou e-mail.</i>
                <p align="center"><input type="submit" value="Completar Cadastro" title="Clique aqui para completar o cadastro" /></p>
                
                </form>
                
            </div>
            
        </div>
        
        <!-- FIM do DIV para cadastro de novo usuario -->
        
        
        <table width="80%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr>
            <td width="38%" class="cad-usuarios"><span class="cad-usuarios"><a href="#" onclick="telaCad('exibir');" title="Clique aqui para cadastrar um novo usu&aacute;rio.">[Novo Usu&aacute;rio]</a></span></td>
          </tr>
          </table>         
          
          <table width="80%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr>
            <td bgcolor="#006B8A" class="tbl-usuarios">ID</td>
            <td bgcolor="#006B8A" class="tbl-usuarios">Nome</td>
            <td bgcolor="#006B8A" class="tbl-usuarios">E-Mail</td>
            <td width="50" bgcolor="#006B8A" class="tbl-usuarios">A&ccedil;&atilde;o</td>
          </tr>
          
          <?php
		  	
				$a = 1;
		  
				while ($r=mysqli_fetch_array($consulta_todos_sql)) {
				
					($a%2 == 1) ? print("<tr bgcolor='#ffffff' onMouseOver=\"this.bgColor='#FFE5CC'\" onMouseOut=\"this.bgColor='#ffffff'\">") : print("<tr bgcolor='#CFE4E9' onMouseOver=\"this.bgColor='#FFE5CC'\" onMouseOut=\"this.bgColor='#CFE4E9'\">");
				
					echo "<td><span class='td-usuarios'>".$r["id"]."</span></td>
						<td><span class='td-usuarios'>".$r["nome"]."</span></td>
						<td><span class='td-usuarios'>".$r["email"]."</span></td>
						<td><span class='td-usuarios'>";
					
					($r["nome"] == $_COOKIE["usuario"]) ? print("<a href='perfil.php'><img src='../images/editar.gif' border='0' title='Editar Dados' alt='Editar'></a>") : print("<a href=\"editar_usuario.php?uid=".$r["id"]."\"><img src='../images/editar.gif' border='0' title='Editar Dados' alt='Editar'></a>");
					
					echo "<a onclick='return confirma();' href=\"usuarios.php?do=remover&uid=".$r["id"]."\"><img src='../images/deletar.gif' border='0' title='Remover usu�rio do sistema' alt='Remover'></a></span></td>";
						
					echo "</tr>";

					$a++;
					
				}
				
		   ?>
           
        </table>
        <br />
        <table width="80%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr>
            <td width="38%" class="cad-usuarios"><div class="legenda"><img src="../images/editar.gif" /> Editar dados do usu&aacute;rio<br /><img src="../images/deletar.gif" /> Apagar usu&aacute;rio do sistema</div></td>
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