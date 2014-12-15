<?php
#=====================================================================
#============================ Error ==================================
function setUp() {
    set_error_handler(function($errno, $errstr, $errfile, $errline) {
        throw new RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
    });
}
function tearDown() {
    restore_error_handler();
}
#=====================================================================
#============================ Encryption =============================
	#TODO
#=====================================================================
#============================ Save / Load ============================
function save(){
	setcookie("trapfun_data", makestate());
}
function load(){
	if(isset($_COOKIE["trapfun_data"])){
		return $_COOKIE["trapfun_data"];
	}else{return 1;}
}
function newgame(){
	$d = time();
	$d .= time();
	$d .= "0003000000000000000000000000000000000001111111111111111111111111";
	return $d;
}
#=====================================================================
#============================ Process ================================
function pad($n,$t){
	$odd = $t-strlen($n);
	if($odd>0){
		$padding = str_repeat("0",$odd);
		return $padding . $n;
	} elseif($odd<0){return str_repeat("9",$t);}
	return $n;
}
function makestate(){
	global $time_F, $creds, $tokens, $theme, $badboy, $term, $stuff, $likes;
	$d = (string)pad(time(),10);
	$d .= (string)pad($time_F,10);
	$d .= (string)pad($creds,5);
	$d .= (string)pad($tokens,3);
	$d .= (string)pad($theme,2);
	$d .= (string)pad($badboy,2);
	$d .= (string)pad($term,2);
	$d .= (string)pad($stuff,25);
	$d .= (string)pad($likes,25);
	return $d;
}	
function readstate($data){
	global $time_L, $time_F, $creds, $tokens, $theme, $term, $badboy, $stuff, $likes;
	$time_L = intval(substr($data,0,10));
	$time_F = intval(substr($data,10,10));
	$creds = intval(substr($data,20,5));
	$tokens = intval(substr($data,25,3));
	$theme	= intval(substr($data,28,2));
	$badboy = intval(substr($data,30,2));
	$term = intval(substr($data,32,2));
	$stuff = intval(substr($data,34,25));
	$likes = substr($data,59,25);
}
?>