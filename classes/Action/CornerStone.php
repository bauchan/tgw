<?		
	
	namespace Action;			
	
	class CornerStone extends DefaultAction{
		
		protected $name = "CornerStone";			
		
		protected $content = "layout";			
		
		protected $view = "maintain.view.index";	
		
		protected $action = "";			
		
		protected $lookMap = Array(
			"list"=>"",
			"delete"=>"",
			"save"=>"",			
			"update"=>"",
			"view"=>"maintain.content.cornerstone.view",
			"detail"=>"maintain.content.cornerstone.detail",
			"layout"=>"maintain.content.cornerstone.layout",
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
			$record["b"] = (double)$_POST["b"];
			$record["l"] = (double)$_POST["l"];
			$record["a"] = (double)$_POST["a"];
			$record["w"] = (double)$_POST["w"];
			$record["u"] = (double)$_POST["u"];
			$record["t"] = (double)$_POST["t"];
			$record["r1"] = (double)$_POST["r1"];
			$record["r2"] = (double)$_POST["r2"];
			$record["shape"] = $_POST["shape"];
		
			$IsOk = $this->DB->AutoExecute("cornerstone",$record, 'UPDATE', "id=$id");
			
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
			$this->DB->Execute("delete from cornerstone where id = $id ");
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
			
			$total = @$this->DB->Execute("select count(*) as cnt from cornerstone $where ",$param)->fields["cnt"];
						
			$result = [];
			$result["rows"] = [];			
			if($total>0){				
				$size = max(10,@(int)$_POST["rows"]);
				$page = max(0,@(int)$_POST["page"]-1)*$size;
				
				$rs = $this->DB->Execute("select * from cornerstone $where limit $page,$size",$param);				
				foreach($rs as $k => $v){
					$row = array(
						"id"=>$v["id"],
						"b"=>$v["b"],
						"l"=>$v["l"],
						"a"=>$v["a"],
						"w"=>$v["w"],
						"u"=>$v["u"],
						"t"=>$v["t"],
						"r1"=>$v["r1"],
						"r2"=>$v["r2"],
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
			$stmt = $this->DB->Prepare('insert into cornerstone (d,t,h,e,f,g,p1,p2,shape) values (?,?,?,?,?,?,?,?,?)');
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