<?php /*require_once("php7_mysql_shim.php");*/
# busca_produtos.php - lista produtos pelo ajax

include("painel/include/func.php");

conectar();

$q = $_GET['q'];

/*foreach($countries as $country) {
	if(eregi("^".$q, $country)) {
		echo $country."\r\n";
	}
}*/

$busca_produtos_sql = mysqli_query($conn, "SELECT * FROM produtos WHERE titulo LIKE '".$q."%' ORDER BY titulo ASC");

header("Content-Type: text/html; charset=iso-8859-1"); 

while ($c=mysqli_fetch_array($busca_produtos_sql)) {

	echo ucwords("".$c["titulo"]." (".$c["codigo"].")\r\n");

}

desconectar();

?>