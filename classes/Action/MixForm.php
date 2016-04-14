<?	
	namespace Action;		
	
	class MixForm extends DefaultAction{
		
		protected $name = "MixForm";			
		
		protected $content = "view";			
		
		protected $view = "maintain.view.index";	
		
		protected $action = "";			
		
		protected $lookMap = Array(
			"main"=>"",
			"save"=>"",			
			"login"=>"",
			"logout"=>"",
			"view"=>"maintain.content.activity.view",
			"detail"=>"maintain.content.activity.detail",
			"list"=>"maintain.content.activity.list",
		);		
		
		public function __construct(){				
			parent::__construct();
		}

		protected function Action(){						
			
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