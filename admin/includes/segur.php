<?php
$permitido_por_role = 0;
if (isset($_SESSION['sess_admin_nivel']))
{
	if (in_array($_SESSION['sess_admin_nivel'], $roles)) { $permitido_por_role = 1; }
} else {
	$_SESSION['sess_admin_login'] = "false";
}

if (($_SESSION['sess_admin_login'] != "true") || ($permitido_por_role == 0)) {
	session_destroy();?>
	<script>
		alert('Acceso no válido')
		top.location.href='../admin/';
	</script>
<?php 
		die();
}
?>
