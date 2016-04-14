<?		
	
	namespace Action;			
	
	class ApCode extends DefaultAction{
		
		protected $name = "GrindingMode";			
		
		protected $content = "layout";			
		
		protected $view = "maintain.view.index";	
		
		protected $action = "";			
		
		protected $lookMap = Array(
			"list"=>"",
			"delete"=>"",
			"save"=>"",			
			"update"=>"",
			"view"=>"maintain.content.ApCode.view",
			"detail"=>"maintain.content.ApCode.detail",
			"layout"=>"maintain.content.ApCode.layout",
		);		
		
		public function __construct(){				
			parent::__construct();
		}

		protected function Action(){	
			switch($this->content){
				case "list":$this->listdata();break;				
				case "save":$this->save();break;					
				case "update":$this->update();break;	
				case "delete":$this->deldata();break;				
			}
		}
		
		private function update(){			
			header('Content-Type: application/json; charset=utf-8');	
			$id = @(int)$_GET["id"];
			$record = array();
			$record["name"] = $_POST["name"];
			$IsOk = $this->DB->AutoExecute("apcode",$record, 'UPDATE', "id=$id");
			
			$result["success"] = false;
			$result["msg"] = "修改失敗!";
			if($IsOk){
				$result["success"] = true;	
				$result["msg"] = "修改成功!";
			}
			echo json_encode($result);
			exit;
		}
		private function deldata(){
			header('Content-Type: application/json; charset=utf-8');
			$id = @(int)$_POST["id"];
			$this->DB->Execute("delete from apcode where id = $id ");
			$result["success"] = true;
			echo json_encode($result);
			exit;
		}
		
		private function listdata(){
			header('Content-Type: application/json; charset=utf-8');	
			$type = $_GET["type"];
			$stmt = $this->DB->Prepare("select count(*) as cnt from apcode where type=? ");			
			$total = $this->DB->Execute($stmt,array($type))->fields["cnt"];
						
			if($total>0){				
				$size = max(10,@(int)$_POST["rows"]);
				$page = max(0,@(int)$_POST["page"]-1)*$size;
				
				$stmt = $this->DB->Prepare("select * from apcode where type=? order by LENGTH(name),name desc limit $page,$size ");
				$rs = $this->DB->Execute($stmt,array($type));
				$result = [];
				foreach($rs as $k => $v){
					$row = array(
						"id"=>$v["id"],
						"name"=>$v["name"]
					);				
					$result["rows"][] = $row;
				}
			}
			$result["total"] = $total;						
			echo json_encode($result);
			exit;
		}
		
		private function save(){
			$insertid = 0 ;
			try{
				$stmt = $this->DB->Prepare('insert into apcode (type,name) values (?,?)');
				if($this->DB->Execute($stmt,array($_REQUEST["type"],$_REQUEST["name"]))){
					$result["success"] = true;
					$result["msg"] = "新增成功!";
				}else{
					$result["success"] = false;
					$result["msg"] = "新增失敗!".$this->DB->ErrorMsg();	
				}
				
			}catch (exception $e){}			
			echo json_encode($result);
			exit;			
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