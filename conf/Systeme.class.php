<?php
/*
  R�le: G�re les sous_menu et options du syst�me
  Auteur: CODO Paterne
  Date de cr�ation:02/03/2013
*/
class Systeme {
	public static $db;
    public function __construct(){
	    
	}
	public static function init($run_webgate_notifier=true)
	{
		self::$db = DataBase::getInstance();
		if(isset($_SESSION['active_alerte_mail_state']))
		{
			if($_SESSION['active_alerte_mail_state']==1)
			{
				if($run_webgate_notifier)
				{
					if(isset($_SESSION['success_connexion_count'])&&$_SESSION['success_connexion_count']>=3)
					{ // Ceci empêchera que le initMailAlerteSession() ne se succede jamais de runWebGateNotifier :: Les deux opérations étant time consuming!
						self::runWebGateNotifier();
					}
				}
				if(isset($_SESSION['success_connexion_count']))
				{
					if($_SESSION['success_connexion_count']>=100)
					{
						self::initMailAlerteSession();
						$_SESSION['success_connexion_count'] = 0;
					}
					else 
					{
						$_SESSION['success_connexion_count']++;
					}
				}
				else $_SESSION['success_connexion_count'] = 0;
			}
			else 
			{
				if(isset($_SESSION['failed_connexion_count']))
				{
					if($_SESSION['failed_connexion_count']>=100)
					{
						self::initMailAlerteSession();
						$_SESSION['failed_connexion_count'] = 0;
					}
					else $_SESSION['failed_connexion_count']++;
				}
				else $_SESSION['failed_connexion_count'] = 0;
			}
		}
	}
	public static function runWebGateNotifier()
	{
		// mis juste pour éviter les erreurs générée en l'absence de cette fonction lorqu'il y a accès à Internet sur le serveur
	}
	public static function initMailAlerteSession()
	{
		if(self::is_connected()) // Test de la connexion à Internet
		{
			$_SESSION['active_alerte_mail_state'] = 1;
		}
		else
		{
			$_SESSION['active_alerte_mail_state'] = 0;
		}
		
	}
	public static function securityCheck()
	{
		// cette variable donne en nombre de mois le temps d'expérimentation du système. 
		// Il ne doit pas depasser 12 mois car il n'y a que 12 mois differents dans une même année
	    $nbr_mois = 12; // nbr de mois d'évaluations
            
		$dates_m = self::$db->queryAllRecords('select MONTH(actual_date) m from systeme');
        // echo count($dates_m);
        // exit(); 
		if(count($dates_m)==0) return false;
		if(count($dates_m)==1)
		{
			$rep = self::$db->queryOneRecord('select actual_date from systeme');
			$date1 = new dateTime($rep['actual_date']);
			$date2 = new dateTime(self::now_date());
           // echo $date1->diff($date2)->days;
           // exit();
			if($date1->diff($date2)->days!=3)
			{
                return false;
			}
		}
		// Block provisoire pour autoriser l'utilisation du système pour une semaine
		// if(count($dates_m)>7)
		// return false; 
		// Fin bloc provisoire!

		if(count($dates_m)>$nbr_mois)
		{
			$mois = array_unique(self::array_key_values($dates_m,'m'));	
			//echo count($mois); exit();
			if(count($mois)>=$nbr_mois)// System has been used for $nbr_mois months!
			return false;
		}
		return self::$db->insertion('systeme','',self::now(),0,0);
	}
	public static function nav_menus($id_poste = 0)
	{
		$nav_menu = array();
		if(is_numeric($id_poste)&&$id_poste>0)
		{
		    $menus = self::$db->queryAllRecords('select * from `menu` where id in (select sm.id_menu from `sous_menu` sm, `poste_sous_menu` psm where sm.id = psm.id_sous_menu and psm.id_poste = '.$id_poste.') and active_state=1');
			$categories = self::$db->query('select * from categorie_menu where active_state=1 order by position asc');
			while($cat = $categories->fetch())
			{
				$cat_menu = array();
				foreach($menus as $m)
				{
				  if($cat['id']==$m['id_categorie'])
				  $cat_menu[] = $m;
				}
				$nav_menu[] = array('id'=>$cat['id'],'designation'=>$cat['designation'],'description'=>$cat['description'],'active_state'=>$cat['active_state'],'show_help'=>$cat['show_help'],'menus'=>$cat_menu);
			}
		}
	    return $nav_menu;		
	}
	public static function menus($id_poste = 0)
	{
	   	$reponse = array();
		$sous_menus = self::$db->queryAllRecords('select * from `sous_menu`');
		if($id_poste==0)
		{
			$menus = self::$db->query('select * from `menu` where active_state=1');
		}
		else if($id_poste>0)
		{
			$menus = self::$db->query('select * from `menu` where active_state=1 and id in (select sm.id_menu from `sous_menu` sm, `poste_sous_menu` psm where sm.id = psm.id_sous_menu and psm.id_poste = '.$id_poste.')');
		}
		while($m = $menus->fetch())
		{
		    $sm_menu = array();
		    foreach($sous_menus as $sm)
			{
			  if($m['id']==$sm['id_menu'])
			  $sm_menu [] = $sm;
			}
			$reponse[] = array('id'=>$m['id'],'designation'=>$m['designation'],'description'=>$m['description'],'active_state'=>$m['active_state'],'show_help'=>$m['show_help'],'sous_menus'=>$sm_menu);
		}
	    return $reponse;
	}
	public static function sous_menus($id_menu=0,$id_poste=0)
	{
		if(!is_numeric($id_menu)||!is_numeric($id_poste)) null;
		$sm = array();
		if($id_menu>0&&$id_poste>0)
		{
			$sm = self::$db->queryAllRecords('select sm.* from `sous_menu` sm where sm.id_menu = '.$id_menu.' and sm.id in (select psm.id_sous_menu from `poste_sous_menu` psm where psm.id_poste='.$id_poste.') order by sm.position');
		}
		else if($id_menu==0&&$id_poste>0)
		{
			$sm = self::$db->queryAllRecords('select sm.* from `sous_menu` sm where sm.id in (select psm.id_sous_menu from `poste_sous_menu` psm where psm.id_poste='.$id_poste.') order by sm.position');
		}
		else if($id_menu>0&&$id_poste==0)
		{
			$sm = self::$db->queryAllRecords('select * from `sous_menu` where id_menu='.$id_menu.' order by sm.position');
		}
		return $sm;
	}
	public static function sm_folder($sm)
	{
	    $sm_items = explode('_',$sm);
		$sub_folder = '';
		if(count($sm_items)!=0)
		{
			$sm = $sm_items[0];
			$sm_info = self::$db->queryOneRecord('select sm.* from sous_menu sm where sm.id = '.$sm);
			switch($sm_items[count($sm_items)-1])
			{
				case 'form':
					$sub_folder = '/view/form/m'.$sm_info['id_menu'];
				break;
				case 'entity':
				case 'ent':
					$sub_folder = '/entity';
				break;
				case 'popup':
					$sub_folder = '/view/popup/m'.$sm_info['id_menu'];
				break;
				case 'pdf':
					$sub_folder = '/view/pdf/m'.$sm_info['id_menu'];
				break;
				default:
					$sub_folder = '';
			}
		
		}
		if(!is_numeric($sm) && count($sm_items)==1)
		{
			return '';
		}

		$type_sm = self::$db->queryOneRecord('select t.* from type_sm t join sous_menu sm on sm.id_type_sm = t.id and sm.id = '.$sm);
		return strtolower($type_sm['folder'].$sub_folder);
	}
	public static function sm_dir($sm)
	{
	  return 'src/'.strtolower(self::sm_folder($sm)).'/';
	}
	public static function sm_path($sm,$params = array())
	{
	  $url = self::sm_dir($sm).$sm.'.php';
	  $i = 0;
	  foreach ($params as $key => $value) {
	  	if($i==0 && !self::str_contains($url,'?'))
	  		$url.='?'.$key.'='.$value;
	  	else
	  		$url.='&'.$key.'='.$value;  
	  }
	  return $url;
	}
	public static function buildUrl($m,$sm,$params=array())
    {
       $url = 'index.php?m='.$m.'&sm='.$sm;
       foreach ($params as $key => $value) {
	  	$url.='&'.$key.'='.$value;  
	  }	 
	  return $url;
    }

