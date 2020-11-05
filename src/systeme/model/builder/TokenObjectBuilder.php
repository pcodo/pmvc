<?php
class TokenObjectBuilder {
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
	public static function buildAsRecords($data_ids)
	{
		self::init();
		$records = array();		
		if($data_ids!==null && count($data_ids)>0)
		{
			$data_ids_to_string = implode(',', $data_ids);
			$records = self::$static_db->queryAllRecords(
				'select t.* from token t				 
				 where 
				 	t.id in ('.$data_ids_to_string.')
				 	ORDER BY t.id DESC				 	
				 ');		
		}
		return $records;		
	}	
	public static function build($data_ids)
	{
		$records = self::buildAsRecords($data_ids);
		$objects = array();		
		foreach ($records as $key => $data) {
				$object = new Token();				
				$object->setData($data);
				$objects[] = $object;
			}
		return $objects;		
	}
	
}
 
