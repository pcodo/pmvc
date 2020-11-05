<?php
class IntervenantInterimaireObjectBuilder {
	public static $static_db;
	protected $db;
	protected $objects = array();
	function __construct($data_ids){
		$this->db = new DataBase();
       	$this->objects = self::build($data_ids);		
	}
	public function objects()
	{
		return $this->objects;
	}
	public static function init()
	{
		if(self::$static_db==null)	self::$static_db = new DataBase();
	}	
	public static function build($data_ids)
	{
		self::init();
		$objects = array();		
		if($data_ids!==null && count($data_ids)>0)
		{
			$data_ids_to_string = implode(',', $data_ids);
			$records = self::$static_db->queryAllRecords(
				'select t.* from intervenant_interimaire t				 
				 where 
				 	t.id in ('.$data_ids_to_string.')	
				 	ORDER BY t.id DESC			 	
				 ');
			
			foreach ($records as $key => $data) {
				$object = new IntervenantInterimaire();				
				$object->setData($data);
				$objects[] = $object;
			}
		}
		return $objects;		
	}
	
}
 
