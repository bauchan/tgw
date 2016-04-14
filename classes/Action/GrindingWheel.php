<?		
	
	namespace Action;			
	
	class GrindingWheel extends DefaultAction{
		
		protected $name = "GrindingWheel";			
		
		protected $content = "layout";			
		
		protected $view = "maintain.view.index";	
		
		protected $action = "";			
		
		protected $lookMap = Array(
			"list"=>"",
			"delete"=>"",
			"save"=>"",			
			"update"=>"",
			"view"=>"maintain.content.grindingwheel.view",
			"detail"=>"maintain.content.grindingwheel.detail",
			"layout"=>"maintain.content.grindingwheel.layout",
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
			$record["d"] = (double)$_POST["d"];
			$record["t"] = (double)$_POST["t"];
			$record["h"] = (double)$_POST["h"];
			$record["e"] = (double)$_POST["e"];
			$record["f"] = (double)$_POST["f"];
			$record["g"] = (double)$_POST["g"];
			$record["p1"] = (double)$_POST["p1"];
			$record["p2"] = (double)$_POST["p2"];
			$record["shape"] = $_POST["shape"];
		
			$IsOk = $this->DB->AutoExecute("grindingwheel",$record, 'UPDATE', "id=$id");
			
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
			$this->DB->Execute("delete from grindingwheel where id = $id ");
			$result["success"] = true;
			echo json_encode($result);
			exit;
		}
		
		private function listdata(){
			header('Content-Type: application/json; charset=utf-8');
			$where = "where 1=1 ";
			$param = array();
			foreach(json_decode($_POST["filterRules"]) as $k=>$v){								
				if($v->op=="equal"){
					$where .= "and {$v->field} = ? ";
				}else if($v->op=="less"){
					$where .= "and {$v->field} <= ? ";
				}else if($v->op=="greater"){
					$where .= "and {$v->field} >= ? ";
				}else if($v->op=="notequal"){
					$where .= "and {$v->field} != ? ";
				}else if($v->op=="contains"){
					$where .= "and {$v->field} like ? ";
				}
				if($v->op=="contains"){
					$param[] = "%{$v->value}%";
				}else{
					$param[] = $v->value;
				}				
			}
			
			$total = @$this->DB->Execute("select count(*) as cnt from grindingwheel $where ",$param)->fields["cnt"];
						
			$result = [];
			$result["rows"] = [];			
			if($total>0){				
				$size = max(10,@(int)$_POST["rows"]);
				$page = max(0,@(int)$_POST["page"]-1)*$size;
				
				$rs = $this->DB->Execute("select * from grindingwheel $where limit $page,$size",$param);				
				foreach($rs as $k => $v){
					$row = array(
						"id"=>$v["id"],
						"d"=>$v["d"],
						"t"=>$v["t"],
						"h"=>$v["h"],
						"e"=>$v["e"],
						"f"=>$v["f"],
						"g"=>$v["g"],
						"p1"=>$v["p1"],
						"p2"=>$v["p2"],
						"shape"=>$v["shape"]
					);				
					$result["rows"][] = $row;
				}
			}
			$result["total"] = $total;						
			echo json_encode($result);
			exit;
		}
		
		private function save(){
			$stmt = $this->DB->Prepare('insert into grindingwheel (d,t,h,e,f,g,p1,p2,shape) values (?,?,?,?,?,?,?,?,?)');
			$row = array(
				(double)$_POST["d"],
				(double)$_POST["t"],
				(double)$_POST["h"],
				(double)$_POST["e"],
				(double)$_POST["f"],
				(double)$_POST["g"],
				(double)$_POST["p1"],
				(double)$_POST["p2"],
				$_POST["shape"]
			);

			if($this->DB->Execute($stmt,$row)){
				$result["success"] = true;
				$result["msg"] = "新增成功!";	
			}else{
				$result["success"] = false;
				$result["msg"] = "新增失敗!".$this->DB->ErrorMsg();				
			}			
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