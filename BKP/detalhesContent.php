<div id="contentBox">

	<div id="contentText">

<div id="conteudo">
  
    <div class="conteudo-texto">
    
<?php require_once("php7_mysql_shim.php");

	while ($p=mysql_fetch_array($produtos_sql)) {
				 	
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
					
		echo "<h1>".$codigo."</h2>";
		
		echo "<h2 align='center'>".$titulo."</h2>";

		echo "<h4 align='center'>";
		
		($categoria == 1) ? print("Ferramentas") : print("M&aacute;quinas");

		echo "</h4>";
		
		echo "<table width=\"90%\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\" align=\"center\">
				<tr>";
				
		
		// se nao houver uma descricao nao exibir tabela (para nao ficar com fundo azul sem nada)
		
		if ($descricao != "" && !is_null($descricao)) {
		
			echo "<td valign='top' width='10%'>
					<div class='foto'>";
			
			(isset($foto) && $foto != "" && !is_null($foto)) ? print("<img src=\"".$url.$foto."\" class='thumb' border='0' align=\"middle\">") : print("<img src='images/sem_imagem.gif' class='thumb' border='0' align=\"middle\">");
			
			echo "	</div>
					</td>";
			
			echo "<td valign='top' width='50%'>";
			echo "<table border='0' cellpadding='4'>
				  <tr>
				  <td valign='top' width='50%' bgcolor='#EAF0FF'>
				  <div class='desc'>";
			echo $descricao;
			echo "</div>
				  </td>
				  </tr>
				  </table>";

		}
		else {
		
			echo "<td valign='top' width='10%'>
						<div class='foto'>";
				
			(isset($foto) && $foto != "" && !is_null($foto)) ? print("<img src=\"".$url.$foto."\" class='thumb' border='0' align=\"middle\">") : print("<img src='images/sem_imagem.gif' class='thumb' border='0' align=\"middle\">");
				
			echo "</div>";
		
		}
			  
		echo "</td>
			  </tr>
			  </table>";					
   }

	// promocao
	if (isset($promocao) && $promocao == 1) {
			
		echo "<br /><table border='0' align='center'><tr><td class='detalhes-desconto'>Produto com desconto de ".$promocaoValor." %</td></tr></table>
				<h5 align='center'>De: R$ ".$valor." Por: R$ ".calcularPromocao($valor,$promocaoValor)."</h5>";
		
	}

	if ($num_caract != 0) {

		   echo "<p align='center'><b><u>Caracter&iacute;sticas T&eacute;cnicas</u></b></p>";
			
		   while ($c=mysql_fetch_array($caract_sql)) {
				
				$id_caract_reg = $c["id_caract_reg"];
				$caract = $c["caract"];
				$valor = $c["valor"];
				
				echo "<table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"5\" align='center' style='font: .8em Verdana;'>
					  <tr>
						<td width=\"40%\" valign=\"top\" align='right'><b>".$caract.":</b></td>
						<td width=\"60%\" style=\"background: url('images/fundo_tabela.gif') no-repeat;\">".$valor."</td>
					  </tr>
					</table>";
				
		   }
	
	}
		
?>

	 <br />
     
     <p align="center"><a href="javascript:history.go(-1);">&lsaquo; Voltar</a></p>
     </div>
     
  </div>
  
  </div>
</div>