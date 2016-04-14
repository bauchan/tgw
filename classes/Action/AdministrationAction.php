<?
	include "Action.php";
	
	class AdministrationAction extends DefaultAction
	{
		protected $name = "index";			
		
		protected $content = "view";			
		
		protected $view = "maintain.view.index";	
		
		protected $action = "";			
		
		protected $lookMap = Array(
			"main"=>"",
			"save"=>"",			
			"login"=>"",
			"detail"=>"maintain.content.administration.detail",
			"view"=>"maintain.content.administration.view"
		);		
		
		public function __construct(){				
			parent::__construct();
		}

		protected function Action(){						
			if($this->content=="view"){										
				$sql = "SELECT * FROM `teacher` WHERE `type`='3' order by `sort` ASC";	 
				$this->data = $this->DB->Execute($sql);														
			}else if($this->content=="detail"){
				$this->data=new adodb_active_record('teacher');
				$this->data->load("t_id=".(int)$_GET['id']);					
				
				$cnt = 0;
				
				$sql = "SELECT * FROM `teacher_book` WHERE `book_type`='1' and `t_id`=? ";	 
				$this->book1 = $this->DB->Execute($sql,array(@(int)$_GET['id']));
				$cnt+=$this->book1->_numOfRows;
				
				$sql = "SELECT * FROM `teacher_book` WHERE `book_type`='2' and `t_id`=? ";	 
				$this->book2 = $this->DB->Execute($sql,array(@(int)$_GET['id']));
				$cnt+=$this->book2->_numOfRows;
				
				$sql = "SELECT * FROM `teacher_book` WHERE `book_type`='3' and `t_id`=? ";	 
				$this->book3 = $this->DB->Execute($sql,array(@(int)$_GET['id']));
				$cnt+=$this->book3->_numOfRows;
				
				$sql = "SELECT * FROM `teacher_book` WHERE `book_type`='4' and `t_id`=? ";	 
				$this->book4 = $this->DB->Execute($sql,array(@(int)$_GET['id']));
				$cnt+=$this->book4->_numOfRows;
								
				$sql = "SELECT * FROM `teacher_book` WHERE `book_type`='5' and `t_id`=? ";	 
				$this->book5 = $this->DB->Execute($sql,array(@(int)$_GET['id']));
				$cnt+=$this->book5->_numOfRows;			

				$this->hasBook = $cnt > 0;	
			}
		}
		
		
		function endsWith($haystack, $needle)
		{
			$length = strlen($needle);
			if ($length == 0) {
				return true;
			}

			$start  = $length * -1; //negative
			return (substr($haystack, $start) === $needle);
		}

	}		
?>		