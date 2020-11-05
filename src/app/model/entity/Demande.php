<?php
class Demande extends Token{
	public static $static_db;
    protected $data=array('id'=>0);
	public static $db_table_name = 'app_demande';
	function __construct($id=0){
        parent::__construct(0,self::$db_table_name,$id);
		if(is_numeric($id)&&$id>0)
		{
			$this->queryData($id);
		}
		else $this->data = array('id'=>0);
    }
	private function queryData($id)
	{
		$this->data = $this->db->queryOneRecord('select t.* from '.self::$db_table_name.' t where t.id='.$id);
	}
	protected function data()
	{
	  return $this->data;
	}
	public function setData($data)
	{
		if($data!==null && count($data)>0) $this->data = $data;
		return $this;
	}
	public function id()
	{
	  return isset($this->data['id'])?$this->data['id']:0;
	}
	public function numero()
	{
		$insert_date = DateTime::createFromFormat('Y-m-d H:i:s',$this->insertDate());
		return str_pad($this->id(),4,'0',STR_PAD_LEFT).$insert_date->format('Y');
	}
	public function setObjet($objet)
	{
		$this->data['objet'] = $objet;
		return $this;
	}
	public function objet()
	{
		return isset($this->data['objet'])?$this->data['objet']:'';
	}
	public function setDescription($description)
	{
		$this->data['description'] = $description;
		return $this;
	}
	public function description()
	{
		return isset($this->data['description'])?$this->data['description']:'';
	}
	public function setStructure($structure)
	{
		if(null!==$structure)
			$this->data['id_structure'] = $structure->id();
		return $this;
	}
	public function setStructureId($structureId)
	{
		if(is_numeric($structureId) and $structureId>0)
			$this->data['id_structure'] =  $structureId;
		return $this;
	}
	public function structure()
	{
		return isset($this->data['id_structure'])?(new Structure($this->data['id_structure'])):new Structure(0);
	}
	public function setCloseState($close_state)
	{
		$this->data['close_state'] = $close_state;
		return $this;
	}
	public function setValidState($valid_state)
	{
		$this->data['valid_state'] = $valid_state;
		return $this;
	}
	public function validState()
	{
		return isset($this->data['valid_state'])?$this->data['valid_state']:0;
	}

	public function closeState()
	{
		return isset($this->data['close_state'])?$this->data['close_state']:0;
	}
    public function insertDate()
	{
		return isset($this->data['date_enregistrement'])?$this->data['date_enregistrement']:'';
	}	
	public function insertUser()
	{
		return isset($this->data['id_intervenant'])?(new Intervenant($this->data['id_intervenant'])):(new Intervenant(0));
	}
	public function dbSave($id_intervenant)
	{
		if($this->objet()!='')
		{
			if($this->id()>0) // update
			{
				if(parent::dbSave($id_intervenant))
				{
					return $this->db->update(self::$db_table_name,array(
						'objet'=>$this->objet(),
						'description'=>$this->description(),
						'id_structure'=>$this->structure()->id(),
						'valid_state'=>$this->validState(),
						'close_state'=>$this->closeState()
					),array('id'=>$this->id()));
				}				
			}
			else if($id_intervenant>0)// nouvel enregistrement
			{
				if(parent::dbSave($id_intervenant))
				{
					$ok = $this->db->insertion(self::$db_table_name,'',$this->tokenId(),$this->objet(),$this->description(),$this->structure()->id(), $this->validState(), $this->closeState(), $id_intervenant,Systeme::now());
					$this->queryData($this->db->lastTabId(self::$db_table_name));
					return $ok;
				}				
			}
		}
		else
		{
			return false;
		} 
		
	}
	public function currentState()
	{
		return $this->currentProgress()->tokenState();
	}
	public function currentObservation()
	{
		return $this->currentProgress()->observation();
	}

	public function token()
	{
		return new Token($this->tokenId());
	}

	public function  reservationsAsRecords($pageSize=-1, $page=1)
	{
		return ReservationSalle::allAsRecords($this->id(),$pageSize,$page);
	}

	public function reservations($pageSize=-1, $page=1)
	{
		return ReservationSalle::all($this->id(),$pageSize,$page);
	}

