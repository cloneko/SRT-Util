<?php

mb_internal_encoding('UTF-8');

$counter = 0;
$firstline = true;

$second = -79.15;
$input = file_get_contents('MarshmallowChallenge_TEDshort-end.srt');

//$second = 0;
//$input = file_get_contents('newted.srt');

$contents = explode("\r\n",$input);


foreach($contents as $line){
    if($firstline){
    	print ++$counter . "\r\n";
    	$firstline = false;
    } else {
    	if($line == ""){
    		$firstline = true;
    	} 
    	
    	if(preg_match('/[0-9][0-9]:[0-9][0-9]:[0-9][0-9],[0-9]+ --> [0-9][0-9]:[0-9][0-9]:[0-9][0-9],[0-9]+/',$line)){
    		$time = explode(' --> ',$line);
    		$begin = new microdata($time[0]);
    		$end = new microdata($time[1]);
    		
    		$begin->modify($second);
    		$end->modify($second);
    		print  $begin->format() . ' --> ' . $end->format() . "\r\n";
    	} else {
    		print $line . "\r\n";
    	}
    }
};

class microdata{

	var $timestamp = 0;
	
	function __construct($str = ''){
		$tmparray = explode(',',$str);
		$timearray = explode(':',$tmparray[0]);
		
		$this->timestamp = (($timearray[0] * 3600) + ($timearray[1] * 60) + ($timearray[2] * 1)) . '.' . $tmparray[1];

	}
	
	function modify($integer){
//		print $this->timestamp . "\n";
		$this->timestamp += $integer;
//		print $this->timestamp . "\n";
	}
	
	function format(){
		$hour = floor($this->timestamp / 3600);
		$min = floor($this->timestamp / 60) - ($hour * 3600);
		$sec = $this->timestamp - ($min * 60) - ($hour * 3600);
		$milsec = $this->timestamp - floor($this->timestamp);
		return sprintf('%02d',$hour) . ':' . sprintf('%02d',$min) . ':' . sprintf('%02d',$sec) . ',' . sprintf('%03d',($milsec*1000));
	}
	
}

?>
