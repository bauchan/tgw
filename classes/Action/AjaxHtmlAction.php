<?
	include "Action.php";
	
	class AjaxHtmlAction extends DefaultAction
	{
		protected $name = "ajaxhtml";			
		
		protected $content = "list";			
		
		protected $view = "maintain.content.detail.class";	
		
		protected $action = "";			
		
		protected $lookMap = Array();		

		protected function Action(){			
			$type = $_REQUEST["type"];
			$rows = array();
			
			if($type=="getClassBySC"){
				$this->view = "maintain.content.detail.class";				
				$sid = $_REQUEST["sid"];
				$cid = $_REQUEST["cid"];
				$yid = $_REQUEST["yid"];
				$sql = "SELECT d.* from detail d inner join class c on d.cid = c.id where d.sid = $sid and d.cid = $cid and d.yid = $yid";
				$this->data = $this->DB->Execute($sql);							
			}else if($type=="getClassBySCEdit"){
				$this->view = "maintain.content.detail.class2";				
				$sid = $_REQUEST["sid"];
				$cid = $_REQUEST["cid"];
				$pid = $_REQUEST["pid"];
				$yid = $_REQUEST["yid"];
				$sql = "SELECT d.* from detail d inner join class c on d.cid = c.id where d.sid = $sid and d.cid = $cid and d.yid = $yid ";
				$this->data = $this->DB->Execute($sql);											
				
				$sql = "SELECT d.*,pd.qty,pd.pid,pd.id as pdid "
					 . "from detail d "
					 . "inner join projectdetail pd on pd.did=d.id "
				     . "inner join class c on d.cid = c.id "
					 . "where d.sid = $sid and d.cid = $cid and pid=$pid ";
				$this->pdata = $this->DB->Execute($sql);											 
			}else if($type=="getChildCase"){				
				$account = $_REQUEST["staff_id"];								
				$p=$this->DB->Execute("select * from staff s INNER join staff_detail d on s.staff_id = d.staff_id where s.staff_id = '$account'")->fields;
				$departmentid = $p["staff_department"];
				
				$this->pg1 = $this->DB->Execute("select s.* from staff s inner join staff_detail d on s.staff_id = d.staff_id and d.staff_department=$departmentid and staff_status=1");
				$this->view = "maintain.content.newcase.child";					
			}						
			
		}
	}		
?>		