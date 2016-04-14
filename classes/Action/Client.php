<?		
	
	namespace Action;			
	
	class Client extends DefaultAction{
		
		protected $name = "Client";			
		
		protected $content = "layout";			
		
		protected $view = "maintain.view.index";	
		
		protected $action = "";			
		
		protected $lookMap = Array(
			"delete"=>"",
			"save"=>"",			
			"update"=>"",
			"view"=>"maintain.content.Client.view",
			"detail"=>"maintain.content.Client.detail",
			"layout"=>"maintain.content.Client.layout",
		);		
		
		public function __construct(){				
			parent::__construct();
		}

		protected function Action(){	
			//echo $this->content;
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
			$record["code"] = $_POST["code"];
			$record["name"] = $_POST["name"];
			$IsOk = $this->DB->AutoExecute("customer",$record, 'UPDATE', "id=$id");
			
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
			$this->DB->Execute("delete from customer where id = $id ");
			$result["success"] = true;
			echo json_encode($result);
			exit;
		}
		
		private function listdata(){
			header('Content-Type: application/json; charset=utf-8');			
			$total = @$this->DB->Execute("select count(*) as cnt from customer")->fields["cnt"];
						
			if($total>0){				
				$size = max(10,@(int)$_POST["rows"]);
				$page = max(0,@(int)$_POST["page"]-1)*$size;
				
				$rs = $this->DB->Execute("select * from customer limit $page,$size");
				$result = [];
				foreach($rs as $k => $v){
					$row = array(
						"id"=>$v["id"],
						"code"=>$v["code"],
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
			$stmt = $this->DB->Prepare('insert into customer (code,name) values (?,?)');
			$this->DB->Execute($stmt,array($_POST["code"],$_POST["name"]));
			$result["success"] = true;
			$result["msg"] = "新增成功!";
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