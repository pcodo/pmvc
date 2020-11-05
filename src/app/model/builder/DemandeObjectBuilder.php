<?php
class DemandeObjectBuilder {
	public static $static_db;
	protected $db;
	protected $objects = array();
	protected $records = array();
	function __construct($data_ids){
		$this->db = DataBase::getInstance();
       	$this->records = self::buildAsRecords($data_ids);		
       	$this->objects = self::build($data_ids);		
	}
	public function objects()
	{
		return $this->objects;
	}
	public function records()
	{
		return $this->objects;
	}
	public static function init()
	{
		if(self::$static_db==null)	self::$static_db = DataBase::getInstance();
	}
	public static function buildAsRecords($data_ids, $order_params = array('key'=>'id','order'=>'DESC'))
	{
		self::init();
		$records = array();		
		if($data_ids!==null && count($data_ids)>0)
		{
			$data_ids_to_string = implode(',', $data_ids);
			$records = self::$static_db->queryAllRecords(
				'select t.*, 
				tn.description tn_description, tn.id_current_state tn_id_current_state, tn.id_current_progress tn_id_current_progress, tn.id_current_forward tn_id_current_forward, tn.id_intervenant tn_id_intervenant, tn.date_enregistrement tn_date_enregistrement
				 from '.Demande::$db_table_name.' t	inner join '.Token::$db_table_name.' tn on t.id_token = tn.id			 
				 where 
				 	t.id in ('.$data_ids_to_string.')
				 	ORDER BY t.'.$order_params['key'].' '.$order_params['order']);			
			
		}
		return $records;		
	}	
	public static function build($data_ids, $order_params = array('key'=>'id','order'=>'DESC'))
	{
		$records = self::buildAsRecords($data_ids,$order_params);
		$objects = array();		
		foreach ($records as $key => $data) {
			    $token_data = array(
					'id'=>$data['id_token'],
					'description'=>$data['tn_description'],
					'id_current_state'=>$data['tn_id_current_state'],
					'id_current_progress'=>$data['tn_id_current_progress'],
					'id_current_forward'=>$data['tn_id_current_forward'],
					'id_intervenant'=>$data['tn_id_intervenant'],
					'date_enregistrement'=>$data['tn_date_enregistrement']					
				);
				$object = new Demande();				
				$object->setData($data);
				$object->setTokenData($token_data);
				$objects[] = $object;
			}
		return $objects;		
	}
	
}
 
