<?php
/*Rôle : Index du projet VS
  Auteur: CODO Paterne, ingénieur en réseaux informatiques et Internet 
  Date de création:19/02/2013
*/
session_start();
header('Access-Control-Allow-Origin: *'); // (Permet de considerer 127.0.0.1 comme localhost et vice versa) Plus de détail sur http://stackoverflow.com/questions/13123167/jquery-load-is-working-till-i-restart-wampserver-and-then-not-anymore
//xdebug_start_trace(NULL, XDEBUG_TRACE_APPEND);
require_once("requirements.php");
$connected = false;


if(isset($_SESSION['id_intervenant']))
{
	$connected = true;
	$db = new DataBase();
	$intervenant = new Intervenant($_SESSION['id_intervenant']);
	if(!isset($_SESSION['magasin'])) $_SESSION['magasin'] = 0;
}
if(!isset($_SESSION['globalYear']))
{
	$_SESSION['globalYear'] = date('Y');
}
?>
<!DOCTYPE html> 
<html> 
<head> 
        <meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1"/>			
		<title><?php echo app_name;?></title>
		<link rel="shortcut icon" href="img/logo.png" />
		<link rel="stylesheet" href="js/bxslider/jquery.bxslider.css" type="text/css" />
        <!-- css perso -->
		<link rel="stylesheet" href="css/style.css" />
		<!--[if lte IE 7]>
		<link rel="stylesheet" href="css/style_ie.css" />
		<![endif]-->
		<!-- inclusion du style CSS de base -->
		<link rel="stylesheet" type="text/css" href="lib/jquery-ui-1.10.1/development-bundle/themes/base/jquery.ui.all.css" />
		<link rel="stylesheet" type="text/css" href="lib/jquery-ui-1.10.1/development-bundle/themes/base/jquery.ui.dataTable.css" />
		<link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.2/extensions/TableTools/css/dataTables.tableTools.css" />
		
		<!--link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.2/media/css/jquery.dataTables.css" /-->
		
		
		<script type="text/javascript" src="lib/jquery-ui-1.10.1/js/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="lib/jquery-ui-1.10.1/js/jquery-ui-1.10.1.js"></script>
		<!--script type="text/javascript" src="lib/jquery-ui-1.10.1/development-bundle/ui/jquery.dataTables.js"></script -->
		<script type="text/javascript" src="lib/DataTables-1.10.2/media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" src="lib/DataTables-1.10.2/extensions/TableTools/js/dataTables.tableTools.js"></script>
		<script type="text/javascript" src="lib/jquery-ui-1.10.1/development-bundle/ui/i18n/jquery.ui.datepicker-fr.js"></script>
		
		<!-- inclusion du jquery pagination -->
		<script type="text/javascript" src="js/jquery.simplePagination.js"></script>
		<link rel="stylesheet" type="text/css" href="css/simplePagination.css">
		
		<!-- inclusion du bootstrap -->
		<!--script type="text/javascript" src="css/bootstrap/js/bootstrap.js"></script -->
		<!-- link rel="stylesheet" type="text/css" href="css/bootstrap/css/bootstrap.css" -->
		

		
		<!-- Inclusion de FancyBox -->
		<!-- Add mousewheel plugin (this is optional) -->
		<script type="text/javascript" src="lib/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

		<!-- Add fancyBox main JS and CSS files -->
		<script type="text/javascript" src="lib/fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
		<link rel="stylesheet" type="text/css" href="lib/fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

		<!-- Add Button helper (this is optional) -->
		<link rel="stylesheet" type="text/css" href="lib/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
		<script type="text/javascript" src="lib/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

		<!-- Add Thumbnail helper (this is optional) -->
		<link rel="stylesheet" type="text/css" href="lib/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
		<script type="text/javascript" src="lib/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

		<!-- Add Media helper (this is optional) -->
		<script type="text/javascript" src="lib/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
        
		<!-- Add jquery md5 -->
		<script type="text/javascript" src="js/jquery.md5.js"></script>
		<script type="text/javascript" src="js/securedRedirect.js"></script>

		<!-- inclusion du select2 -->
		<script type="text/javascript" src="lib/select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="lib/select2/select2.min.css">
		
		<!-- Add custom js-->
		<script type="text/javascript" src="js/js_index.js"></script>
		<script type="text/javascript" src="js/js_alerte.js"></script>
		<script type="text/javascript" src="js/dataTable_init.js"></script>

		<!-- bx slider -->
		<script src="js/bxslider/jquery.bxslider.min.js"></script>
		<script src="js/bxslider/bx_slider_launch.js"></script>
			   
</head> 
<body> 

<div id="page">
        <div id="header" style="border:1px solid white;">
            <img src="img/logo.png" class="logo"/><div class="app_title"><?php echo app_title;?></div>
			<?php if($connected){?>
			<form>
				<?php
				$waiting_d_records = Demande::notViewedUserDemandesAsRecords($intervenant->id());
				$count_waiting_d_records = count($waiting_d_records);
				if($count_waiting_d_records>0)
				{
					echo '<span class="thin-notifier radius zone-color-red"><a href="index.php?m=12&sm=64" style="background: inherit;text-decoration: none;" class="redirect">'.$count_waiting_d_records.'</a></span>';
				}
				?>
				
				<a href="index.php" class="b_home" style="">Accueil</a>
				<select name="globalYear" title="Excercice">	
								
					<?php
					    echo '<option value="'.date('Y').'"> -- </option>';
						$years = Systeme::getRecordYears();
						foreach ($years as $key => $year) {
							if(isset($_SESSION['globalYear'])&&$_SESSION['globalYear']==$year)
								echo '<option selected="selected" value="'.$year.'">'.$year.'</option>';
							else
								echo '<option value="'.$year.'">'.$year.'</option>';
						}
					?>
				</select>
				<select name = "sessionInfo" title="<?php echo $intervenant->poste();?>">
					<option value="0"><?php echo $intervenant->nom().' '.$intervenant->prenom();?></option>
					<option value="1">Mot de passe</option>
					<option value="2">Déconnexion</option>
				</select>
			</form>
			<?php } ?>
			
	    </div><!-- /header -->
		<?php 
			if($connected)
			{
				include('includes/connected.php');
			}
			else
			{
				include('includes/connexion.php');
			}
			
		?>
		<div id="footer">			
			<fieldset>
			<legend>DIP\MEEM&copy;2018</legend>
			<ul>
				<li title="">Contacts</li>
				<li title="">Services</li>
			</ul>
			</fieldset>
		</div><!-- /footer -->
</div><!-- /page -->
</body>
</html>