<?php
/*
  Formulaire de configuration du systeme
  Auteur: CODO Paterne
  
*/
$id_site = 0;
$site = $db->queryOneRecord('select * from site where localisation_systeme = 0 order by id desc limit 0,1');
if($site!=null && isset($site['id']))
{
	$id_site = $site['id'];
}
$last_static_params = $db->queryOneRecord('select * from static_params order by id desc limit 0,1');
?>
<script>
	$(function(){
		$('input[name=active_sm_access_psswd]').click(function(){
			if($(this).is(':checked'))
			{
				$('.chg_passwd_zone').show();
			}
			else
			{
				$('.chg_passwd_zone').hide();
			}
		});
	});
</script>
<h3> Information du site local du système </h3>
<div align = "center" class="radius">
	<?php
		if(isset($site['logo'])&&$site['logo']!='') echo '<img class="img_1" src = "'.$site['logo'].'"/>';
	?>
	<form action="" method="post" enctype="multipart/form-data">
		<fieldset>
			<legend>Options </legend>
			<table class="form_table">
				<tr>
					<td><label for="nom_site"> Nom du site </label></td>
					<td title="Nom du site ou entreprise possedant l'application"> <input type="text" name="nom_site" id="nom_site" value="<?php if(isset($_POST['nom_site'])) echo $_POST['nom_site']; else if(isset($site['nom'])) echo $site['nom']; ?>" size="50"/> </td>
				</tr>
				<tr>
					<td><label for="telephone_site"> Téléphone </label></td>
					<td title="Nom du site ou entreprise possedant l'application"> <input type="text" name="telephone_site" id="telephone_site" value="<?php if(isset($_POST['telephone_site'])) echo $_POST['telephone_site']; else if(isset($site['telephone'])) echo $site['telephone']; ?>" size="50"/> </td>
				</tr>	
				<tr>
					<td><label for="mail_site"> Mail </label></td>
					<td title="Adresse mail du site ou entreprise possedant l'application"> <input type="text" name="mail_site" id="mail_site" value="<?php if(isset($_POST['mail_site'])) echo $_POST['mail_site']; else if(isset($site['mail'])) echo $site['mail']; ?>" size="50"/> </td>
				</tr>	
				<tr>
					<td><label for="adresse_site"> Adresse </label></td>
					<td title="Nom du site ou entreprise possedant l'application"> <input type="text" name="adresse_site" id="adresse_site" value="<?php if(isset($_POST['adresse_site'])) echo $_POST['adresse_site']; else if(isset($site['adresse'])) echo $site['adresse']; ?>" size="50"/> </td>
				</tr>	
				<tr>
					<td><label for="logo_site"> Logo </label></td>
					<td title="logo du site ou possedant l'application"> <input type="file" name="logo_site" /> </td>
				</tr>	
				<tr>
					<td><label for="url_site"> URL DU SITE </label></td>
					<td title="adresse complète de l'application sur site accessible à distance"> <input type="text" name="url_site" id="url_site" value="<?php if(isset($_POST['url_site'])) echo $_POST['url_site']; else if(isset($site['url_systeme'])) echo $site['url_systeme']; ?>" size="50"/> </td>
				</tr>
				<tr>
					<td><label for="site_web"> Site web </label></td>
					<td title="Url du site web de l'organisation"> <input type="text" name="site_web" id="site_web" value="<?php if(isset($_POST['site_web'])) echo $_POST['site_web']; else if(isset($site['site_web'])) echo $site['site_web']; ?>" size="50"/> </td>
				</tr>
				<tr>
					<td><label for="ifu"> IFU </label></td>
					<td title="Identifiant Fiscal Unique"> <input type="text" name="ifu" id="ifu" value="<?php if(isset($_POST['ifu'])) echo $_POST['ifu']; else if(isset($site['ifu'])) echo $site['ifu']; ?>" size="50"/> </td>
				</tr>
				<tr>
					<td><label for="rccm"> RCCM </label></td>
					<td title="RCCM de l'organisation"> <input type="text" name="rccm" id="rccm" value="<?php if(isset($_POST['rccm'])) echo $_POST['rccm']; else if(isset($site['rccm'])) echo $site['rccm']; ?>" size="50"/> </td>
				</tr>
				<tr>
					<td><label for="secteur"> Secteur d'activité </label></td>
					<td title="Enumérer les services ainsi que les types de produits vendus"> <input type="text" name="secteur" id="secteur" value="<?php if(isset($_POST['secteur'])) echo $_POST['secteur']; else if(isset($site['secteur'])) echo $site['secteur']; ?>" size="50"/> </td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend> Validation </legend>
			<table cellspacing="10" width="100%" style="text-align:right;">
				<tr>
					<td>
						<input type="submit" name="valider_site_info" value="Valider"/>
						<input type="reset" name="annuler" value="Annuler"/>
					</td>
				</tr>				
			</table>
		</fieldset>
	</form>
