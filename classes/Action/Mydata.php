<?
class Mydata{
	
	private static $instance = null;

	private function __construct() {}

	public static function getInstance() {
		if(self::$instance === null) {
		self::$instance = new Mydata();
		}
		return self::$instance;
	}	
	
	public $template	= NULL;
	
	public $tagassign   = array();		
						
    public function display()
    {				
		header('Content-type: text/html; charset=utf-8');
		ob_start(); 
		@include_once($this->getTemplate($this->template));
        $html = ob_get_contents();        		
		for($i=0;$i<sizeof($this->tagassign);$i++){
			$old = $this->tagassign[$i]["tagname"];
			$new = $this->tagassign[$i]["value"];					
			$html = str_replace($old,$new,$html);			
		}	
		ob_end_clean();	
		unset($this->tagassign);		
		echo $html;			
    }
	
	public function getTemplate($property_name){
		//Global ${"$property_name"};	
		//$template = ${"$property_name"};	
		//unset(${"$property_name"});
		return str_replace(".","/",$property_name);
	}
	
	public function assign($template){	
		ob_start(); 
		include_once($this->getTemplate($template));
		$html = ob_get_contents(); 	
		ob_end_clean();		
		return $html;		
	}	

	public function assignHTML($tag,$template){		
		$id = sizeof($this->tagassign);	
        $this->tagassign[$id]["tagname"]  = "<@$tag>";	
		$this->tagassign[$id]["value"] = $this->assign($template);	
		
	}	
	
	public function assignText($tag,$value){		
		$id = sizeof($this->tagassign);		
        $this->tagassign[$id]["tagname"]  = "<@$tag>";	
		$this->tagassign[$id]["value"] = $value;		
	}				
}
?>