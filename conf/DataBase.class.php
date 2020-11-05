<?php 
require_once("config.php");
class DataBase
{
	var $bdd;
	var $dbHost="";
	var $dbName="";
	var $dbUser = "";
	var $dbMdp="";
	private static $connexion = null;	
	// pour augmenter le nombre de connexion dans mysql, on peut ajouter la ligne: max_connections=500 dans /etc/my.in ou /etc/my.conf
		
	public function __construct($dbHost="localhost",$dbName="pmvc",$dbUser="root",$dbMdp=DBPWD)
	{
		$this->dbHost=$dbHost;
		$this->dbName=$dbName;
		$this->dbUser = $dbUser;
		$this->dbMdp=$dbMdp;
		$this->bdd = $this->connexiondb();
	}
	public static function getInstance()
	{
		if(self::$connexion===null)
		{
			self::$connexion = new DataBase();
		}
		return self::$connexion;
	}	
	public function query($queryString) // envoie l'instance de la reponse PDO
	{ 
		if($this->bdd!=null)
		{
			$reponse = $this->bdd->query($queryString);
			//$reponse->closeCursor();
			return $reponse;
		}
		else
		{
			return null;
		}
	}
	public function queryOneRecord($queryString) // envoie une seule ligne de reponse sous forme de tableau associatif regroupant les attributs des tables indexées
	{
		if($this->bdd!=null)
		{
			$reponse = $this->bdd->query($queryString);
			return $reponse->fetch();
		}
		else
		{
			return null;
		}
	}
	public function queryAllRecords($queryString) // envoie toutes les lignes de reponse sous forme d'un tableau de tableaux associatifs regroupant les attributs des tables indexées
	{
		if($this->bdd!=null)
		{
		    $reponse = $this->bdd->query($queryString);
			return  $reponse->fetchAll();
		}
		else
		{
			return null;
		}
	}
		
	public function countTabRows($tabName)
	{
		if($this->bdd!=null)
		{
			$reponse = $this->queryOneRecord('select count(*) nbr from`'.$tabName.'`');
			return $reponse['nbr'];
		}
		else
		{
			return null;
		}
	}
	public function lastTabId($tabName,$premaryKeyFieldName='id')
	{
		if($this->bdd!=null)
		{
			$reponse = $this->queryOneRecord('select max('.$premaryKeyFieldName.') max_id from`'.$tabName.'`');
			return $reponse['max_id'];
		}
		else
		{
			return null;
		}
	}
	/*
	   gere la modification des tables:
	   exemple : $this->update('poste_sous_menu',array('id_poste'=>2),array('id_poste'=>1));
	   va générer la requete :
	   UPDATE `poste_sous_menu` SET `id_poste` = 2 WHERE `id_poste` = 1
	*/
	public function update($tabName,$updatefields,$wherecloseFields = array())
	{
		if($this->bdd!=null)
		{
		   $fieldstr  = " "; $i=0;
		   foreach($updatefields as $cle=>$valeur)
		   {
			// if(is_numeric($valeur))
			// $fieldstr.='`'.$cle.'` ='. $this->escape($valeur).',';
			// elseif(is_string($valeur))
			// $fieldstr.='`'.$cle.'` ="'. $this->escape($valeur).'",';
			$fieldstr.='`'.$cle.'` ="'. $this->escape($valeur).'",';
			$i++;
		   }
		   $fieldstr = substr($fieldstr, 0, strlen($fieldstr) - 1);
		   if(trim($fieldstr)=='') return false;
		   
		   $wfieldstr  = " "; $i=0;
		   foreach($wherecloseFields as $cle=>$valeur)
		   {
			if(is_numeric($valeur))
			$wfieldstr.='`'.$cle.'` ='. $this->escape($valeur).' and ';
			elseif(is_string($wherecloseValues[$i]))
			$wfieldstr.='`'.$cle.'` ="'. $this->escape($valeur).'" and ';
			$i++;
		   }
		   $wfieldstr = substr($wfieldstr, 0, strlen($wfieldstr) - 4);
		  
		   if($wfieldstr!='')
				$this->execute('UPDATE `'.$tabName.'` set '.$fieldstr.' where '.$wfieldstr);
		   else
				$this->execute('UPDATE `'.$tabName.'` set '.$fieldstr);
				
			return true;
		}
		else
		{
			return false;
		}
	}
	public function updateRequestText($tabName,$updatefields,$wherecloseFields = array())
	{
		
		   $fieldstr  = " "; $i=0;
		   foreach($updatefields as $cle=>$valeur)
		   {
			if(is_numeric($valeur))
			$fieldstr.='`'.$cle.'` ='. $this->escape($valeur).',';
			elseif(is_string($valeur))
			$fieldstr.='`'.$cle.'` ="'. $this->escape($valeur).'",';
			$i++;
		   }
		   $fieldstr = substr($fieldstr, 0, strlen($fieldstr) - 1);
		   
		   $wfieldstr  = " "; $i=0;
		   foreach($wherecloseFields as $cle=>$valeur)
		   {
			if(is_numeric($valeur))
			$wfieldstr.='`'.$cle.'` ='. $this->escape($valeur).' and ';
			elseif(is_string($wherecloseValues[$i]))
			$wfieldstr.='`'.$cle.'` ="'. $this->escape($valeur).'" and ';
			$i++;
		   }
		   $wfieldstr = substr($wfieldstr, 0, strlen($wfieldstr) - 4);
		  
		   if($wfieldstr!='')
				return 'UPDATE `'.$tabName.'` set '.$fieldstr.' where '.$wfieldstr;
		   else
				return 'UPDATE `'.$tabName.'` set '.$fieldstr;
		
	}
	