	public function removeAllReservation()
	{
		$this->db->execute('delete from '.ReservationSalle::$db_table_name.' where id_demande = '.$this->id());
	}
	public function horaire()
	{
		$horaire = '';
		$reservations = $this->reservations();
		foreach ($reservations as $key => $reservation) {
			$horaire.=$reservation->horaire().' - ';
		}
		return substr($horaire, 0, strlen($horaire)-3);
	}
	
	public function notesAsRecords($pageSize=-1, $page=1)
	{
		return Note::allAsRecords($this->id(), $pageSize, $page);
	}
	public function notes($pageSize=-1, $page=1)
	{
		return Note::all($this->id(), $pageSize, $page);
	}

	public static function init()
	{
		if(self::$static_db==null)	self::$static_db = DataBase::getInstance();	
	}

	public static function  allAsRecords($pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.id from '.self::$db_table_name.' t order by t.id DESC';
		$query_string = DataBase::paginate($query_string, $pageSize,$page);            
		$records = self::$static_db->queryAllRecords($query_string);
		return $records;
	}

	public static function all($pageSize=-1, $page=1)
	{
		$records = self::allAsRecords($pageSize, $page);
		$rep = DemandeObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}

	public static function findOne($id_demande)
	{
		$rep = DemandeObjectBuilder::build(array($id_demande));
		if(count($rep)>0)
		{
			return $rep[0];
		}
		else return null;
	}

	public static function notForwardedAsRecords($id_insert_user = 0,$pageSize=-1, $page=1)
	{
		self::init();
		$tokens_as_records = Token::notForwardedAsRecords($id_insert_user);
		$records = array();
		if($tokens_as_records!==null and count($tokens_as_records)>0)
		{
			$tokens_as_str = implode(',', Systeme::array_key_values($tokens_as_records,'id'));
			$query_string = ' select t.id from '.self::$db_table_name.' t where t.id_token in ('.$tokens_as_str.') order by t.id DESC';
			$query_string = DataBase::paginate($query_string, $pageSize,$page);            
			$records = self::$static_db->queryAllRecords($query_string);
		}
		return $records;
	}
	public static function notForwarded($id_insert_user = 0,$pageSize=-1, $page=1)
	{
		$records = self::notForwardedAsRecords($id_insert_user, $pageSize, $page);
		$rep = DemandeObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}

	public static function notViewedUserDemandesAsRecords($id_user,$current_states = array(),$previous_states=array(), $excluded_state=array(), $params=array(),$pageSize=-1, $page=1)
	{
		self::init();
		$tokens_as_records = TokenForward::notViewedUserTokensAsRecords($id_user, $current_states, $previous_states, $excluded_state, $params);
		$records = array();
		if($tokens_as_records!==null and count($tokens_as_records)>0)
		{
			$tokens_as_str = implode(',', Systeme::array_key_values($tokens_as_records,'id_token'));
			$query_string = ' select t.id from '.self::$db_table_name.' t where t.id_token in ('.$tokens_as_str.') order by t.id DESC';
			$query_string = DataBase::paginate($query_string, $pageSize,$page);   

			$records = self::$static_db->queryAllRecords($query_string);
		}
		return $records;
	}
	public static function notViewedUserDemandes($id_user,$current_states = array(),$previous_states=array(), $excluded_state=array(), $params=array(),$pageSize=-1, $page=1)
	{
		$records = self::notViewedUserDemandesAsRecords($id_user, $current_states, $previous_states, $excluded_state, $params, $pageSize, $page);
		$rep = DemandeObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}

	public static function notForwardedUserDemandesAsRecords($id_user,$current_states = array(),$previous_states=array(), $excluded_state=array(), $params=array(),$pageSize=-1, $page=1)
	{
		self::init();
		$tokens_as_records = TokenForward::notForwardedUserTokensAsRecords($id_user, $current_states, $previous_states, $excluded_state, $params);
		$records = array();
		if($tokens_as_records!==null and count($tokens_as_records)>0)
		{
			$tokens_as_str = implode(',', Systeme::array_key_values($tokens_as_records,'id_token'));
			$query_string = ' select t.id from '.self::$db_table_name.' t where t.id_token in ('.$tokens_as_str.') order by t.id DESC';
			$query_string = DataBase::paginate($query_string, $pageSize,$page);   

			$records = self::$static_db->queryAllRecords($query_string);
		}
		return $records;
	}
	public static function notForwardedUserDemandes($id_user,$current_states = array(),$previous_states=array(), $excluded_state=array(), $params=array(),$pageSize=-1, $page=1)
	{
		$records = self::notForwardedUserDemandesAsRecords($id_user, $current_states, $previous_states, $excluded_state, $params, $pageSize, $page);
		$rep = DemandeObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}

