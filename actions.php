<?php
#=====================================================================
#============================ Utility ================================
function valid(){
	global $likes;
	$n=array();
	for($i=0;$i<=strlen($likes)-1;$i++){
		$char = substr( $likes,$i,1);
		if ($char>0){$n[]=$i;}
	}
	return array_rand($n);
}
function naughty($p){
	global $badboy;
	$badboy+=$p;
	if ($badboy>95){$badboy=95;}
	return ($badboy<85);
}
function learn($fail=False){
	global $likes, $cLvlUp, $cLvlDn;
	if (!(isset($_POST["hard"]) and isset($_POST["subject"]))){return;}
	$sub=$_POST["subject"];
	$d=$_POST["hard"];
	if($fail and $d<substr($likes,$sub)){
		if(mt_rand(0,$cLvlDn)==0){
			$likes[$sub]=substr($likes,$sub)+1;
		}
	}elseif($d>substr($likes,$sub)){
		if(mt_rand(0,$cLvlUp)==0){
			$likes[$sub]=substr($likes,$sub)+1;
		}
	}
}
function check_items($node){
	global $stuff;
	$have= ~$stuff;
	$owned = (($node->req)&$have);
	return $owned==0;
}
function gen_task($type,$mod=0){
	global $likes;
	$d=substr($likes,$type,1);
	$n=mt_rand(0,8);
	$n=2-floor(sqrt(mt_rand(0,8)));
	if(mt_rand(0,1)){$n=-1*$n;}
	$d=$n+$d+$mod;
	if ($d>9){$d=9;}
	if ($d<1){$d=1;}
	$xml = simplexml_load_file("tasks/" . $type . ".xml");
	$trees = $xml->xpath("/tasks/t[d='" . $d ."']");
	$trees = array_values(array_filter($trees,"check_items"));
	if (sizeOf($trees)==0){return gen_task($type,$mod-1);}
	$choice=mt_rand(0,sizeof($trees)-1);
	return $trees[$choice];
}
#=====================================================================
#============================ Settings ===============================
function harder(){
	global $likes;
	$new = "";
	for($i=0;$i<=strlen($likes);$i++){
		$char = substr( $likes,$i,1);
		if ($char>0){$char+=2;}
		$new.=$char;
	}
	$likes=$new;
	save();
	buildPage("Careful what you wish for slut.");
}
function set(){
	global $likes, $stuff;
	$nlikes="";
	$nstuff="";
	for($i=0;$i<=24;$i++){
		if(isset($_POST["menu_f_".$i])){
			if (substr($likes,$i,1)>0){
				$nlikes .= substr($likes,$i,1);
			}else{$nlikes .= "1";}
		}else{$nlikes .= "0";}
		if(isset($_POST["menu_i_".$i])){
			$nstuff = "1"+$nstuff;
		}else{$nstuff = "0"+$nstuff;}
	$stuff=bindec($nstuff);
	$likes=$nlikes;
	save();
	}
	buildPage();
}
#=====================================================================
#============================ Endings ================================
function reward($mod=0){
	global $creds, $tokens, $term;
	$term=0;
	$n=9-floor(sqrt(mt_rand(0,99)));
	if ($n==(9-$mod)){
		$tokens+=1;
		save();
		buildPage("Who's a good girl? Enjoy you're gift ;)");
	}else{
		$creds+=($n);
		save();
		buildPage("Good Job!");
	}
}
function punish(){
	global $term;
	$term = 31;
	save();
	$message = "Do to your severe disobedience lately, I've decided to punish you. Don't make this harder by continuing to be disobedient...";
	buildPage($message,valid(),3);
}
#=====================================================================
#============================ Game ===================================
function daily(){
	#TODO
}
function good($new=False){
	global $term, $creds, $tokens, $badboy;
	if($new){
		if($new==16){
			if($creds<5){
				buildPage("Maybe if you work harder I'll let you...");
				return;
			}else{$creds-=5;}
		}elseif($new==6){
			if($tokens<1){
				buildPage("You have NOT earned that yet...");
				return;
			}else{$tokens-=1;}
		}
		$term=$new+mt_rand(-3,3);
	}else{
		$term-=1;
		learn();
	}
	$badboy-=1;
	save();
	if ($term>20){buildPage(False,valid(),1);}
	elseif($term==20){reward();}
	elseif($term>10){buildPage(False,valid(),-1);}
	elseif($term==10){reward(1);}
	elseif($term>1){buildPage(False,valid(),0);}
	elseif($term==1){buildPage(False,24,1);}
	else{buildPage();}
}
#=====================================================================
#============================ Page Builder ===========================
function buildHead($mes=False){
global $creds, $tokens;
	include "header_A.html";
	echo $creds . " / " . $tokens;
	include "header_B.html";
	if($mes){
		echo '<div id="feedback" class="alert alert-dismissable alert-warning">
<button type="button" class="close" onclick="$(\'#feedback\').slideUp()">×</button><h4>' . $mes . '</h4></div>';
	}
}
function buildPage($mes=False,$sub=-1,$mod=0){
	buildHead($mes);
	if($sub>-1){
		$task=gen_task($sub,$mod);
		include "game_head.html";
		$pic = $task->xpath('pic');
		$pic = strip_tags($pic[0] -> asXML());
		$txt = $task->xpath('text');
		$txt = strip_tags($txt[0] -> asXML());
		$d = $task->xpath('d');
		$d = strip_tags($d[0] -> asXML());
		echo '<img style="display:block;margin:auto;max-width:80%;height:auto" class="img-rounded" src="' . $pic . '"></img>';
		echo '<blockquote>'.$txt.'</blockquote>';
		echo '<input type="hidden" name="hard" value="'.$d.'"/>';
		echo '<input type="hidden" name="subject" value="'.$sub.'"/>';
		include "game_foot.html";
	}else{
		include "menu.html";
	}
}
?>