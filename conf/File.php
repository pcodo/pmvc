<?php

 class File {
 	protected $db;
	protected $name;
	protected $description;
	protected $id = 0;
	protected $url;
	protected $insert_user_id;
	protected $insert_date;
    protected $allowed_extention;
    protected $allowed_file_size;
    protected $form_field_identifier ;
    protected $storage_folder;
    protected $relative_storage_folder;
	function __construct($id=0,$form_field_identifier='',$relative_storage_folder='uploads/file/')
	{
		$this->db = new DataBase();
		if(is_numeric($id)&&$id>0)
		{
			$this->queryData($id);			
		}
		else
		{
			$this->id = 0;			
			$this->url = '';
			$this->name = '';
			$this->description = '';	
			$this->insert_user_id = 0;
			$this->insert_date = '';					
		}		
		$this->setConstructInfo($form_field_identifier,$relative_storage_folder);
		$this->allowed_extention = array('jpg','jpeg', 'gif','png','pdf','doc','docx','txt');	
   		$this->allowed_file_size = 5*MB; // MP est une constante définie dans config.php
	}
    
    public function setConstructInfo($form_field_identifier,$relative_storage_folder='uploads/file/')
    {
    	$this->relative_storage_folder = $relative_storage_folder;
    	$items = explode(project_root_directory_name, $_SERVER['SCRIPT_FILENAME']);
		if(count($items)>0)
		{
			$this->storage_folder = $items[0].project_root_directory_name.'/'.$relative_storage_folder;
		}
		else $this->storage_folder = $relative_storage_folder;

		$this->form_field_identifier = $form_field_identifier;			
    }

	private function queryData($id)
	{
		$data = $this->db->queryOneRecord('select d.* from file d where d.id='.$id);
		$this->id = $data['id'];
		$this->name = $data['name'];
		$this->description = $data['description'];
		$this->url = $data['url'];
		$this->insert_user_id = $data['id_intervenant'];
		$this->insert_date = $data['date_enregistrement'];		
	}
	public function id()
	{
		return $this->id;
	}
	public function url()
	{
		return $this->url;
	}
	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}
	public function name()
	{
		return $this->name;
	}
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}
	public function description()
	{
		return $this->description;
	}
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
	public function insertDate()
	{
		return $this->insert_date;
	}
	public function insertUser()
	{
		return new Intervenant($this->insert_user_id);
	}
	public function dbSave($id_intervenant)
	{
		$saved_url = '';
		$relative_url = $this->url();
		$this->storage_folder.='doc_'.($this->db->lastTabId('file')+1);
		$upload_state = Systeme::upload_file($this->form_field_identifier, $this->storage_folder, $this->allowed_extention, $this->allowed_file_size);
		if ($upload_state['etat'] == 'ok')
		{
			$saved_url = $upload_state['saved_file_url'];
			$items = explode($this->relative_storage_folder,$saved_url);
			$relative_url = $this->relative_storage_folder.$items[1];
			if($this->id()>0) // update
			{
				return $this->db->update('file',array('name'=>$this->name(),'description'=>$this->description(),'url'=>$relative_url),array('id'=>$this->id()));
			}
			else if($id_intervenant>0)// nouvel enregistrement
			{
				$ok = false;
				if($this->description()=='') $this->setDescription($saved_url);
				if($this->name()=='') $this->setName($saved_url);
				if($this->url()=='') $this->setUrl($relative_url);
				$ok = $this->db->insertion('file','',$this->name(),$this->description(),$this->url(),$id_intervenant,Systeme::now());				
				$this->queryData($this->db->lastTabId('file'));
				return $ok;
			}			
		}
		else
		{
			return false;
		}		
	}
	public function popupUrl()
	{
		return '../../../../../'.$this->url();
	}
	public function smUrl()
	{
		return $this->url();
	}
}

?>