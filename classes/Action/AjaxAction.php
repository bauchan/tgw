
<?
	include "Action.php";
	
	class AjaxAction extends DefaultAction
	{
		protected $name = "ajax";			
		
		protected $content = "list";			
		
		protected $view = "";	
		
		protected $action = "";			
		
		protected $lookMap = Array(
			"ajax"=>""
		);		

		protected function Action(){			
			$type = @$_REQUEST["type"];
			$rows = array();
			
			if($type=="getDesigner"){
				$id = @$_REQUEST["locale_code"];
				$id = @(int)$this->localeMap[$id];
				$sql = "select * from designer where locale_id=?";					
				$this->data = $this->DB->Execute($sql,array($id));			
				foreach($this->data as $k => $v) { 
					$rows[] = $v;
				}
			}
			header('Content-Type: application/json; charset=utf-8');			
			echo json_encode($rows);			
			exit;
		}
	}		
?>		