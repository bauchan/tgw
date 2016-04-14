<?php
	//ini_set('display_errors', '1');
	//echo ini_get('display_errors');
	namespace DAO;	
	
	class IpTableDAO{
		public static function checkIp(){
			$IPTABLE = Array("127.0.0.1","203.70.51.40-254","192.168.1.1");
			$isCorrect = false;	
			$ipnode = preg_split('/[.]/',IpTableDAO::getUserIP(), -1, PREG_SPLIT_NO_EMPTY);	
						
			foreach($IPTABLE as $v){
				$isCorrect = true;	
				$ranges = preg_split('/[.]/',$v, -1, PREG_SPLIT_NO_EMPTY);	
				$flag = 0;	
				
				foreach($ranges as $ran){
					$ipm = 0;
					$range_s=0xFE;
					$range_e=0xFE;
					
					$ipm=(int)$ipnode[$flag];					
					if(strpos($ran,"-")>-1){
						$rans = preg_split('/[-]/',$ran, -1, PREG_SPLIT_NO_EMPTY);
						$range_s = (int)$rans[0];
						$range_e = (int)$rans[1];
					}else{
						if("*"===$ran){
							$range_s = 0;
							$range_e = 0xFF;
						}else{
							$range_s = @(int)$ran;
							$range_e = @(int)$ran;
						}	
					}
					if($ipm<$range_s||$ipm>$range_e){
						$isCorrect=false;
						break;
					}					
					$flag++;						
				}								
				if($isCorrect){				
					break;
				}
			}
			return $isCorrect;			
		}
		
		private static function getUserIP()
		{
			$client  = @$_SERVER['HTTP_CLIENT_IP'];
			$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
			$remote  = $_SERVER['REMOTE_ADDR'];

			if(filter_var($client, FILTER_VALIDATE_IP))
			{
				$ip = $client;
			}
			elseif(filter_var($forward, FILTER_VALIDATE_IP))
			{
				$ip = $forward;
			}
			else
			{
				$ip = $remote;
			}

			return $ip;
		}
	}		
?>