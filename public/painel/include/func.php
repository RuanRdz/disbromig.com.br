<?php 

# funcoes
$conn = null;

$url = "http://www.disbromig.com.br";
$disbromig_mail = "disbromig@disbromig.com.br";
$assunto = "[Disbromig] - Fale Conosco";

// conexao com o banco de dados
function conectar() {
	global $conn;
	// dados para conexao
	
	// HOSTNET
	$db_host = "host.docker.internal";
	$db_user = "root";
	$db_pass = "root";
	$db_name = "pocinhos_disbromig_xdissql708090";
	
	// conexao
	try {
		$conn = @mysqli_connect($db_host,$db_user,$db_pass,$db_name);
		mysqli_query($conn, 'SET character_set_connection=utf8mb4');
    mysqli_query($conn, 'SET character_set_client=utf8mb4');
    mysqli_query($conn, 'SET character_set_results=utf8mb4');
	} catch (Exception $oException) {
		echo $oException->getMessage();
		die;
	}
	
	// seleciona banco
	$seleciona_bd = mysqli_select_db($conn, $db_name);
}

// desconexao do BD
function desconectar() {
	global $conn;
	mysqli_close($conn);
	
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
	global $conn;

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

		$valida_cookie = mysqli_query($conn, "SELECT * FROM usuarios WHERE nome = '".$user."'") or die(mysqli_error());

		while($v=mysqli_fetch_array($valida_cookie)) {
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