	public static function str_contains($str, $search)
	{
		return strpos(strtolower($str),$search)!==false;
	}
	public static function afterSpaceFirstCharacterToUpper($str)
	{
		$str_items = explode(' ', $str);
		$str = '';
		$first = true;
		foreach ($str_items as $key => $value) {
			if(trim($value)!='')
			{
				if($first)$str.=ucfirst($value);
				else $str.=' '.ucfirst($value);
			}
			$first = false;
		}

		return $str;
	}
	public static function hasMenu($id_poste,$id_menu)
	{
		
	}
	public static function hasSousMenu($id_poste,$id_sous_menu)
	{
		$sm = self::$db->queryOneRecord('select count(id_sous_menu) nbr from `poste_sous_menu` where id_sous_menu='.$id_sous_menu.' and id_poste='.$id_poste);
		if($sm['nbr']>0) return true;
		else return false;
	}
	public static function login($login,$mdp)
	{
		return self::$db->queryOneRecord('select * from intervenant where login="'.self::$db->escape($login).'" and mdp="'.self::$db->escape($mdp).'"');
	}
	
	// retourne le tableau des �lement de la cl� du tableau envoy�
	public static function array_key_values($array_tab,$key)
	{
		$rep = array();
		foreach($array_tab as $tab)
		{
			if(isset($tab[$key]))
				$rep[]= $tab[$key];
		}
		return $rep;
	}
    public static function array_key_macth_data($array_tab,$key,$key_value)
    {
    	$rep = null;
    	foreach($array_tab as $tab)
		{
			if(isset($tab[$key]) && $tab[$key]==$key_value)
			{
				$rep = $tab;
				break;
			}
		}
		return $rep;
    }
    public static function array_key_macth_data_index($array_tab,$key,$key_value,$index)
    {
    	$rep = 0;
    	foreach($array_tab as $tab)
		{
			if(isset($tab[$key]) && $tab[$key]==$key_value)
			{
				$rep = $tab[$index];
				break;
			}
		}
		return $rep;
    }

