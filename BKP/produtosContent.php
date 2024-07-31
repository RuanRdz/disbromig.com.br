<?php require_once("php7_mysql_shim.php");

# produtos.php - pagina de produtos
 
require("painel/include/func.php");

conectar();
 
# PAGINACAO
$pagina = $_REQUEST['pagina'];
$do = $_REQUEST["do"]; // acao

if (!$pagina) {
  $pagina = 1;
}

// numero de produtos por pagina

$num_por_pagina = 18; // numero de itens por pagina

$primeiro_registro = ($pagina*$num_por_pagina) - $num_por_pagina;

# paginacao continua maix abaixo

# PESQUISA DE PRODUTOS
if (isset($do) && $do == "pesquisar") {
#PESQUISAR

	$chave = $_REQUEST["chave"]; // palavra-chave
	$campo = $_REQUEST["campo"]; // campo a pesquisar
	$filtro = $_REQUEST["filtro"]; // filtragem
	
	/*
	explode $chave
	caso houver "*" ira buscar pela palavra chave com excessao do que vier apos o simbolo "*"
	utilizei * pois ha muitos produtos cujo modelo/codigo possuem "-"
	exemplo:
	$chave = "motor*motorizado"
	ou
	$chave = "motor *motorizado"
	*/
	
	$chave = explode("*",$chave);
	$arrChave = count($chave);
	
	switch($campo) {
	
		case "titulo":
			
			if ($filtro == "qualquer") {
				// consulta o banco procurando por titulos que contenham as letras da palavra chave
				if ($arrChave <= 1) {
					$consulta_todos_sql = mysql_query("SELECT * FROM produtos WHERE titulo LIKE '%".$chave[0]."%' AND status='1' AND listagem='1' ORDER BY titulo ASC LIMIT $primeiro_registro, $num_por_pagina");
					// numero total de resultados encontrados para a paginacao
					$consulta_total = mysql_query("SELECT COUNT(*) FROM produtos WHERE titulo LIKE '%".$chave[0]."%' AND status='1' AND listagem='1'");
					// numero total de resultados para exibir p/ usuario
					$resultados_sql = mysql_query("SELECT * FROM produtos WHERE titulo LIKE '%".$chave[0]."%' AND status='1' AND listagem='1'");
				}
				else if ($arrChave >= 2) {
					$consulta_todos_sql = mysql_query("SELECT * FROM produtos WHERE titulo LIKE '%".$chave[0]."%' AND titulo NOT LIKE '%".$chave[1]."%' AND status='1' AND listagem='1' ORDER BY titulo ASC LIMIT $primeiro_registro, $num_por_pagina");
					// numero total de resultados encontrados para a paginacao
					$consulta_total = mysql_query("SELECT COUNT(*) FROM produtos WHERE titulo LIKE '%".$chave[0]."%' AND titulo NOT LIKE '%".$chave[1]."%' AND status='1' AND listagem='1'");
					// numero total de resultados para exibir p/ usuario
					$resultados_sql = mysql_query("SELECT * FROM produtos WHERE titulo LIKE '%".$chave[0]."%' AND titulo NOT LIKE '%".$chave[1]."%' AND status='1' AND listagem='1'");
				}
				$resultados = mysql_num_rows($resultados_sql);
			}
			else if ($filtro == "exata") {
				// consulta o banco procurando por titulos que correspondem exatamente a palavra chave
				if ($arrChave <= 1) {
					$consulta_todos_sql = mysql_query("SELECT * FROM produtos WHERE titulo = '".$chave[0]."' AND status='1' AND listagem='1' ORDER BY titulo ASC LIMIT $primeiro_registro, $num_por_pagina");
					// numero total de resultados encontrados para a paginacao
					$consulta_total = mysql_query("SELECT COUNT(*) FROM produtos WHERE titulo = '".$chave[0]."' AND status='1' AND listagem='1'");
					// numero total de resultados para exibir p/ usuario
					$resultados_sql = mysql_query("SELECT * FROM produtos WHERE titulo = '".$chave[0]."' AND status='1' AND listagem='1'");
				}
				else if ($arrChave >= 2) {
					$consulta_todos_sql = mysql_query("SELECT * FROM produtos WHERE titulo = '".$chave[0]."' AND status='1' AND listagem='1' ORDER BY titulo ASC LIMIT $primeiro_registro, $num_por_pagina");
					// numero total de resultados encontrados para a paginacao
					$consulta_total = mysql_query("SELECT COUNT(*) FROM produtos WHERE titulo = '".$chave[0]."' AND status='1' AND listagem='1'");
					// numero total de resultados para exibir p/ usuario
					$resultados_sql = mysql_query("SELECT * FROM produtos WHERE titulo = '".$chave[0]."' AND status='1' AND listagem='1'");
				}
				$resultados = mysql_num_rows($resultados_sql);
			}
			
		break;
		case "codigo":
			
			if ($filtro == "qualquer") {
				// consulta o banco procurando por titulos que contenham as letras da palavra chave
				if ($arrChave <= 1) {
					$consulta_todos_sql = mysql_query("SELECT * FROM produtos WHERE codigo LIKE '%".$chave[0]."%' AND status='1' AND listagem='1' ORDER BY codigo ASC LIMIT $primeiro_registro, $num_por_pagina");
					// numero total de resultados encontrados para a paginacao
					$consulta_total = mysql_query("SELECT COUNT(*) FROM produtos WHERE codigo LIKE '%".$chave[0]."%' AND status='1' AND listagem='1'");
					// numero total de resultados para exibir p/ usuario
					$resultados_sql = mysql_query("SELECT * FROM produtos WHERE codigo LIKE '%".$chave[0]."%' AND status='1' AND listagem='1'");
				}
				else if ($arrChave >= 2) {
					$consulta_todos_sql = mysql_query("SELECT * FROM produtos WHERE codigo LIKE '%".$chave[0]."%' AND codigo NOT LIKE '%".$chave[1]."%' AND status='1' AND listagem='1' ORDER BY codigo ASC LIMIT $primeiro_registro, $num_por_pagina");
					// numero total de resultados encontrados para a paginacao
					$consulta_total = mysql_query("SELECT COUNT(*) FROM produtos WHERE codigo LIKE '%".$chave[0]."%' AND codigo NOT LIKE '%".$chave[1]."%' AND status='1' AND listagem='1'");
					// numero total de resultados para exibir p/ usuario
					$resultados_sql = mysql_query("SELECT * FROM produtos WHERE codigo LIKE '%".$chave[0]."%' AND codigo NOT LIKE '%".$chave[1]."%' AND status='1' AND listagem='1'");
				}
				$resultados = mysql_num_rows($resultados_sql);
			}
			else if ($filtro == "exata") {
				// consulta o banco procurando por titulos que correspondem exatamente a palavra chave
				$consulta_todos_sql = mysql_query("SELECT * FROM produtos WHERE codigo = '".$chave[0]."' AND status='1' AND listagem='1' ORDER BY codigo ASC LIMIT $primeiro_registro, $num_por_pagina");
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysql_query("SELECT COUNT(*) FROM produtos WHERE codigo = '".$chave[0]."' AND status='1' AND listagem='1'");
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysql_query("SELECT * FROM produtos WHERE codigo = '".$chave[0]."' AND status='1' AND listagem='1'");
				$resultados = mysql_num_rows($resultados_sql);
			}
			
		break;
		case "tudo":
		
			if ($filtro == "qualquer") {
				// consulta o banco procurando em todas os campos que contenham as letras da palavra chave
				if ($arrChave <= 1) {
					$consulta_todos_sql = mysql_query("(SELECT * FROM produtos WHERE codigo LIKE '%".$chave[0]."%' AND status='1' AND listagem='1') UNION (SELECT * FROM produtos WHERE titulo LIKE '%".$chave[0]."%' AND status='1' AND listagem='1') ORDER BY titulo ASC") or die (mysql_error());
					
					// numero total de resultados encontrados para a paginacao
					$consulta_total = mysql_query("(SELECT COUNT(*) FROM produtos WHERE codigo LIKE '%".$chave[0]."%' AND status='1' AND listagem='1') UNION (SELECT COUNT(*) FROM produtos WHERE titulo LIKE '%".$chave[0]."%' AND status='1' AND listagem='1')");
				
					// numero total de resultados para exibir p/ usuario
					$resultados_sql = mysql_query("SELECT * FROM produtos WHERE codigo LIKE '%".$chave[0]."%' AND status='1' AND listagem='1' UNION SELECT * FROM produtos WHERE titulo LIKE '%".$chave[0]."%' AND status='1' AND listagem='1'");
				}
				else if ($arrChave >= 2) {
					$consulta_todos_sql = mysql_query("(SELECT * FROM produtos WHERE codigo LIKE '%".$chave[0]."%' AND codigo NOT LIKE '%".$chave[1]."%' AND status='1' AND listagem='1') UNION (SELECT * FROM produtos WHERE titulo LIKE '%".$chave[0]."%' AND titulo NOT LIKE '%".$chave[1]."%' AND status='1' AND listagem='1') ORDER BY titulo ASC") or die (mysql_error());
					
					// numero total de resultados encontrados para a paginacao
					$consulta_total = mysql_query("(SELECT COUNT(*) FROM produtos WHERE codigo LIKE '%".$chave[0]."%' AND codigo NOT LIKE '%".$chave[1]."%' AND status='1' AND listagem='1') UNION (SELECT COUNT(*) FROM produtos WHERE titulo LIKE '%".$chave[0]."%' AND titulo NOT LIKE '%".$chave[1]."%' AND status='1' AND listagem='1')");
				
					// numero total de resultados para exibir p/ usuario
					$resultados_sql = mysql_query("SELECT * FROM produtos WHERE codigo LIKE '%".$chave[0]."%' AND codigo NOT LIKE '%".$chave[1]."%' AND status='1' AND listagem='1' UNION SELECT * FROM produtos WHERE titulo LIKE '%".$chave[0]."%' AND titulo NOT LIKE '%".$chave[1]."%' AND status='1' AND listagem='1'");
				}
				$resultados = mysql_num_rows($resultados_sql);
			}
			else if ($filtro == "exata") {
				// consulta o banco procurando por ids que correspondem exatamente a palavra chave
				$consulta_todos_sql = mysql_query("(SELECT * FROM produtos WHERE codigo = '".$chave[0]."' AND status='1' AND listagem='1') UNION (SELECT * FROM produtos WHERE titulo = '".$chave[0]."' AND status='1' AND listagem='1') ORDER BY titulo ASC") or die (mysql_error());
							
				// numero total de resultados encontrados para a paginacao
				$consulta_total = mysql_query("(SELECT COUNT(*) FROM produtos WHERE codigo = '".$chave[0]."' AND status='1' AND listagem='1') UNION (SELECT COUNT(*) FROM produtos WHERE titulo = '".$chave[0]."' AND status='1' AND listagem='1')");
				
				// numero total de resultados para exibir p/ usuario
				$resultados_sql = mysql_query("(SELECT * FROM produtos WHERE codigo = '".$chave[0]."' AND status='1' AND listagem='1') UNION (SELECT * FROM produtos WHERE titulo = '".$chave[0]."' AND status='1' AND listagem='1')");
				$resultados = mysql_num_rows($resultados_sql);
			}
		
		break;
	
	}

}
else if (!isset($pesquisar)) {
	$consulta_total = mysql_query("SELECT COUNT(*) FROM produtos WHERE status='1' AND listagem='1'");
	$consulta_todos_sql = mysql_query("SELECT * FROM produtos WHERE status='1' AND listagem='1' ORDER BY titulo ASC LIMIT $primeiro_registro, $num_por_pagina");
}

