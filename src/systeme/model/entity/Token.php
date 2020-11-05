<?php
class Token{
	public static $static_db;
	private $data = array();
	protected $db;
	public static $db_table_name = 'token';
	function __construct($id=0, $child_db_table_name='', $child_id = 0){
		$this->db = DataBase::getInstance();
		$this->queryData($id, $child_db_table_name, $child_id);			
    }
	private function queryData($id, $child_db_table_name='', $child_id = 0)
	{
		if($id>0)
		{
			$this->data = $this->db->queryOneRecord('select t.* from token t where t.id='.$id);
		}
		else if(is_numeric($child_id) && $child_id>0 && $child_db_table_name!='')
		{
			$this->data = $this->db->queryOneRecord('select t.* from token t inner join '.$child_db_table_name.' ch_t on t.id = ch_t.id_token where ch_t.id='.$child_id);
		}
	}
	protected function data()
	{
	  return $this->data;
	}
	public function setTokenData($data)
	{
		if($data!==null && count($data)>0) $this->data = $data;
		return $this;
	}
	public function tokenId()
	{
	  return isset($this->data['id'])?$this->data['id']:0;
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
	public function setCurrentStateId($id_current_state)
	{
		$this->data['id_current_state'] = $id_current_state;
		return $this;
	}
	public function currentStateId()
	{
		return isset($this->data['id_current_state'])?$this->data['id_current_state']:0;
	}
	public function currentState()
	{
		return new TokenState($this->currentStateId());
	}
	public function setCurrentProgressId($id_current_progress)
	{
		$this->data['id_current_progress'] = $id_current_progress;
		return $this;
	}
	public function currentProgressId()
	{
		return isset($this->data['id_current_progress'])?$this->data['id_current_progress']:0;
	}
	public function currentProgress()
	{
		return new TokenProgress($this->currentProgressId());
	}
	public function setCurrentForwardId($id_current_forward)
	{
		$this->data['id_current_forward'] = $id_current_forward;
		return $this;
	}
	public function currentForwardId()
	{
		return isset($this->data['id_current_forward'])?$this->data['id_current_forward']:0;
	}
	public function currentForward()
	{
		return new TokenForward($this->currentForwardId());
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
		$ok = false;
		if($this->tokenId()>0) // update
		{
			$ok = $this->db->update('token',array('description'=>$this->description(),'id_current_state'=>$this->currentStateId(),'id_current_progress'=>$this->currentProgressId(),'id_current_forward'=>$this->currentForwardId()),array('id'=>$this->tokenId()));				
		}
		else if($id_intervenant>0)// nouvel enregistrement
		{		
			$ok = $this->db->insertion('token','',$this->description(),$this->currentStateId(), $this->currentProgressId(), $this->currentForwardId(),$id_intervenant,Systeme::now());
			$this->queryData($this->db->lastTabId('token'));			
		}
		return $ok;		
	}
	public function allTokenForwardAsRecords($id_target_user=0, $id_source_user=0,$pageSize=-1, $page=1)
	{
		return TokenForward::allAsRecords($this->id(),$id_target_user,$id_source_user,$pageSize,$page);
	}

	public function allTokenForward($id_target_user=0, $id_source_user=0, $pageSize=-1, $page=1)
	{
		return TokenForward::all($this->id(),$id_target_user,$id_source_user,$pageSize,$page);
	}

	public function allTokenProgressAsRecords($id_token_state=0, $id_token_forward=0,$pageSize=-1, $page=1)
	{
		return TokenProgress::allAsRecords($this->id(),$id_token_state,$id_token_forward,$pageSize,$page);
	}

	public function allTokenProgress($id_token_state=0,$id_token_forward=0,$pageSize=-1, $page=1)
	{
		return TokenProgress::all($this->id(),$id_token_state,$id_token_forward,$pageSize,$page);
	}

	public function tokenProgressByState($id_token_state)
	{
		return TokenProgress::lastWithState($this->id(),$id_token_state);
	}

	public function position()
	{
		$rep = 'Enregistré par '.$this->insertUser()->fullname();
		$tokenProgress = $this->currentProgress();
		$tokenForward = $this->currentForward();		
		if($tokenProgress!==null && $tokenProgress->id()>0)
		{
			$rep = $tokenProgress->tokenState()->nameFr().' par '.$tokenProgress->insertUser()->fullname();
		}

		if($tokenForward!==null && $tokenForward->id()>0)
		{
			$rep.=' [avec '.$tokenForward->targetUser()->fullname().']';
		}
		else
		{
			$rep.=' [avec '.$this->insertUser()->fullname().']';
		}

		return $rep;					
	}	

	public static function init()
	{
		if(self::$static_db==null)	self::$static_db = DataBase::getInstance();
	}

	public static function  allAsRecords($pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.id from token t order by t.id DESC';
		$query_string = DataBase::paginate($query_string, $pageSize,$page);            
		$records = self::$static_db->queryAllRecords($query_string);
		return $records;
	}

	public static function all($pageSize=-1, $page=1)
	{
		$records = self::allAsRecords($pageSize, $page);
		$rep = AncienCombattantObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}
	public static function findOne($id)
	{
		$rep = TokenObjectBuilder::build(array($id));
		if(count($rep)>0)
		{
			return $rep[0];
		}
		else return null;
	}
	/*
     @params:
       ids : contains arrays ids_token
	*/
	public function findAll($ids = array()) 
	{
		return TokenObjectBuilder::build($ids); 
	}

	public static function notForwardedAsRecords($id_insert_user=0,$pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.id from '.self::$db_table_name.' t where t.id not in (select tf.id_token from '.TokenForward::$db_table_name.' tf)';
		if($id_insert_user>0)
		{
			if(!Systeme::str_contains($query_string,'where'))
			{
				$query_string.=' WHERE t.id_intervenant ='.$id_insert_user;
			}
			else
			{
				$query_string.=' AND t.id_intervenant ='.$id_insert_user;
			}
		}

		if($query_string!='')
		{
			$query_string.=' order by t.id DESC';
			$query_string = DataBase::paginate($query_string,$pageSize,$page);
			$records = self::$static_db->queryAllRecords($query_string);
		}		
		return $records;		
	}
	public static function notForwarded($id_insert_user=0,$pageSize=-1, $page=1)
	{
		$records = self::notForwardedAsRecords($id_insert_user,$pageSize, $page);
		$rep = TokenObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}

	public static function inStatesAsRecords($current_states = array(), $id_state_author = 0, $pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		if($current_states!==null && count($current_states)>0)
		{
			$current_states_to_str = implode(',', $current_states);
			if($id_state_author>0)
			{
				$query_string = ' select t.id from '.self::$db_table_name.' t inner join '.TokenProgress::$db_table_name.' tp on t.id = tp.id_token where t.id_current_state in ('.$current_states_to_str.') and tp.id_intervenant = '.$id_state_author.' order by t.id DESC';
			}
			else
			{
				$query_string = ' select t.id from '.self::$db_table_name.' t inner join '.TokenProgress::$db_table_name.' tp on t.id = tp.id_token where t.id_current_state in ('.$current_states_to_str.') order by t.id DESC';
				
			}
			
			$query_string = DataBase::paginate($query_string, $pageSize,$page);            
			$records = self::$static_db->queryAllRecords($query_string);
		}		
		return $records;
	}
	public static function inStates($current_states = array(), $id_state_author = 0, $pageSize=-1, $page=1)
	{
		$records = self::inStatesAsRecords($current_states, $id_state_author, $pageSize, $page);
		$rep = TokenObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}



}
 