	public static function inStatesAsRecords($current_states = array(), $id_state_author = 0, $tab_filter_ids = null, $pageSize=-1, $page=1)
	{
		self::init();
		$tokens_as_records = Token::inStatesAsRecords($current_states, $id_state_author);
		$records = array();
		if($tab_filter_ids!==null and count($tab_filter_ids)==0)
			return $records;

		if($tokens_as_records!==null and count($tokens_as_records)>0)
		{
			$tokens_as_str = implode(',', Systeme::array_key_values($tokens_as_records,'id'));
			$query_string = ' select t.id from '.self::$db_table_name.' t where t.id_token in ('.$tokens_as_str.') ';
			
			if($tab_filter_ids!==null and count($tab_filter_ids)>0)
			{
				$tab_filter_ids_str = implode(',',$tab_filter_ids);
				$query_string.=' and t.id in ('.$tab_filter_ids_str.')';
			}
			$query_string.'order by t.id DESC';
			$query_string = DataBase::paginate($query_string, $pageSize,$page);  
			$records = self::$static_db->queryAllRecords($query_string);
		}
		return $records;
	}
	public static function inStates($current_states = array(), $id_state_author = 0, $tab_filter_ids=null, $pageSize=-1, $page=1)
	{
		$records = self::inStatesAsRecords($current_states, $id_state_author,  $tab_filter_ids, $pageSize, $page);
		$rep = DemandeObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}

	public static function searchForOverllappingAsRecords($date_debut, $date_fin, $id_salle,$pageSize=-1,$page=1)
	{
		self::init();
		$records = array();
		if($date_debut=='' || $date_fin=='' || $id_salle==0)
			return $records;

		if(!Systeme::str_contains($date_debut,':')) $date_debut = $date_debut.' 00:00:00';
		if(!Systeme::str_contains($date_fin,':')) $date_fin = $date_fin.' 00:00:00';
		
		$query_string = 'select distinct t.id from '.self::$db_table_name.' t inner join '.ReservationSalle::$db_table_name.' r on t.id = r.id_demande where ((r.date_debut <="'.$date_debut.'" and r.date_fin >"'.$date_debut.'" ) OR (r.date_debut>="'.$date_debut.'" and r.date_debut <"'.$date_fin.'")) AND r.id_salle = '.$id_salle.' and t.valid_state=1';
 

    	if($query_string!='')
		{
			$query_string = Database::paginate($query_string,$pageSize,$page);
			$records = self::$static_db->queryAllRecords($query_string);
		}		
		
		return $records;
	}
    
	public static function searchByReservationDateAsRecords($date_debut, $date_fin, $id_salle =0,$pageSize=-1,$page=1)
	{
		self::init();
		$records = array();
		if($date_debut=='' || $date_fin=='')
			return $records;
		if(!Systeme::str_contains($date_debut,':')) $date_debut = $date_debut.' 00:00:00';
		if(!Systeme::str_contains($date_fin,':')) $date_fin = $date_fin.' 23:59:59';
		
		$query_string = 'select distinct t.id from '.self::$db_table_name.' t inner join '.ReservationSalle::$db_table_name.' r on t.id = r.id_demande where r.date_debut >="'.$date_debut.'" and r.date_debut <="'.$date_fin.'" and t.valid_state=1';
       
    	if($id_salle>0)
		{
			if(!Systeme::str_contains($query_string,'where'))
			{
				$query_string.=' WHERE r.id_salle ='.$id_salle;
			}
			else
			{
				$query_string.=' AND r.id_salle ='.$id_salle;
			}
		}
		
		if($query_string!='')
		{
			$query_string = Database::paginate($query_string,$pageSize,$page);
			$records = self::$static_db->queryAllRecords($query_string);
		}		
		
		return $records;
	}

	public static function searchByReservationDate($date_debut, $date_fin, $id_salle =0, $pageSize=-1, $page=1)
    {
    	$records = self::searchByReservationDateAsRecords($date_debut,$date_fin,$id_salle, $pageSize, $page);
		$rep = DemandeObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
    }


}
 