</div>


<h3> Ergonomie du système </h3>
<div align = "center" class="radius">
	<?php
		if(isset($site['logo'])&&$site['logo']!='') echo '<img class="img_1" src = "'.$site['logo'].'"/>';
	?>
	<form action="" method="post" enctype="multipart/form-data">
		<fieldset>
			<legend>Affichage d'infos bulles </legend>
			<table class="form_table">
				<tr>
					<td><label for="activate_cate_info"><span style="color:red;">*</span>Info bulle sur toutes les catégories</label></td> 
					<td> 
						Activer <input type="radio" name="activate_cate_info"  value="1" <?php if((isset($_POST['activate_cate_info']) && $_POST['activate_cate_info'] == 1)) echo 'checked="checked"'; ?>/>
						Désactiver <input type="radio" name="activate_cate_info" value="0" <?php if((isset($_POST['activate_cate_info']) && $_POST['activate_cate_info'] == 0))echo 'checked="checked"'; ?>/>
					</td>
					
				</tr>
				<tr>
					<td><label for="activate_menu_info"><span style="color:red;">*</span>Info bulle sur tous les menus</label></td> 
					<td> 
						Activer <input type="radio" name="activate_menu_info"  value="1" <?php if((isset($_POST['activate_menu_info']) && $_POST['activate_menu_info'] == 1)) echo 'checked="checked"'; ?>/>
						Désactiver <input type="radio" name="activate_menu_info" value="0" <?php if((isset($_POST['activate_menu_info']) && $_POST['activate_menu_info'] == 0))echo 'checked="checked"'; ?>/>
					</td>
				</tr>	
				<tr>
					<td><label for="activate_sm_info"><span style="color:red;">*</span>Info bulle sur tous les sous-menus</label></td> 
					<td> 
						Activer <input type="radio" name="activate_sm_info"  value="1" <?php if((isset($_POST['activate_sm_info']) && $_POST['activate_sm_info'] == 1)) echo 'checked="checked"'; ?>/>
						Désactiver <input type="radio" name="activate_sm_info" value="0" <?php if((isset($_POST['activate_sm_info']) && $_POST['activate_sm_info'] == 0))echo 'checked="checked"'; ?>/>
					</td>
				</tr>
				
			</table>
		</fieldset>
		<fieldset>
			<!--legend>STOCK</legend -->
			<?php
				
				$rupture_alerte_stop = isset($last_static_params['rupture_alerte_stop'])?$last_static_params['rupture_alerte_stop']:0;
					
			?>
			<table class="form_table hidden">
				<tr>
					<td><input type="checkbox" name="rupture_alerte_stop" <?php if($rupture_alerte_stop==0) echo 'checked="checked"';?>/></td>
					<td title="Activer ou désactiver l'affichage des produits en rupture dans la liste des produits en boutique!"> <?php echo $rupture_alerte_stop==0 ?'Activer':'Désactiver'; ?> l'affichage des produits en rupture dans la liste des produits en boutique</td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend>Activation de certaines fonctionnalités </legend>
			<?php
				$active_magasin_zone = isset($last_static_params['active_magasin_zone'])?$last_static_params['active_magasin_zone']:0;
				$active_alerte_zone= isset($last_static_params['active_alerte_zone'])?$last_static_params['active_alerte_zone']:0;
				$active_alerte_mail_state= isset($last_static_params['active_alerte_mail_state'])?$last_static_params['active_alerte_mail_state']:0;
				$active_sm_access_psswd= isset($last_static_params['sm_access_psswd'])?$last_static_params['sm_access_psswd']:'';
				$disable_default_password_usage = isset($last_static_params['disable_default_password_usage'])?$last_static_params['disable_default_password_usage']:0;
			?>
			<table class="form_table">
				<tr>
					<td><input type="checkbox" name="active_alerte_zone" <?php if($active_alerte_zone==1) echo 'checked="checked"';?>/></td>
					<td title="Activer ou désactiver l'affichage des alertes système!"> <?php echo $active_alerte_zone==0 ?'Activer':'Désactiver'; ?> l'affichage de la zone d'alerte</td>
				</tr>
				<tr>
					<td><input type="checkbox" name="active_alerte_mail_state" <?php if($active_alerte_mail_state==1) echo 'checked="checked"';?>/></td>
					<td title="Activer ou désactiver l'envoi des alertes par mails!"> <?php echo $active_alerte_mail_state==0 ?'Activer l\'envoi des alertes par mails. <span style="color:pink;">Attention, la connexion Internet est nécéssaire pour cette fonctionnalité!</span>':'Désactiver l\'envoi des alertes par mails.'; ?>  </td>
				</tr>
				<tr class="hidden">
					<td><input type="checkbox" name="active_magasin_zone" <?php if($active_magasin_zone==1) echo 'checked="checked"';?>/></td>
					<td title="Activer ou désactiver la gestion des magasins"> <?php echo $active_magasin_zone==0 ?'Activer':'Désactiver'; ?> l'affichage de la zone de sélection des magasins</td>
				</tr>
				<tr>
					<td><input type="checkbox" name="disable_default_password_usage" <?php if($disable_default_password_usage==1) echo 'checked="checked"';?>/></td>
					<td title=""> Désactiver l'utilisation du mot de passe par défaut</td>
				</tr>												
			</table>
			<table class="form_table">
				
				<tr>
					<td title="Mot de passe d'accès aux rubriques critiques"><label for="df_sm_access_psswd">MOT DE PASSE</label></td>
					<td><input type="password" name="df_sm_access_psswd" id="df_sm_access_psswd" size="30"/></td>
				</tr>
				<?php if($last_static_params['sm_access_psswd']!=''){?>
				<tr>
					<td><input type="checkbox" name="active_sm_access_psswd" <?php if($active_sm_access_psswd!='') echo 'checked="checked"';?>/></td>
					<td> Protection des rubriques critiques par un mot de passe</td>
				</tr>
				<tr class="chg_passwd_zone">
					<td><label for="chg_sm_access_psswd">NOUVEAU MOT DE PASSE</label></td>
					<td><input type="password" name="chg_sm_access_psswd" id="chg_sm_access_psswd" size="30"/></td>
				</tr>
				
				<?php }?>
				<tr class="chg_passwd_zone">
					<td><label for="cnf_sm_access_psswd">CONFIRMER</label></td>
					<td><input type="password" name="cnf_sm_access_psswd" id="cnf_sm_access_psswd" size="30"/></td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend> Validation </legend>
			<table cellspacing="10" width="100%" style="text-align:right;">
				<tr>
					<td>
						<input type="submit" name="valider_ergo_params" value="Valider"/>
						<input type="reset" name="annuler" value="Annuler"/>
					</td>
				</tr>				
			</table>
		</fieldset>
	</form>
