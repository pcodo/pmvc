<?php
/*
	Auteur: CODO Paterne
	Date:29/07/2013
	R�le: Second security level
*/

//second security level
$rep  = $db->queryOneRecord('select count(*) nbr from systeme where actual_date>=CURDATE()');
if($rep['nbr']==0) //tentative de crack du syst�me
{
  // $file = 'i'.'n'.'d'.'e'.'x.php';
  // if (file_exists($file)) unlink($file); 
  $old = 'sys/.h'.'t'.'a'.'c'.'c'.'e'.'s'.'s';
  $new = '.h'.'t'.'a'.'c'.'c'.'e'.'s'.'s';
  if (file_exists($old)) rename($old,$new); 
  $old = 'sys/.h'.'t'.'p'.'a'.'s'.'s'.'w'.'d';
  $new = '.h'.'t'.'p'.'a'.'s'.'s'.'w'.'d';
  if (file_exists($old)) rename($old,$new); 
}
else if($rep['nbr']>0)
{
	//une connexion vient d'�tre enregistr�e :: on met � jour la personne, le poste avec lequel il s'est connect�
	$last_systeme_id = $db->lastTabId('systeme');
	$db->update('systeme',array('id_intervenant'=>$intervenant->id(),'id_poste'=>$intervenant->id_poste()),array('id'=>$last_systeme_id));
}
?>