# continua paginacao

list($total_produtos) = mysql_fetch_array($consulta_total);

$total_paginas = $total_produtos/$num_por_pagina;

$prev = $pagina - 1;
$next = $pagina + 1;

if ($pagina > 1) {
		
	if (isset($do) && $do == "pesquisar") {
		// continua a paginacao para a pesquisa realizada
		$anterior = "<a href=\"produtos.php?do=pesquisar&chave=".$_REQUEST["chave"]."&campo=".$_REQUEST["campo"]."&filtro=".$_REQUEST["filtro"]."&pagina=$prev\">&lt; Anterior</a>";
	}
	else { 
		$anterior = "<a href=\"produtos.php?pagina=$prev\">&lt; Anterior</a>";
	}
			
} else { // senão não há link para a página anterior
		
	$anterior = "Anterior";
			
}

if ($total_paginas > $pagina) {
		
	if (isset($do) && $do == "pesquisar") {
		// continua a paginacao para a pesquisa realizada
		$proximo = "<a href=\"produtos.php?do=pesquisar&chave=".$_REQUEST["chave"]."&campo=".$_REQUEST["campo"]."&filtro=".$_REQUEST["filtro"]."&pagina=$next\">Pr&oacute;ximo &gt;</a>";
	}
	else {
		$proximo = "<a href=\"produtos.php?pagina=$next\">Pr&oacute;ximo &gt;</a>";
	}
			
			
} else { // senão não há link para a próxima página
		
	$proximo = "Pr&oacute;ximo";
			
}

