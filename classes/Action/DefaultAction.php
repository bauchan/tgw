<?		
	namespace Action;
	
	include "Mydata.php";
	include "PagerCtrl.php";
	include "FCK/fckeditor.php";
	 
	class DefaultAction{	
	
		protected $right = "";
	
		protected $name = "index";
		
		protected $content = "list";		
		
		protected $view = "maintain.view.index";	
		
		protected $action = "";
		
		protected $path = "";	

		protected $page;	
		
		protected $header = "maintain.ui.header";	
		
		protected $menu = "maintain.ui.menu";	
		
		protected $footer = "maintain.ui.footer";	
		
		protected $lookMap = Array();
		
		protected $DB;		
	
		public $tagassign   = array();	
	
		protected function Action(){}
		
		public function __construct(){	
			
			Global $db;
			$this->DB = $db;	
			
			if(!isset($_REQUEST["action"]) || !isset($this->lookMap[$_REQUEST["action"]])){
				@$this->action = $this->lookMap[$this->content];	
				
			}else{
				$this->action = $this->lookMap[$_REQUEST["action"]];
				$this->content = $_REQUEST["action"];
			}											
		}
	
		public function display()
		{									
			@header('Content-type: text/html; charset=utf-8');
			ob_start(); 
			@include_once($this->getTemplate($this->view));
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
		
		public function displayByJson()
		{							
			header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
			header("Cache-Control: no-cache, must-revalidate"); 
			header("Pragma: no-cache");
			header("Content-type: application/json");
			ob_start(); 
			@include_once($this->getTemplate($this->view));
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
			return str_replace(".","/",$property_name);
		}
		
		public function assign($template){	
			$html = "";			
			if($template!="" && file_exists($this->getTemplate($template))){						
				ob_start(); 
				include_once($this->getTemplate($template));
				$html = ob_get_contents(); 	
				ob_end_clean();		
			}
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
	
		public function Bind(){									
			$this->Action();			
			$this->assignHTML("content",$this->action);			
			$this->assignHTML("header",$this->header);
			$this->assignHTML("menu",$this->menu);
			$this->assignHTML("footer",$this->footer);
			$this->assignText("path",$this->path);
			$this->display();
		}
		
		public function JsonBind(){
			$this->Action();			
			$this->assignHTML("content",$this->action);
			$this->displayByJson();
		}
		
		public function HtmlBind(){
			$this->Action();			
			//$this->assignHTML("content",$this->action);
			$this->display();		
		
		}

		public function FCK($name,$defaultvalue = NULL){
			Global $FCK_default;
			$sBasePath = "classes/Action/FCK/" ;
			$oFCKeditor = new FCKeditor($name) ;
			$oFCKeditor->BasePath = $sBasePath ;		
			$oFCKeditor->Config['AutoDetectLanguage']	= true ;
			$oFCKeditor->Config['DefaultLanguage']		= "utf-8" ;		
			$oFCKeditor->Value = $FCK_default.$defaultvalue ;
			$oFCKeditor->Create() ;
			unset($FCK_default);				
		}		
		
		protected function doActionBefore(){}
		
		public function pageCtrl($total,$pagesize,$nowpage){			
			$totalpage = ceil(max(1,$total)/$pagesize); //總頁數							

			$result = array();			
			
			$ln = min(6,$totalpage);
			$rn = min($totalpage-5,$totalpage)<1?1:min($totalpage-5,$totalpage);
			
			$flag = 1;
			if($nowpage<=$ln){    		 
				$e = max(min(5,$totalpage),min($nowpage+2,$totalpage));    		
				for($i=1;$i<=$e;$i++){    			
					$result[] = $i;
					$flag++;
				}
			}else{
				$result[] = "1";
				$result[] = "2";
				$result[] = "";
				$flag+=2;
				$s = min($nowpage-2,$nowpage);
				$e = min($nowpage+2,$totalpage);
				if(($e-$s)<4){
					$s = $e - 4;
				}
				for($i=$s;$i<=$e;$i++){
					$result[] = $i;
					$flag=$i;
				}    		    		
			}
			
			
			if($nowpage>=$rn){
				$start = $flag+1;	    		
				for($i=$start;$i<=$totalpage;$i++){
					$result[] = $i;
				}
			}else{
				$result[] = "";
				$e = $totalpage-1;    
				for($i=$e;$i<=$totalpage;$i++){    			
					$result[] = $i;    		
				}
			}				
			$this->page = $result;			
		}
		
		public function QueryStringSet($param,$value){
			
			$params = $_GET;
			$params[$param] = $value;			
			$url = http_build_query($params);			
			return $url;
		}
		
		public function QueryStringSetFromArray($array,$param,$value){			
			$array[$param] = $value;			
			$url = http_build_query($array);			
			return $url;
		}
		
		public function QueryStringSetByArray($array){		
			$params = $_GET;
			while(list($k,$v) = each($array)){
				$params[$k]=$v;
			}
			$url = http_build_query($params);	
			return $url;			
		}
		
		public function Pager($total=0,$pagesize=10,$nowpage=1){				
			$totalpage = (int) ceil((double)$total / $pagesize);     	
			$s = max(1,$nowpage-4); 
			$e = min($nowpage+5,$totalpage);
			$s = $e-9;    	
			if($s<1){
				$s = 1;
				$e = min($s+9,$totalpage);
			}    	
			$prev=0;	
			$next=0;			
			if($nowpage>1){
				$prev = $nowpage-1;
			}			
			if($nowpage<$totalpage){
				$next = $nowpage+1;
			}
			return array($s,$e,$prev,$next);	
		}
		
		public function Get($str){
			return (!empty($_GET[$str]) ? $_GET[$str] : null);
		}
		
		public function setContent($content){
			$this->content = $content;
		}
	}		
?>		