	public function sum_numeric_array_tab($tab = array())
	{
		$total = 0;
		foreach ($tab as $key => $value) {
			$total+=$value;
		}
		return $total;
	}
	public static function updatePosteSousMenu($id_poste,$new_array_menus)
	{
	    if(is_numeric($id_poste)&&$id_poste>0)
		{
			self::$db->startTransaction();
			self::$db->execute('delete from `poste_sous_menu` where id_poste='.$id_poste);// suppression de tous les sous_menu du poste concern�
			foreach($new_array_menus as $id_sm)
			{
				self::$db->insertion('poste_sous_menu',$id_sm,$id_poste);
			}
			self::$db->commit();
		}
	}
	public static function now($yfmt = "-", $hfmt = ":", $separator = " ") { // g�re l'instant pr�sent en date time : //2010-04-02 15:28:22 format de sortie, format anglais
        $jour = date('d');
        $mois = date('m');
        $annee = date('Y');

        $heure = date('H');
        $minute = date('i');
        $seconde = date('s');

        $dateTime = $annee . $yfmt . $mois . $yfmt . $jour . $separator . $heure . $hfmt . $minute . $hfmt . $seconde . $hfmt ;
        //$dateTime = $annee . $yfmt . $mois . $yfmt . $jour ;
        return $dateTime;
    }
	public static function now_date() {
        $jour = date('d');
        $mois = date('m');
        $annee = date('Y');
        return $annee . '-' . $mois . '-' . $jour;
    }
    public static function datePreviousToNowDate($date_en)
    {
    	$date_en_obj = new dateTime($date_en);
    	$date_now = new dateTime(Systeme::now_date());
		return $date_en_obj>$date_now;
    }

