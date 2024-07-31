<?php require_once("php7_mysql_shim.php");
# index.php - pagina principal

/*

Para adicionar novo item na listagem de produtos, va ate a linha (aprox.) 133 e adicione novo item da seguinte forma:
<li><a href="produtos.php?do=pesquisar&campo=tudo&filtro=qualquer&chave=NOMEdoITEMaqui">NOME DO NOVO ITEM AQUI</a></li>

* Faça uma pesquisa em http://www.disbromig.com.br/produtos.php e coloque o nome em chave= de acordo com o resultado, alguns produtos estão em plural outros não.
Os produtos em plural são encontrados mesmo no singular (Ex.: furadeira também encontrará furadeiras)

*/

require("painel/include/func.php");
 
conectar();
 
// consulta promocoes
$promocoes_sql = mysql_query("SELECT * FROM produtos WHERE promocao='1' AND status='1' ORDER BY id DESC LIMIT 1");
 
$num_promocoes = mysql_num_rows(mysql_query("SELECT * FROM produtos WHERE promocao='1' AND status='1'")); // numero de produtos em promocao
 
?>
<div id="contentBox">

	<div id="contentText">
	
	<div id="conteudo">
  <div class="conteudo-texto">
        
     
     <!--	<h2>Texto</h2> -->     
       <p>Bem-vindo  &agrave; Disbromig Ferramentas Pneum&aacute;ticas, 
       nosso campo de atua&ccedil;&atilde;o inclui os setores de Minera&ccedil;&atilde;o, Siderurgia, Fundi&ccedil;&atilde;o, Metalurgia, Eletro-eletr&ocirc;nico, Aliment&iacute;cio, Automobil&iacute;stico e Constru&ccedil;&atilde;o Civil.</p>
       <p>Nosso cat&aacute;logo possui uma enorme variedade de <a href="produtos.php">&iacute;tens</a>.</p>
      <p>Veja, por favor, uma amostragem destes abaixo:</p>
      <ul>
        <li><a href="produtos.php?do=pesquisar&campo=tudo&filtro=qualquer&chave=bomba">Bombas</a></li>
        <li><a href="produtos.php?do=pesquisar&campo=tudo&filtro=qualquer&chave=desincrustador">Desincrustadores</a></li>        
        <li><a href="produtos.php?do=pesquisar&campo=tudo&filtro=qualquer&chave=esmerilhadeira">Esmerilhadeiras</a></li>
        <li><a href="produtos.php?do=pesquisar&campo=tudo&filtro=qualquer&chave=furadeira">Furadeiras</a></li>
                <li><a href="produtos.php?do=pesquisar&campo=tudo&filtro=qualquer&chave=guincho">Guinchos</a></li>
        <li><a href="produtos.php?do=pesquisar&campo=tudo&filtro=qualquer&chave=lixadeira">Lixadeiras</a>

        </li>
        <li><a href="produtos.php?do=pesquisar&campo=tudo&filtro=qualquer&chave=martelete">Marteletes</a></li>
        <li><a href="produtos.php?do=pesquisar&campo=tudo&filtro=qualquer&chave=motor*motorizado">Motores</a></li>
        <li><a href="produtos.php?do=pesquisar&campo=tudo&filtro=qualquer&chave=socador">Socadores</a></li>
        <li><a href="produtos.php?do=pesquisar&campo=tudo&filtro=qualquer&chave=talha*talhadeira">Talhas</a></li>
        <li><a href="produtos.php?do=pesquisar&campo=tudo&filtro=qualquer&chave=trole">Troles</a></li>
        <li><a href="produtos.php?do=pesquisar&campo=tudo&filtro=qualquer&chave=vibrador">Vibradores</a></li>
        <li><a href="produtos.php?do=pesquisar&campo=tudo&filtro=qualquer&chave=acess�rio">Acess&oacute;rios</a></li>
      </ul>
      <p>Podemos ajud&aacute;-lo a buscar o <a href="produtos.php">produto</a> que voc&ecirc; necessita. <a href="informacoes.php">Fale conosco</a>, teremos satisfa&ccedil;&atilde;o em atend&ecirc;-lo com as melhores marcas.</p>
      <p align="center"><img src="images/brobras_logo_mini.jpg" width="130"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
      
    </div>
     
  </div>
	
	     
    </div>
	
</div>