	public function execute($queryString)
	{
		if($this->bdd!=null)
		{
		    return $this->bdd->exec($queryString);
		}
		else
		{
			return false;
		}
	}
	public function insertion($parametres)
	{
		if($this->bdd!=null)
		{
			$valeur = func_get_args();
			$table = array_shift($valeur);
			$value_text = "";
			foreach ($valeur as $val) {
				$value_text.="'" . $this->escape($val) . "',";
			}
			$value_text = substr($value_text, 0, strlen($value_text) - 1);
			return $this->bdd->exec("insert into $table values($value_text)");
		}
		else
        {
			return false;
	    }
	}
	
	public function insertionRequestText($parametres)
	{
		$valeur = func_get_args();
		$table = array_shift($valeur);
		$value_text = "";
		foreach ($valeur as $val) {
			$value_text.="'" . $this->escape($val) . "',";
		}
		$value_text = substr($value_text, 0, strlen($value_text) - 1);
		return "insert into $table values($value_text)";
		
	}
	// verifier l'existence d'un enregistrement dans une table::
	public function countMatchedRows($tabName,$wherecloseFields)
	{
		if($this->bdd!=null)
		{
		   $fieldstr  = " ";
		   foreach($wherecloseFields as $cle=>$valeur)
		   {
			if(is_numeric($valeur))
			$fieldstr.='`'.$cle.'` ='. $this->escape($valeur).' and ';
			elseif(is_string($valeur))
			$fieldstr.='`'.$cle.'` ="'. $this->escape($valeur).'" and ';
		   }
		   $fieldstr = substr($fieldstr, 0, strlen($fieldstr) - 4);
		   if($fieldstr!='')
		   {
				$count =  $this->queryOneRecord('select count(*) nbr from `'.$tabName.'` where '.$fieldstr);
				return $count['nbr'];
		   }
		   else
				return 0;
		}
		else
		{
			return 0;
		}
	}
	public function connexiondb()
	{
		
		try
		{
			$pdo_options[PDO::ATTR_PERSISTENT] = true;
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8";// particulier à une base de donnée mysql pour les encodages utf8
			return new PDO('mysql:host='.$this->dbHost.';dbname='.$this->dbName, $this->dbUser, $this->dbMdp, $pdo_options);
			
		}
		catch(Exception $e)
		{
		    die ('erreur : '.$e->getMessage());
			return null;
		}
	}
	
	public function escape($data) {

		if (!is_string($data) && !is_numeric($data))
            return null;
			
		if($this->bdd!=null)
		{
			return self::mysql_escape_mimic(stripslashes($data));
			#return mysql_real_escape_string(stripslashes($data));
			#return mysql_escape_string(stripslashes($data));
		}
		else
		{
		   return false;
		}
    }

    public static function  mysql_escape_mimic($inp) {
	    if(is_array($inp))
	        return array_map(__METHOD__, $inp);

	    if(!empty($inp) && is_string($inp)) {
	        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
	    }

	    return $inp;
	} 
	
	// Les instrcutions commentées dans les trois fonctions suivantes marchent, mais étant donné que la PDO les implémente, je prefere passer par les méthodes déjà existentes.
	public function startTransaction()
	{
		// $this->execute('START TRANSACTION;');
		
		$this->bdd->beginTransaction(); // Officiel, mais désactivé pour mieux organiser les appels effectués dans le code avant de réactiver cette ligne.
	}
	public function commit()
	{
		// $this->execute('COMMIT;');
		$this->bdd->commit();// Officiel, mais désactivé pour mieux organiser les appels effectués dans le code avant de réactiver cette ligne.
	}
	public function rollback()
	{
		// $this->execute('ROLLBACK;');
		$this->bdd->rollback();
	}

	public static function paginate($queryString,$pageSize,$page)
	{
		if($pageSize>0)
		{
			if($page!=0 and $page<1) $page = 1;
			$startpos = ($page-1)*$pageSize;
			$queryString.=' LIMIT '.$startpos.','.$pageSize;
		}
		return $queryString;
	}
	
}


?>
