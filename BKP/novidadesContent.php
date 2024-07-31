<?php require_once("php7_mysql_shim.php");

# novidades.php - pagina de novidades
 
require("painel/include/func.php");

conectar();
 
# PAGINACAO
$consulta_total = mysql_query("SELECT COUNT(*) FROM produtos WHERE novidades='1' AND status='1'");

$pagina = $_REQUEST['pagina'];

if (!$pagina) {
  $pagina = 1;
}

// numero de produtos novos por pagina

$num_por_pagina = 4; // numero de itens por pagina

$primeiro_registro = ($pagina*$num_por_pagina) - $num_por_pagina;

list($total_promocoes) = mysql_fetch_array($consulta_total);

$total_paginas = $total_promocoes/$num_por_pagina;

$prev = $pagina - 1;
$next = $pagina + 1;

if ($pagina > 1) {
		
	$anterior = "<a href=\"novidades.php?pagina=$prev\">&lt; Anterior</a>";
		
} else { // senão não há link para a página anterior
		
	$anterior = "Anterior";
			
}

if ($total_paginas > $pagina) {
		
	$proximo = "<a href=\"novidades.php?pagina=$next\">Pr&oacute;ximo &gt;</a>";
		
} else { // senão não há link para a próxima página
		
	$proximo = "Pr&oacute;ximo";
			
}

$total_paginas = ceil($total_paginas);
$n_paginas = "";
		
for ($x=1; $x<=$total_paginas; $x++) {
		
	if ($x==$pagina) { // se estivermos na página corrente, não exibir o link para visualização desta página
	   $n_paginas .= " [$x] ";
		
	} else {
		  	
		$n_paginas .= " <a href=\"novidades.php?pagina=$x\">[$x]</a> ";
	}
}
# FIM DA PAGINACAO
# para exibir links (< anterior | 0-9 | proximo >) utilize o codigo: echo $anterior." | ".$n_paginas." | ".$proximo;

// consulta novidades
$novidades_sql = mysql_query("SELECT * FROM produtos WHERE novidades='1' AND status='1' ORDER BY id DESC LIMIT $primeiro_registro, $num_por_pagina");
 
 
?>

<div id="contentBox">

	<div id="contentText">
	
	<div id="conteudo">
         
    <div class="conteudo-texto" id="ctexto">
	
	<img src="images/layout/novidades.png">
		
	<table border="0" align="center" width="50%" cellpadding="4" cellspacing="10">
        	   <tr>
				   <?php
                        
						# numero total de produtos novos
						$total_promo = mysql_result($consulta_total,0);

						# Caso nao houver nenhum produto em 'novidade' exibir mensagem:
						if ($total_promo == 0) {
							print("<td align='center'>No momento, n&atilde;o h&aacute; nenhuma novidade.</td>");
						}
						else {
						# Ha novidades, exibir produtos
							
							$i = 1;
							
							while ($p=mysql_fetch_array($novidades_sql)) {
							
								$thumb = $p["thumb"];
								$foto = $p["foto"];
								$titulo = $p["titulo"];
								$descricao = $p["descricao"];
								$pid = $p["id"];
								$promocao = $p["promocao"];
								$promocaoValor = $p["promoValor"];
								$categoria = $p["categoria"];
								$codigo = $p["codigo"];
								$valor = $p["valor"];
								
								// evita que imagens com espaco nao sejam exibidas
								$thumb = str_replace(" ","%20",$thumb);
								
								echo "
								
								<td width='50%'valign='top' align='center'>";
								
								echo"<table cellpadding='0' cellspacing='0'><tr><td><img src='images/layout/topborder.png'></td></tr><tr><td style='background-image:url(images/layout/midborder.png);background-repeat:repeat-y;' align='center'>";

								
								(isset($thumb) && $thumb != "") ? print('<a href="detalhes.php?pid='.$pid.'"><img src='.$url.$thumb.' alt="" title="Ver detalhes de '.$titulo.'" border="0" align="center"></a><br><br>') : print(',<br/><a href="detalhes.php?pid='.$pid.'"><img src="images/sem_imagem.gif" alt="" title="Ver detalhes de '.$titulo.'" border="0"></a>');
								
								echo "<p><b>".$titulo."</b></p>";
								
								//echo limitar($descricao,0);
								
								echo "<p>C&oacute;d.: ".$codigo."</p>";
								
								($promocao == "1" && isset($promocaoValor) && !is_null($promocaoValor)) ? print("<br /><span class='desconto'>Desconto: -".$promocaoValor."%</span>") : "";
								
								echo "<p><a href='detalhes.php?pid=".$pid."'>Mais detalhes</a></p></td></tr><tr><td><img src='images/layout/botborder.png'></td></tr></table></td>";
								 
								 ($i%2 == 0) ? print("<tr>") : "";
								 
								 $i++;
							
							}
							
						}
                        
                   ?>
           	   </tr>
           </table>
	
	</div>

</div>

</div></div>