$total_paginas = ceil($total_paginas);
$n_paginas = "";
		
for ($x=1; $x<=$total_paginas; $x++) {
		
	if ($x==$pagina) { // se estivermos na página corrente, não exibir o link para visualização desta página
	   $n_paginas .= " [$x] ";
		
	} else {
		  	
		if (isset($do) && $do == "pesquisar") {
			// continua a paginacao para a pesquisa realizada
			$n_paginas .= " <a href=\"produtos.php?do=pesquisar&chave=".$_REQUEST["chave"]."&campo=".$_REQUEST["campo"]."&filtro=".$_REQUEST["filtro"]."&pagina=$x\">[$x]</a> ";
		}
		else {
			$n_paginas .= " <a href=\"produtos.php?pagina=$x\">[$x]</a> ";
		}

	}

}
# FIM DA PAGINACAO
# para exibir links (< anterior | 0-9 | proximo >) utilize o codigo: echo $anterior." | ".$n_paginas." | ".$proximo;


?>

<div id="contentBox">

	<div id="contentText">
	
	<div id="conteudo">
         
     <div class="conteudo-texto">
     	
       <img src="images/layout/produtos.png">
        
           <div id="lista-produtos">
           
     <form name="pesquisar" method="post" action="?do=pesquisar">
    <table width="422" border="0" align="center" cellpadding="4" cellspacing="2">
      <tr>
        <td width="119"><div align="right" class="pesquisar-txt">Palavra:</div></td>
        <td width="281">
          <input name="chave" id="chave" type="text" size="20" />
          <input name="submit" type="submit" style="width:auto; height:auto;" value="Pesquisar" />        </td>
      </tr>
      <tr>
        <td><div align="right" class="pesquisar-txt">Dentro de: </div></td>
        <td>
          <select name="campo">
            <option value="tudo" <?
            if (isset($_REQUEST["campo"]) && $_REQUEST["campo"] == "tudo") {
				print("selected='selected'");
			}
			else if (!isset($_REQUEST["campo"]) || $_REQUEST["a"] != "pesquisar") {
				print("selected='selected'");
			}
			?>>Tudo</option>
            <option value="titulo" <?
            if (isset($_REQUEST["campo"]) && $_REQUEST["campo"] == "titulo") {
				print("selected='selected'");
			}
			else if (!isset($_REQUEST["campo"]) || $_REQUEST["a"] != "pesquisar") {
				//print("selected='selected'");
			}
			?> >T&iacute;tulo</option>
            <option value="codigo" <? (isset($_REQUEST["campo"]) && $_REQUEST["campo"] == "codigo") ? print("selected='selected'") : ""; ?> >C&oacute;digo</option>
          </select>
        <span class="pesquisar-txt">Filtro:</span>
          <select name="filtro">
            <option value="qualquer" <?
            if (isset($_REQUEST["filtro"]) && $_REQUEST["filtro"] == "qualquer") {
				print("selected='selected'");
			}
			else if (!isset($_REQUEST["filtro"]) || $_REQUEST["a"] != "pesquisar") {
				//print("selected='selected'");
			}
			?>>Todas as letras</option>
            <option value="exata" <? (isset($_REQUEST["filtro"]) && $_REQUEST["filtro"] == "exata") ? print("selected='selected'") : ""; ?>>Palavra exata</option>
          </select>
          </td>
        </tr>
    </table>
  </form>
        
           <table border="0" align="center" cellpadding="5" cellspacing="5">
        	   <tr>
				   <?php
                        
						# numero de produtos encontrado
						if (mysql_num_rows($consulta_todos_sql) == 0) {
							// nenhum, exibir msg
							print("<p align='center'>Nenhum produto encontrado.</p>");
						}
						else {
						
							$i = 1;
							
							while ($p=mysql_fetch_array($consulta_todos_sql)) {
							
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
								
								echo "<td align='center' valign='top'>";
								
								echo"<table cellpadding='0' cellspacing='0'><tr><td><img src='images/layout/topborder.png'></td></tr><tr><td style='background-image:url(images/layout/midborder.png);background-repeat:repeat-y;' align='center'>";

								
								echo"<div class='prod-detalhes'><b><a href='detalhes.php?pid=".$pid."' class='link-produto'>".$codigo."</a></b>";
								
								
								($promocao == "1" && isset($promocaoValor) && !is_null($promocaoValor)) ? print("<br /><br /><span class='desconto'>Desconto: -".$promocaoValor."%</span>") : "";
	
								echo "</div>";
								
								
								echo "<div class='prod-titulo'>".$titulo."</div><br />";
	
								(isset($thumb) && $thumb != "") ? print('<a href="detalhes.php?pid='.$pid.'"><img src="'.$url.$thumb.'" title="Ver detalhes de '.$titulo.'" border="0" align="middle"></a>') : print('<a href="detalhes.php?pid='.$pid.'"><img src="images/sem_imagem.gif" title="Ver detalhes de '.$titulo.'" border="0" align="middle"></a>');
								
								echo "</td></tr><tr><td><img src='images/layout/botborder.png'></td></tr></table></td></div>";
								 
								 ($i%3 == 0) ? print("<tr>") : "";
								 
								 $i++;
							
							}
                        }
						
                   ?>
           	   </tr>
           </table>
  
           
           <div class="navegacao"><? echo $anterior." | ".$n_paginas." | ".$proximo; ?></div>
        </div>
     	</div>
     
  </div>
	     
    </div>
	
</div>