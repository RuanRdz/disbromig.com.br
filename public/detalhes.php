<?php /*require_once("php7_mysql_shim.php");*/
# detalhes.php - pagina com detalhes dos produtos
 
require("painel/include/func.php");
 
conectar();

$pid = (int) isset($_REQUEST["pid"]) ? $_REQUEST["pid"] : '';

// consulta produtos
$produtos_sql = mysqli_query($conn, "SELECT * FROM produtos WHERE status='1' AND id='".$pid."'");
if(mysqli_num_rows($produtos_sql)==0){
	header("Location:http://www.disbromig.com.br/");
}else{
// consulta caracteristicas cadastradas para este produto
$caract_sql = mysqli_query($conn, "SELECT * FROM caract_reg WHERE produto='".$pid."' ORDER BY caract ASC");

$num_caract = mysqli_num_rows($caract_sql);
 }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<meta name="description" content="A Disbromig Ferramentas Pneum�ticas � uma distribuidora autorizada da marca Brobr�s, atua distribuindo ferramentas pneum�ticas para os setores de Minera��o, Sider�rgicas, Fundi��es, Metal�rgicas, Eletro-Eletr�nicas, Eletro-Dom�sticos, Ind�strias Aliment�cias, Auto Pe�as, Ind�strias Automobil�sticas, Cimenteira e Constru��o Civil.">

<meta name="keywords" content="bomba imers�o centr�fuga desincrustadores �mbolos esmerilhadeiras ferramentaria furadeira lixadeiras pistolas  marteletes socadores talhas turbinas troles vibradores">

<meta name="author" content="Disbromig Ferramentas Pneum�ticas/ www.disbromig.com.br">

<meta name="distribution" content="Global">

<meta name="copyright" content="Disbromig Ferramentas Pneum�ticas � 2007 ">

<meta name="rating" content="General">

<meta name="resource-type" content="document">

<meta name="document-rights" content="copywritten, photos, images and systems">

<meta name="document-type" content="Public">

<meta name="document-rating" content="Safe for Kids">

<link rel="stylesheet" type="text/css" href="css/padrao.css" />
<title>Lixadeiras, furadeiras, bombas, esmerilhadeiras, vibradores, troles. Disbromig Ferramentas Pneum&aacute;ticas. Marca Brobr&aacute;s</title>

<script type="text/javascript" src="js/menu.js"></script>
<script language="javascript">var seps = "sep0";</script>

</head>

<body>

<table width="100%"><tr><td align="center">

<div id="header">

<?php include("header.php");?>

</div>

<div id="content">

<?php include("detalhesContent.php");?>

</div>

<div id="footer">

<?php include("footer.php");?>

</div>


</td></tr></table>

</body>
</html>
<?php desconectar(); ?>