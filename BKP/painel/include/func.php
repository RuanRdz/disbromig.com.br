<?php require_once("php7_mysql_shim.php");

# funcoes

$url = "http://www.disbromig.com.br";
$disbromig_mail = "disbromig@disbromig.com.br";
$assunto = "[Disbromig] - Fale Conosco";

// conexao com o banco de dados
function conectar() {
	
	// dados para conexao
	
	// HOSTNET
	$db_host = "internal-db.s221364.gridserver.com";
	$db_user = "db221364_disbro";
	$db_pass = "@Xdissqlbro708090";
	$db_name = "db221364_disbromig_xdissql708090";
	
	// conexao
	$conn = mysql_connect($db_host,$db_user,$db_pass) or die ("Erro ao conectar com o Banco de Dados: ".mysql_error());
	
	// seleciona banco
	$seleciona_bd = mysql_select_db($db_name);
	
}

// desconexao do BD
function desconectar() {
	
	mysql_close();
	
}

// calcula valor final de desconto / promocao
/* $v = valor, $desconto = promocaoValor */

function calcularPromocao($v,$desconto) {

	$v = substituir($v);
	$desconto = substituir($desconto);
	
	$porcentagem = $desconto / 100;
	$valor_final = $v - ($v * $porcentagem);
	$valor_final = number_format($valor_final, 2, ',', '.');
	
	return $valor_final;
}

// substitui virgulas, espacos, etc. de valores numericos
function substituir($var) {

	$var = str_replace(".","", $var);
	$var = str_replace(" ","", $var);
	
	return $var;
	
}

// adiciona tres pontos para 'Leia mais'
function limitar($txt, $limite) {

	if (strlen($txt) > $limite) {
		$txt = substr($txt, 0, $limite)."...";
		return $txt;
	}
	else {
		return $txt;
	}
	
}

// valida cookie do usuario
function validar() {

	if (!$_COOKIE["key"] && !$_COOKIE["usuario"]) {

	// se cookie nao existir, exibir tela para login
	header("Location: index.php?do=aviso");
	die;
	exit;
    }
	
	else if ($_COOKIE["key"] && $_COOKIE["usuario"]) {
		
		conectar();
		
		// pega valor do cookie
		
		$key = $_COOKIE["key"];
		$user = $_COOKIE["usuario"];

		$valida_cookie = mysql_query("SELECT * FROM usuarios WHERE nome = '".$user."'") or die(mysql_error());

		while($v=mysql_fetch_array($valida_cookie)) {
			$n = $v["nome"];
		}

		$db_key = md5("disbromig-".$n.".com.br");
		
		// se o nome fornecido for igual ao valor admin_id do cookie, usuario esta autenticado
		if ($db_key == $key) { return true; exit; }
		// se nao envia o usuario para pagina inicial para login
		else if ($db_key != $key) {
			
			setcookie("key", "0", time()-3600, "/");
			setcookie("usuario", "0", time()-3600, "/");
			
			header("Location: index.php?do=aviso");
			
		}
	}
	
}

?>
