<style>
#content
{
	min-height:200px;
}
</style>
<?php
/*RÙle : Index du projet VS
  Auteur: CODO Paterne, ingÈnieur en rÈseaux informatiques et Internet 
  Date de crÈation:19/02/2013
*/
?>
	<div id="tab_connexion">
		<h3 style="border:none;"> 
			<?php
				if(isset($deconnexion)&&$deconnexion=='ok')
				{
				  echo 'Merci de votre passage !';
				}
				else
				{
				  echo 'Veuillez vous authentifier';
				}
			?>
			
		</h3>
		<div class="tab_content">
			
		</div>
	</div>
	<div id="wrap">
					
		<div id="left_menu">
			  
		</div>
		<div id="right_menu">
			
		</div>
	    <div id="content" style="background-color:rgb(240,240,240);padding-top:100px;">
		 	<?php if(!isset($deconnexion)){
				if(isset($_POST['login']))
				{
				 echo '<h3 style="color:red"> Vos identifiants sont incorrects !</h3>';
				}
			?>
			<?php if(isset($_GET['sec_1'])&&$_GET['sec_1']==0){	?>
					<div id="grace_expired_warner">
						<p style="font-weight:bold;"> Vos identitifants de connexion ont expir√©! Veuillez vous adresser √† l'administrateur du syst√®me pour les r√©activer. Merci!</p>
					</div>
			<?php } ?>   
				<form name="authentification" method="post" action="includes/connexion_post.php">
					<table cellspacing="20" style="border:thin inset;border-top:thin outset;border-left:thin outset;background-color:rgb(30,100,100);color:white;">
						<tr>
							<td><label for="login">Login </label></td>
							<td><input type="text" name="login" id="login" size="30"/></td>
						</tr>
						<tr>
							<td><label for="mdp">Mot de passe </label></td>
							<td><input type="password" name="mdp" id="mdp" size="30"/></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td style="text-align:right;"><input type="submit" name="valider" value="Valider"/></td>
						</tr>
					</table>
				</form>
			<?php }?>
		</div>
	</div><!-- /wrap -->
	