    public static function dateToFrench($date) { // re�oit une date au format anglais qu'il transforme en format fran�ais 2012-03-04 ==>04/03/2012
        $items = explode('-', $date);
        if (count($items) != 3)
            return $date;
        $annee = $items[0];
        $mois = $items[1];
        $jour = $items[2];
        return $jour . "/" . $mois . "/" . $annee;
    }
    public static function dateTimeToFrench($date_time) {
        $items = explode(' ', $date_time);
		if(count($items)!=2)
		return $date_time;
        $item1 = explode('-', $items[0]);
        $item2 = explode(':', $items[1]);
		if(count($item1) != 3&&count($item2) != 3)
            return $date_time;
        $annee = $item1[0];
        $mois = $item1[1];
        $jour = $item1[2];

        $heure = $item2[0];
        $minute = $item2[1];
        $seconde = $item2[2];

        $frenchDate = $jour . '/' . $mois . '/' . $annee . ' à ' . $heure . 'h ' . $minute . 'mn ' . $seconde.'s';
        return $frenchDate;
    }
    public static function sort2d_bycolumn($array, $column, $method, $has_header)
	{
	  if ($has_header)  $header = array_shift($array);
	  $narray = array();
	  foreach ($array as $key => $row) {
		$narray[$key]  = $row[$column]; 
	  }
	  array_multisort($narray, $method, $array);
	  
	  if ($has_header) array_unshift($array, $header);
	  
	  return $array;
	}
    public static function getdateyear($date_time) 
	{
        $items = explode(' ', $date_time);
		if(count($items)!=2)
		return $date_time;
        $item1 = explode('-', $items[0]);
        $item2 = explode(':', $items[1]);
		if(count($item1) != 3&&count($item2) != 3)
            return $date_time;
        $annee = $item1[0];
        $mois = $item1[1];
        $jour = $item1[2];

        $heure = $item2[0];
        $minute = $item2[1];
        $seconde = $item2[2];

        //$frenchDate = $jour . '/' . $mois . '/' . $annee . ' &agrave; ' . $heure . 'h ' . $minute . 'mn ' . $seconde.'s';
        return $annee;
    }
    public static function formatAmount($montant)
	{
		return number_format($montant,0,',',' ');
	}
        
	public static function dateToEnglish($date) {
         $split = preg_split("#/#", $date);
		if(count($split)!=3)
		 return '';
        $date2 = $split[2];
        $date1 = $split[1];
        $date0 = $split[0];
        return $date2 . "-" . $date1 . "-" . $date0;
    }
	public static function getFileNameFromUrl($url,$url_separator='/')
	{
		$tab = explode($url_separator,$url); // divise la chaine en tableau :0=>nom, 1=>extension
		return $tab[count($tab)-1];
	}
	// retourne un tableau : tab[0] renvoie l'etat(ok,error,error_extension..), tab[1] contient l'extension du fichier enregistre
    public static function upload_file($htmlIdentifier, $save_repertory, $admitted_extensions, $max_size) {
        $retour = array('etat' => '', 'extension' =>'','saved_file_url' =>'');
        if (isset($_FILES[$htmlIdentifier]) and $_FILES[$htmlIdentifier]['error'] == 0) {

            if ($_FILES[$htmlIdentifier]['size'] <= $max_size) {
                $infosfichier = pathinfo($_FILES[$htmlIdentifier]['name']);
                $extension_upload = strtolower($infosfichier['extension']);
                if (in_array($extension_upload, $admitted_extensions)) { // si le fichier a une bonne extension
                    $nom_image = '' . $save_repertory . '.' . $extension_upload;
                    move_uploaded_file($_FILES[$htmlIdentifier]['tmp_name'], $nom_image);
                    $retour['etat'] = 'ok';
                    $retour['extension'] = $extension_upload;
                    $retour['saved_file_url'] = $nom_image;
                } else {
                    $retour['etat'] = 'extension_error';
                }
            } else {
                $retour['etat'] = 'size_error';
            }
        } else {
            $retour['etat'] = 'error';
        }

        return $retour;
    }
	
