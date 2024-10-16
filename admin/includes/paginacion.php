<?php 
$rango = 2;
$primero = $num_pagina - $rango;
$ultimo = $num_pagina + $rango;

if ($num_pagina <= $rango + 1 ){ $ultimo = 2*$rango + 1;}
if ($primero < 1 ){ $primero = 1;}
if ($ultimo > $total_paginas) { $ultimo =$total_paginas;}

$pos = strrpos($url, "?");
if ($pos === false) {    $url = $url . "?"; }
else				{    $url = $url . "&"; }

?>


<?php if ($num_pagina > 1) {
	$saltoP = 1;
	if (($num_pagina) < 1) {
		$cual = 1;
	} else {
		$cual = $num_pagina - $saltoP;
	}?>
	<a href="<?php echo $url;?>?pagina=1"><img src="images/paginacioninicio.gif" align="absmiddle" alt="Primera p&aacute;gina" border="0" /></a><a href="<?php echo $url;?>pagina=<?php echo $cual;?>"><img src="images/paginacionmenos.gif" border="0" align="absmiddle" alt="p&aacute;gina anterior" /></a>

	<?php if ($num_pagina > ($rango + 1)){
		echo "...";
	}
}else {?>
	<img src="images/paginacioniniciodes.gif" align="absmiddle"><img src="images/paginacionmenosdes.gif" align="absmiddle">
<?php }?>


<?php for ($i=$primero;$i<=$ultimo;$i++){ 
	if ($i == $num_pagina) {?>
		<span class="paginaActual"><?php echo $i;?></span>
	<?php }else {?>
		<span class="linkPagina"><a href="<?php echo $url;?>pagina=<?php echo $i;?>"><?php echo $i;?></a></span>
	<?php }
}?>


<?php if ($num_pagina < $total_paginas){
	if ($num_pagina < ($total_paginas - $rango)){ echo "...";}

	echo "<a href='" . $url . "pagina=";
	$saltoP = $num_pagina;

	if (($num_pagina + $saltoP) > $total_paginas) {
		echo $total_paginas;
	}else {
		echo $num_pagina + $saltoP;
	}

	echo "'>";
	echo "<img src='images/paginacionmas.gif' border='0' align='absmiddle' alt='";
	echo "p&aacute;gina siguiente' /></a>";
	echo "<a href='" . $url . "pagina=" . $total_paginas . "'><img src='images/paginacionfin.gif' border='0' align='absmiddle' alt='&Uacute;ltima p&aacute;gina'/></a>";
}else {
	echo "<img src='images/paginacionmasdes.gif' border='0' align='absmiddle'><img src='images/paginacionfindes.gif' border='0' align='absmiddle' />";
}?>


<span style="padding-left:15px;">&nbsp;</span>
<select name="regPagina" onChange="location.href='<?php echo $url;?>regPagina='+this.value;" class="form-control" style="width:auto; display:inline-block;">
<?php

$valoresRP = array(10,25,50,100,200,$num_total_registros);
$contador = 1;
foreach ($valoresRP as $item)
{
	?><option value=<?php echo $item;?><?php if ($TAMANO_PAGINA==$item) { echo " selected"; } ?>><?php
	if ($contador==count($valoresRP)) {
		echo "Todos ($num_total_registros)";
	} else {
		echo "de $item en $item";
	}
	$contador = $contador+1;
	?></option><?php
}?>
</select>
