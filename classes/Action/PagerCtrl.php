<?	
	class PagerCtrl{
		private $np = 1;		
		private $totalpage  = 0;		
		private $pageIndex  = 0;
		
		function PagerCtrl($np=1,$totaltuple=0,$pagesize=10){
			$this->totalpage = (int)ceil((double)$totaltuple/$pagesize);
			$this->np = max(1,min($np,$this->totalpage));
			$this->pageIndex = ($this->np-1)*$pagesize;
		}
		 
		public function getNp(){
			return $this->np;
		}
		public function getPrev(){
			return max($this->np-1,1);
		}
		
		public function getNext(){
			return min($this->np+1,$this->totalpage);
		}
		
		public function getPageIndex(){
			return $this->pageIndex;
		}
		public function getPageList(){		    	
			$page = array();
			$s = max(1,$this->np-4); 
			$e = min($this->np+5,$this->totalpage);			
			$s = $e-9;    	
			if($s<1){
				$s = 1;
				$e = min($s+9,$this->totalpage);
			}    	    	    	
			
			for($i=$s;$i<=$e;$i++){				
				$page[]=$i;
			}
			return $page;
		}
	}
	
	//$p = new Pager(19,999,10);
	//echo $p->getNp();
	//print_r($p->getPageList2());
?>
