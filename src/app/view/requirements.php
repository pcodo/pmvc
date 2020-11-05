<?php
/*Rôle : 
  Auteur: CODO Paterne, ingénieur en réseaux informatiques et Internet 
  Date de création:19/02/2013
*/
session_start();

require_once("../../../../../requirements.php");

if(isset($_SESSION['id_intervenant']))
{	
	$db = DataBase::getInstance();
	$intervenant = new Intervenant($_SESSION['id_intervenant']);
}
else exit();

?>
<link rel="stylesheet" type="text/css" href="../../../../../lib/jquery-ui-1.10.1/development-bundle/themes/base/jquery.ui.all.css" />
<link rel="stylesheet" type="text/css" href="../../css/style.css" />
<script type="text/javascript" src="../../../../../lib/jquery-ui-1.10.1/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../../../../../lib/jquery-ui-1.10.1/js/jquery-ui-1.10.1.js"></script>
<script type="text/javascript" src="../../../../../lib/jquery-ui-1.10.1/development-bundle/ui/i18n/jquery.ui.datepicker-fr.js"></script>
<script type="text/javascript" src="../../../../../lib/select2/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../../../../lib/select2/select2.min.css">
<script>
	$(function(){
		jQuery.datepicker.setDefaults(jQuery.datepicker.regional['fr']);
		$( ".dateField" ).datepicker();
		$(".select2").select2();
		// Gestion des select hide et show
		$('.hider_select').change(function(){
		   if($(this).val()==0||$(this).val()=="N")
		   {
			 $('.'+$(this).attr('name')).show();
		   }
		   else
		   {
			 $('.'+$(this).attr('name')).hide();
		   }
		});	

		
		
	});
</script>
<style>
	*{
		font-size: 14px;
	}
</style>