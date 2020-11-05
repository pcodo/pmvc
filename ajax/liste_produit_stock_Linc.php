<?php
/*Rôle : Index du projet VS
  Auteur: CODO Paterne, ingénieur en réseaux informatiques et Internet 
  Date de création:19/02/2013
*/
session_start();
require_once("../conf/Systeme.class.php");
require_once("../conf/Intervenant.class.php");
require_once("../conf/DataBase.class.php");
$db = new DataBase();
if(isset($_GET['id_gamme'])&&$_GET['id_gamme']==0) // la liste des gammes de produits 
{
	$id_gamme = $db->escape($_GET['id_gamme']);
	while ($produit = $reponses->fetch()) {
		echo'<tr style="background-color:silver;">';
			echo'<td><input type="checkbox" name="id_'.$produit['id'].'"/></td>';
			echo'<td title="'.$produit['description'].'">'.$produit['designation'].' ['.$produit['code'].'] '.'</td>';
			echo'<td><input type="text" name="qt_'.$produit['id'].'"/></td>';
		echo'</tr>';
		$i++;
	}
}


?>