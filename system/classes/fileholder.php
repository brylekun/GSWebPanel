<?php
/**
 * Ran Panel
 * http://glen-soft.com/
 * 
 * @version 2.1.0
 * @copyright (c) 2018, Dev Glenox Ran Panel
 * @build-Date 4/16/2018
 */


class fileholder {
	var $item=array();
	function __construct(){

		$fp = fopen(__PATH_CONFIGS__."itemstrtable.txt", "r");
		$line = array();
		while(!feof($fp)){
			$line[] = fgets($fp);
		}
			fclose($fp);
			$item = array();
			foreach($line as $itm){
				$so = explode("\t",$itm);
				$this->item[$so[0]] = $so;
			}
	}
	public function getName($a,$b){
		$mid = str_pad($a,3,'0',STR_PAD_LEFT);
		$sid = str_pad($b,3,'0',STR_PAD_LEFT);
		$id = "IN_{$mid}_{$sid}";
		return $this->item[$id][1];
	}
	public function getSName($a,$b){
		$mid = str_pad($a,3,'0',STR_PAD_LEFT);
		$sid = str_pad($b,3,'0',STR_PAD_LEFT);
		$id = "SN_{$mid}_{$sid}";
		return $this->item[$id][1];
	}
	


	
	
}