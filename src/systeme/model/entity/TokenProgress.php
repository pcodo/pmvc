<?php
class TokenProgress {
	public static $static_db;
    protected $db;
	protected $data=array('id'=>0);
	public static $db_table_name = 'token_progress';
	function __construct($id=0){
        $this->db = DataBase::getInstance();
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
	public function setToken($token)
	{
		if(null!==$token)
			$this->data['id_token'] = $token->tokenId();
		return $this;
	}
	public function setTokenId($tokenId)
	{
		if(is_numeric($tokenId) and $tokenId>0)
			$this->data['id_token'] =  $tokenId;
		return $this;
	}
	public function token()
	{
		return isset($this->data['id_token'])?(new Token($this->data['id_token'])):new Token(0);
	}
	public function setTokenState($tokenState)
	{
		if(null!==$tokenState)
			$this->data['id_token_state'] = $tokenState->id();
		return $this;
	}
	public function setTokenStateId($tokenStateId)
	{
		if(is_numeric($tokenStateId) and $tokenStateId>0)
			$this->data['id_token_state'] =  $tokenStateId;
		return $this;
	}
	public function tokenState()
	{
		return isset($this->data['id_token_state'])?(new TokenState($this->data['id_token_state'])):new TokenState(0);
	}
	public function setTokenForward($tokenForward)
	{
		if(null!==$tokenForward)
			$this->data['id_token_forward'] = $tokenForward->id();
		return $this;
	}
	public function setTokenForwardId($id_token_forward)
	{
		if(is_numeric($id_token_forward) and $id_token_forward>0)
			$this->data['id_token_forward'] =  $id_token_forward;
		return $this;
	}
	public function tokenForward()
	{
		return isset($this->data['id_token_forward'])?(new TokenForward($this->data['id_token_forward'])):new TokenForward(0);
	}
	public function setObservation($observation)
	{
		$this->data['observation'] = $observation;
		return $this;
	}
	public function observation()
	{
		return isset($this->data['observation'])?$this->data['observation']:'';
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
		if($this->id()>0) // update
		{
			return $this->db->update(self::$db_table_name,array('id_token'=>$this->token()->tokenId(),'id_token_state'=>$this->tokenState()->id(),'id_token_forward'=>$this->tokenForward()->id(),'observation'=>$this->observation()),array('id'=>$this->id()));
		}
		else if($id_intervenant>0)// nouvel enregistrement
		{
			$ok = $this->db->insertion(self::$db_table_name,'',$this->token()->tokenId(),$this->tokenState()->id(),$this->tokenForward()->id(),$this->observation(), $id_intervenant,Systeme::now());
			$this->queryData($this->db->lastTabId(self::$db_table_name));

			// update token current_state
			$this->db->update(Token::$db_table_name,array('id_current_state'=>$this->tokenState()->id(),'id_current_progress'=>$this->id()),array('id'=>$this->token()->tokenId()));	
			return $ok;
		}		
	}


	public static function init()
	{
		if(self::$static_db==null)	self::$static_db = DataBase::getInstance();	
	}

	public static function  allAsRecords($id_token, $id_token_state = 0, $id_token_forward = 0, $pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.id from '.self::$db_table_name.' t where t.id_token = '.$id_token;

		if($id_token_state>0)
		{
			if(!Systeme::str_contains($query_string,'where'))
			{
				$query_string.=' WHERE t.id_token_state ='.$id_token_state;
			}
			else
			{
				$query_string.=' AND t.id_token_state ='.$id_token_state;
			}
		}
		if($id_token_forward>0)
		{
			if(!Systeme::str_contains($query_string,'where'))
			{
				$query_string.=' WHERE t.id_token_forward ='.$id_token_forward;
			}
			else
			{
				$query_string.=' AND t.id_token_forward ='.$id_token_forward;
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

	public static function all($id_token, $id_token_state = 0, $id_token_forward = 0, $pageSize=-1, $page=1)
	{
		$records = self::allAsRecords($id_token,$id_token_state,$id_token_forward,$pageSize, $page);
		$rep = TokenProgressObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}
	public static function lastAsRecord($id_token)
	{
		self::init();
		$record = array();
		$query_string = ' select t.id from '.self::$db_table_name.' t where t.id_token = '.$id_token.' order by t.id DESC LIMIT 0,1';
		$record = self::$static_db->queryOneRecord($query_string);
		return $record;
	}
	public static function last($id_token)
	{
		$record = self::lastAsRecord($id_token);
		return $record?new TokenProgress($record['id']):new TokenProgress();
	}
	public static function lastWithStateAsRecord($id_token,$id_token_state)
	{
		self::init();
		$record = array();
		$query_string = ' select t.* from '.self::$db_table_name.' t where t.id_token = '.$id_token.' and t.id_token_state = '.$id_token_state.' order by t.id DESC LIMIT 0,1';
		$record = self::$static_db->queryOneRecord($query_string);
		return $record;
	}
	public static function lastWithState($id_token,$id_token_state)
	{
		$record = self::lastWithStateAsRecord($id_token,$id_token_state);
		return $record?new TokenProgress($record['id']):new TokenProgress();
	}

	public static function  allDataAsRecords($id_token, $pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.* from '.self::$db_table_name.' t where t.id_token = '.$id_token.' order by t.id DESC';
		$query_string = DataBase::paginate($query_string, $pageSize,$page);            
		$records = self::$static_db->queryAllRecords($query_string);
		return $records;
	}

	
	
}
 

