<?

class DefaultDAO{
	public $name;
	public $entity;
	public DefaultDAO($name){
		$this->name = $name;
		$this->entity = new adodb_active_record($name);
	}
	
	public load(){
	
	}
	
}
?>