	public static function monthList()
	{
		return array('1'=>'Janvier','2'=>'Février','3'=>'Mars','4'=>'Avril','5'=>'Mai','6'=>'Juin','7'=>'Juillet','8'=>'Août','9'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Décembre');
	}
	public static function monthNameFromNum($numero)
	{
	    $months = self::monthList();
		return $months[$numero];
	}
	public static function directionList()
	{

	}
	public static function posteList($chef_direction=false)
	{
		if($chef_direction)
		{
			$postes = self::$db->queryAllRecords('select p.*, inter.id id_inter from poste p left join intervenant inter on inter.id_poste = p.id where p.chef_direction_state=1 ORDER BY p.designation ASC');
		}
		else
		{
			$postes = self::$db->queryAllRecords('select p.* from poste p ORDER BY p.designation ASC');
		}
		return $postes;
	}
	public static function userList($direction=0,$connected = false, $superUserIncluded=false)
	{
		$requete = 'select inter.*, p.designation poste,d.designation direction from intervenant inter, poste p left join direction d on p.id_direction = d.id where inter.id_poste=p.id';
		
		if($direction>0) $requete.=' and d.id = '.$direction;
		
		if($connected) $requete.=' and inter.status = 2';
		
		if($superUserIncluded&&$connected) $requete.=' and inter.status = 4';
		else if(!$superUserIncluded) $requete.=' and inter.status != 4';
		
		$requete.=' ORDER BY inter.nom, inter.prenom ASC';
		$intervenants = self::$db->queryAllRecords($requete);
		return $intervenants;
	}
	public static function adminList()
	{
		$requete = 'select inter.*, p.designation poste,d.designation direction from intervenant inter, poste p left join direction d on p.id_direction = d.id where inter.id_poste=p.id and status=4';
		return self::$db->queryAllRecords($requete);
	}
	public static function exUserlist()
	{
		$requete = 'select inter.*, p.designation poste,d.designation direction from intervenant_ex inter, poste p left join direction d on p.id_direction = d.id where inter.id_poste=p.id';
		return self::$db->queryAllRecords($requete);
	}
	
	public static function setStaticParams($active_magasin_zone,$active_alerte_zone,$active_alerte_mail_state,$rupture_alerte_stop,$sm_access_psswd='',$reinit_psswd=false,$disable_default_password_usage=true)
	{
	    if(self::$db->countTabRows('static_params')==0)
		{
			$auto_active_alerte_mail_state = 0;
			self::$db->insertion('static_params','',$active_magasin_zone,$active_alerte_zone,$sm_access_psswd,$active_alerte_mail_state,$rupture_alerte_stop,$disable_default_password_usage);
		}
		else
		{
		    $last_static_params = self::$db->queryOneRecord('select * from static_params order by id desc limit 0,1');
			if($reinit_psswd)
				self::$db->update('static_params',array('active_magasin_zone'=>$active_magasin_zone,'active_alerte_zone'=>$active_alerte_zone,'active_alerte_mail_state'=>$active_alerte_mail_state,'rupture_alerte_stop'=>$rupture_alerte_stop,'sm_access_psswd'=>$sm_access_psswd,'disable_default_password_usage'=>$disable_default_password_usage),array('id'=>$last_static_params['id']));
			else
				self::$db->update('static_params',array('active_magasin_zone'=>$active_magasin_zone,'active_alerte_zone'=>$active_alerte_zone,'active_alerte_mail_state'=>$active_alerte_mail_state,'rupture_alerte_stop'=>$rupture_alerte_stop,'disable_default_password_usage'=>$disable_default_password_usage),array('id'=>$last_static_params['id']));
		}
		//self::$db->update('stock',array('rupture_alerte_stop'=>$rupture_alerte_stop));
	}

	public static function staticParams()
	{
		return self::$db->queryOneRecord('select * from static_params order by id desc limit 0,1');
	}
	public static function initInfoBulleState($categorie_menu_help_state,$menu_help_state,$sous_menu_help_state)
	{
		self::$db->update('categorie_menu',array('show_help'=>$categorie_menu_help_state));
		self::$db->update('menu',array('show_help'=>$menu_help_state));
		self::$db->update('sous_menu',array('show_help'=>$sous_menu_help_state));
	}
	public static function checkMailFormat($email) // verifie la validit� d'une adresse mail
	{
		 if($email==''|| $email=='NULL') return false;  
		 // return (preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#is', $email));
		 return true;
	}
	public static function is_PortAwardConnected($website='www.google.com', $port=80)
	{	
	    $is_conn = false;
		$connected = @fsockopen($website, $port); //website and port
		if ($connected){
			$is_conn = true; //action when connected
			fclose($connected);
		}else{
			$is_conn = false; //action in connection failure
		}
		return $is_conn;

	}
	public static function is_connected($website='www.google.com')
	{	
	    exec("ping -n 4 $website 2>&1", $output, $retval);
		if ($retval != 0) { 
		    return false;
		} 
		else { 
			return true;
		}
	}	
	public static function sendMail($subject, $message, $destinataires = array(), $attachement = '', $emetteur = '', $is_html_message = true) 
	{   
		if($destinataires==null || count($destinataires) == 0) 
		{
			return false;
		}
		$sending_state = false; 
		$last_static_params = self::$db->queryOneRecord('select * from static_params order by id desc limit 0,1');
		$active_alerte_mail_state= isset($last_static_params['active_alerte_mail_state'])?$last_static_params['active_alerte_mail_state']:0;
		if($active_alerte_mail_state==1&&isset($_SESSION['active_alerte_mail_state'])&&$_SESSION['active_alerte_mail_state']==1)
		{

			$sending_state = true;
			$mail = new MyPHPMailer(true);			
			if(!$mail->SendMail($subject, $message, $destinataires,$attachement, $emetteur, $is_html_message)) {				
				$sending_state = false; 
				if(isset($_SESSION['failed_mail_count']))
				{
					if($_SESSION['failed_mail_count']>=3)
					{
						$_SESSION['active_alerte_mail_state'] = 0; // On désactive les tentatives d'envoi de mails
					}
					else
					{
						$_SESSION['failed_mail_count']+=1;
					}
				}
				else $_SESSION['failed_mail_count']=0;
							
			}
			else
			{
				if(isset($_SESSION['failed_mail_count'])) $_SESSION['failed_mail_count'] = 0;
				$sending_state = true; 
			}			
		}
		return $sending_state;
	}

	public static function getRecordYears()
	{
		$requete = 'select distinct YEAR(t.date_enregistrement) year from token t ORDER BY t.date_enregistrement DESC';
		$records = self::$db->queryAllRecords($requete);
		$years = self::array_key_values($records,'year');
		if(count($years)>0 and !isset($_SESSION['globalYear'])) $_SESSION['globalYear'] = $years[0];
		return $years;
	}

	public static function siteInfo()
	{
		return self::$db->queryOneRecord('select * from site order by id desc limit 0,1');
	}

	public static function sendSms($destinataire,$message)
	{
		$ok = false;
		if($destinataire!=''&&$message)
		{
			$message = preg_replace('#(\s)#', '+', $message);
			$request_url = SMS_SERVER_FULL_ADDRESS.'&to='.$destinataire+'&text='.$message;
			echo '
			<script>
				$(function(){
					$.get("'.$request_url.'", function(data) {
															
					});
				});
			</script>
			';
			$ok = true; // timide!
		}
		return $ok;
	}
    
	public static function redirect($url)
	{
		// js secured redirect
		echo '<script language="javascript"> $(function(){ $.redirect("'.$url.'"); }); </script>';
	}

	public static function debug($msg)
	{
		echo $msg;
		exit();
	}
	public static function getJsonItemsOnDialog($key='selected_items',$tag='``')
	{
		$items = array();
		if(isset($_GET[$key]))
		{
			$items = json_decode(preg_replace('#'.$tag .'#','"',$_GET[$key]),true);			
		}
		return $items;
	}

	
}

 

