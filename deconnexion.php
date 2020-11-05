<?php
/*R?le : Index du projet VS
  Auteur: CODO Paterne, ing?nieur en r?seaux informatiques et Internet 
  Date de cr?ation:19/02/2013
*/
session_start();
require_once("conf/config.php");
?>
<!DOCTYPE html> 
<html> 
<head> 
		<meta charset="utf-8" />
        <title><?php echo app_name;?></title> 
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <!-- css perso -->
		<link rel="stylesheet" href="css/style.css" />
		<link rel="shortcut icon" href="img/logo.png" />
		<!--[if lte IE 7]>
		<link rel="stylesheet" href="style_ie.css" />
		<![endif]-->
		<!-- inclusion du style CSS de base -->
		<link rel="stylesheet" type="text/css" href="lib/jquery-ui-1.10.1/development-bundle/themes/base/jquery.ui.all.css" />
		<script type="text/javascript" src="lib/jquery-ui-1.10.1/js/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="lib/jquery-ui-1.10.1/js/jquery-ui-1.10.1.js"></script>
		<script type="text/javascript" src="javascript/js_index.js"></script>
	
</head> 
<body> 

<div id="page">

        <div id="header">
             <img src="img/logo.png" class="logo"/><div class="app_title"><?php echo app_title;?></div>		
			<form>
				<a href="index.php">connexion</a>
			</form>	
	    </div><!-- /header -->
			
		<?php 
			
			if(isset($_SESSION['id_intervenant']))
			{
				session_destroy();
			}
			$deconnexion = 'ok';
			include('includes/connexion.php');
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