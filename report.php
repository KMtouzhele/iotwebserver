<?php
	$filename = 'report.xml';
	if(isset($_GET['l'], $_GET['p'], $_GET['c'], $_GET['ts'])){
		$str = "";
		if(file_exists($filename)) {
			$str = file_get_contents($filename);
		}
		
		if(strlen($str) == 0) {
			$str = "<?xml version='1.0' encoding='UTF-8'?> \n<records></records>";
		}
		
		$light = $_GET['l'];
		$power = $_GET['p'];
		$collision = $_GET['c'];
		$timestamp = $_GET['ts'];
		$newData = "\n<record><light>". $light . "</light><power>". $power . "</power><collision>". $collision ."</collision><devicetimestamp>".$timestamp."</devicetimestamp><servertimestamp>".date('Y-m-d H:i:s')."</servertimestamp></record>\n</records>"; 	
		$str = str_replace("</records>", $newData, $str);
		
		file_put_contents($filename, $str);
		echo 1;
	}
	echo 0;
?>