</div>

<?php
	// Traitement de la validation des informations du site
  if(isset($_POST['valider_site_info']))
  {
		$nom = isset($_POST['nom_site'])?$db->escape($_POST['nom_site']):'';
		$telephone = isset($_POST['telephone_site'])?$db->escape($_POST['telephone_site']):'';
		$mail = isset($_POST['mail_site'])?$db->escape($_POST['mail_site']):'';
		$adresse = isset($_POST['adresse_site'])?$db->escape($_POST['adresse_site']):'';
		$url_systeme = isset($_POST['url_site'])?$db->escape($_POST['url_site']):'';
		$site_web = isset($_POST['site_web'])?$db->escape($_POST['site_web']):'';
		$ifu = isset($_POST['ifu'])?$db->escape($_POST['ifu']):'';
		$rccm = isset($_POST['rccm'])?$db->escape($_POST['rccm']):'';
		$secteur = isset($_POST['secteur'])?$db->escape($_POST['secteur']):'';
		$localisation_systeme = 0;// site locale
		$logo = '';
		if($id_site>0)
		{// modification
			$db->update('site',array('nom'=>$nom,'telephone'=>$telephone,'mail'=>$mail,'adresse'=>$adresse,'url_systeme'=>$url_systeme,'site_web'=>$site_web,'ifu'=>$ifu,'rccm'=>$rccm,'secteur'=>$secteur),array('id'=>$id_site));
		}
		else
		{// Nouvelle insertion
			if($db->insertion('site','',$nom,$logo,$telephone,$mail,$adresse,$localisation_systeme,$url_systeme,$site_web,$ifu,$rccm,$secteur))
			{
				$id_site = $db->lastTabId('site');
			}
		}
		
		// Mise à jour de l'url du logo du site si possible
		$extensions_autorisees = array('jpg','JPG', 'jpeg', 'gif','PNG','png');
		$file_url = 'uploads/systeme/logo_site'.$id_site; 
		$upload_state = Systeme::upload_file('logo_site', $file_url, $extensions_autorisees, '100000');
		if ($upload_state['etat'] == 'ok')
		{
			$file_url = $upload_state['saved_file_url'];
			$db->update('site',array('logo'=>$file_url),array('id'=>$id_site));
		}
		
		// redirection
		echo '<script language="javascript">document.location.href="index.php?m='.$_GET['m'].'"</script>';		
  }
  
  	// Traitement de la validation des informations du site
  if(isset($_POST['valider_ergo_params']))
  {
		$activate_cate_info = isset($_POST['activate_cate_info'])?$db->escape($_POST['activate_cate_info']):0;
		$activate_menu_info = isset($_POST['activate_menu_info'])?$db->escape($_POST['activate_menu_info']):0;
		$activate_sm_info = isset($_POST['activate_sm_info'])?$db->escape($_POST['activate_sm_info']):0;
		
		Systeme::initInfoBulleState($activate_cate_info,$activate_menu_info,$activate_sm_info);
		
		$disable_default_password_usage = isset($_POST['disable_default_password_usage'])?1:0;
		// Gestion de l'activation ou désactivation de certaine zones!
		$active_magasin_zone = isset($_POST['active_magasin_zone'])?1:0;
		$active_alerte_zone = isset($_POST['active_alerte_zone'])?1:0;
		$active_alerte_mail_state = isset($_POST['active_alerte_mail_state'])?1:0;
		$rupture_alerte_stop = isset($_POST['rupture_alerte_stop'])?0:1;
		$df_sm_access_psswd = (isset($_POST['df_sm_access_psswd']))?$db->escape($_POST['df_sm_access_psswd']):'';
		$chg_sm_access_psswd = isset($_POST['chg_sm_access_psswd'])?$db->escape($_POST['chg_sm_access_psswd']):'';
		$cnf_sm_access_psswd = isset($_POST['cnf_sm_access_psswd'])?$db->escape($_POST['cnf_sm_access_psswd']):'';
		$update_sm_accs_psswd = false;
		if(isset($_POST['active_sm_access_psswd'])&&$chg_sm_access_psswd!=''&&$chg_sm_access_psswd==$cnf_sm_access_psswd&&$last_static_params['sm_access_psswd']==$df_sm_access_psswd)
		{
			$df_sm_access_psswd = $chg_sm_access_psswd;
			$update_sm_accs_psswd = true;
		}
		else if($last_static_params['sm_access_psswd']==$df_sm_access_psswd&&!isset($_POST['active_sm_access_psswd']))
		{
			$df_sm_access_psswd = '';
			$update_sm_accs_psswd = true;
		}
		else if($df_sm_access_psswd!=''&&$df_sm_access_psswd==$cnf_sm_access_psswd)
		{
			$update_sm_accs_psswd = true;
		}
		Systeme::setStaticParams($active_magasin_zone,$active_alerte_zone,$active_alerte_mail_state,$rupture_alerte_stop,$df_sm_access_psswd,$update_sm_accs_psswd,$disable_default_password_usage);
		if($active_alerte_mail_state==1)
		{
			Systeme::initMailAlerteSession($active_alerte_mail_state);
		}
		// redirection
		Systeme::redirect('index.php?m='.$_GET['m'].'&sm=39');
			
  }

?>