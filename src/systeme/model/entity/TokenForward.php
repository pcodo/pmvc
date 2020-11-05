<?php
class TokenForward {
	public static $static_db;
    protected $db;
	protected $data=array('id'=>0);
	public static $db_table_name = 'token_forward';
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

	public function setTargetUser($targetUser)
	{
		if(null!==$targetUser)
			$this->data['id_target_user'] = $targetUser->id();
		return $this;
	}
	public function setTargetUserId($targetUserId)
	{
		if(is_numeric($targetUserId) and $targetUserId>0)
			$this->data['id_target_user'] =  $targetUserId;
		return $this;
	}
	public function targetUser()
	{
		return isset($this->data['id_target_user'])?(new Intervenant($this->data['id_target_user'])):new Intervenant(0);
	}

	public function setTokenBundle($tokenBundle)
	{
		if(null!==$tokenBundle)
			$this->data['id_token_bundle'] = $tokenBundle->id();
		return $this;
	}
	public function setTokenBundleId($tokenBundleId)
	{
		if(is_numeric($tokenBundleId) and $tokenBundleId>0)
			$this->data['id_token_bundle'] =  $tokenBundleId;
		return $this;
	}
	public function tokenBundle()
	{
		return isset($this->data['id_token_bundle'])?(new TokenBundle($this->data['id_token_bundle'])):new TokenBundle(0);
	}
	public function setViewed($viewed)
	{
		$this->data['viewed'] = $viewed;
		return $this;
	}
	public function viewed()
	{
		return isset($this->data['viewed'])?$this->data['viewed']:0;
	}
	public function setReceived($received)
	{
		$this->data['received'] = $received;
		return $this;
	}
	public function received()
	{
		return isset($this->data['received'])?$this->data['received']:0;
	}
	public function setNextForwardId($id_next_forwarded)
	{
		$this->data['id_next_forwarded'] = $id_next_forwarded;
		return $this;
	}
	public function nextForwardId()
	{
		return isset($this->data['id_next_forwarded'])?$this->data['id_next_forwarded']:0;
	}
	public function nextForward()
	{
		return isset($this->data['id_next_forwarded'])?(new TokenForward($this->data['id_next_forwarded'])):new TokenForward(0);
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
			return $this->db->update(self::$db_table_name,array('id_token'=>$this->token()->tokenId(),'id_target_user'=>$this->targetUser()->id(),'id_token_bundle'=>$this->tokenBundle()->id(),'viewed'=>$this->viewed(), 'received'=>$this->received(),'id_next_forward'=>$this->nextForward()->id(),'observation'=>$this->observation()),array('id'=>$this->id()));
		}
		else if($id_intervenant>0)// nouvel enregistrement
		{
			$ok = $this->db->insertion(self::$db_table_name,'',$this->token()->tokenId(),$this->targetUser()->id(),$this->tokenBundle()->id(), $this->viewed(), $this->received(), $this->nextForward()->id(), $this->observation(), $id_intervenant,Systeme::now());

			$this->queryData($this->db->lastTabId(self::$db_table_name));

			// update token current_state
			$this->db->update(Token::$db_table_name,array('id_current_forward'=>$this->id()),array('id'=>$this->token()->tokenId()));
			return $ok;
		}
		
		
	}
	
	public static function init()
	{
		if(self::$static_db==null)	self::$static_db = DataBase::getInstance();	
	}
	public static function lastTabId()
    {
    	self::init();
    	return self::$static_db->lastTabId(self::$db_table_name);
    }
	public static function  allAsRecords($id_token=0, $id_target_user=0, $id_source_user=0, $pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.id from '.self::$db_table_name.' t';
		if($id_token>0)
		{
			if(!Systeme::str_contains($query_string,'where'))
			{
				$query_string.=' WHERE t.id_token ='.$id_token;
			}
			else
			{
				$query_string.=' AND t.id_token ='.$id_token;
			}
		}
		if($id_target_user>0)
		{
			if(!Systeme::str_contains($query_string,'where'))
			{
				$query_string.=' WHERE t.id_target_user ='.$id_target_user;
			}
			else
			{
				$query_string.=' AND t.id_target_user ='.$id_target_user;
			}
		}
		if($id_source_user>0)
		{
			if(!Systeme::str_contains($query_string,'where'))
			{
				$query_string.=' WHERE t.id_intervenant ='.$id_source_user;
			}
			else
			{
				$query_string.=' AND t.id_intervenant ='.$id_source_user;
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

	public static function all($id_token=0, $id_target_user=0, $id_source_user=0, $pageSize=-1, $page=1)
	{
		$records = self::allAsRecords($id_token, $id_target_user, $id_source_user, $pageSize, $page);
		$rep = TokenForwardObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}

	public static function  allDataAsRecords($pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.* from '.self::$db_table_name.' t order by t.id DESC';
		$query_string = DataBase::paginate($query_string, $pageSize,$page);            
		$records = self::$static_db->queryAllRecords($query_string);
		return $records;
	}	

	public static function notViewedUserTokensAsRecords($id_user,$current_states = array(),$previous_states=array(), $excluded_state=array(), $params=array(),$pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.id_token from '.self::$db_table_name.' t where t.id_target_user = '.$id_user.' and t.viewed = 0';
		$in_state_records = Token::inStatesAsRecords($current_states);
		if($in_state_records!==null && count($in_state_records)>0)
		{
			$in_state_records_str = implode(',', Systeme::array_key_values($in_state_records,'id'));
			if(!Systeme::str_contains($query_string,'where'))
			{
				$query_string.=' WHERE t.id_token in ('.$in_state_records_str.')';
			}
			else
			{
				$query_string.=' AND t.id_token in ('.$in_state_records_str.')';
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
	public static function notViewedUserTokens($id_user,$current_states = array(),$previous_states=array(), $excluded_state=array(), $params=array(),$pageSize=-1, $page=1)
	{
		$records = self::notViewedUserTokensAsRecords($id_user, $current_states, $previous_states, $excluded_state, $params, $pageSize, $page);
		$rep = TokenObjectBuilder::build(Systeme::array_key_values($records,'id_token'));
		return $rep;
	}
	public static function notReceivedUserTokensAsRecords($id_user,$current_states = array(),$previous_states=array(), $excluded_state=array(), $params=array(),$pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.id_token from '.self::$db_table_name.' t where t.id_target_user = '.$id_user.' and t.received = 0';
		$in_state_records = Token::inStatesAsRecords($current_states);
		if($in_state_records!==null && count($in_state_records)>0)
		{
			$in_state_records_str = implode(',', Systeme::array_key_values($in_state_records,'id'));
			if(!Systeme::str_contains($query_string,'where'))
			{
				$query_string.=' WHERE t.id_token in ('.$in_state_records_str.')';
			}
			else
			{
				$query_string.=' AND t.id_token in ('.$in_state_records_str.')';
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
	public static function notReceivedUserTokens($id_user,$current_states = array(),$previous_states=array(), $excluded_state=array(), $params=array(),$pageSize=-1, $page=1)
	{
		$records = self::notReceivedUserTokensAsRecords($id_user, $current_states, $previous_states, $excluded_state, $params, $pageSize, $page);
		$rep = TokenObjectBuilder::build(Systeme::array_key_values($records,'id_token'));
		return $rep;
	}

	public static function notForwardedUserTokensAsRecords($id_user,$current_states = array(),$previous_states=array(), $excluded_state=array(), $params=array(),$pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.id_token from '.self::$db_table_name.' t where t.id_target_user = '.$id_user.' and t.id_next_forward = 0';
		$in_state_records = Token::inStatesAsRecords($current_states);		
		if($in_state_records==null || count($in_state_records)==0)
		{
			return $records;
		}		
		$in_state_records_str = implode(',', Systeme::array_key_values($in_state_records,'id'));
		if(!Systeme::str_contains($query_string,'where'))
		{
			$query_string.=' WHERE t.id_token in ('.$in_state_records_str.')';
		}
		else
		{
			$query_string.=' AND t.id_token in ('.$in_state_records_str.')';
		}		

		if($query_string!='')
		{
			$query_string.=' order by t.id DESC';
			$query_string = DataBase::paginate($query_string,$pageSize,$page);
			$records = self::$static_db->queryAllRecords($query_string);
		}	

		return $records;
	}
	public static function notForwardedUserTokens($id_user,$current_states = array(),$previous_states=array(), $excluded_state=array(), $params=array(),$pageSize=-1, $page=1)
	{
		$records = self::notForwardedUserTokensAsRecords($id_user, $current_states, $previous_states, $excluded_state, $params, $pageSize, $page);
		$rep = TokenObjectBuilder::build(Systeme::array_key_values($records,'id_token'));
		return $rep;
	}
	
}
 

