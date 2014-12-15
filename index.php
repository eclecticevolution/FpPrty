<?php
#===========================================================
$key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
$salt = "SaLtYsTrInG";
$time_L=0;
$time_F=0;
$creds=0;
$tokens=0;
$theme=0;
$badboy=0;
$term=0;
$stuff=0;
$likes=0;
$cLvlUp=200;
$cLvlDn=100;
require_once "data_handle.php";
require_once "actions.php";
setUp();
#===========================================================
$data=load();
if($data){
	if ($data==1){
		if(isset($_POST["agreed"])){
			readstate(newgame());
			save();
			buildPage();
		}else{
			buildHead();
			include "legal_BS.html";
		}
	}else{
		readstate($data);
		if ($term>30){
			if(!isset($_POST["action"])){
				$tokens=0;
				$creds=0;
				$badboy-=11;
				$term=0;
				learn(True);
				save();
				buildPage("Bad little girl, trying to avoid her punishment!...");
			}elseif($_POST["action"]=="yes"){
				$term=0;
				$badboy-=40;
				learn();
				save();
				buildPage("Good girl, its better to admit you were bad, isn't it?");
			}elseif($_POST["action"]=="no"){
				$creds-=50;
				if ($creds<0){$creds=0;}
				$badboy-=20;
				$term=0;
				learn(True);
				save();
				buildPage("Naughty, naughty...");
			}else{
				$tokens=0;
				$creds=0;
				$badboy-=11;
				$term=0;
				learn(True);
				save();
				buildPage("Bad little girl, trying to avoid her punishment!...");
			}
		}elseif ($term>19){
			if(!isset($_POST["action"])){
				if(naughty(10)){
					$term=0;
					learn(True);
					save();
					buildPage("Honey, if you continue to leave during orders, I WILL punish you...");
				}else{punish();}
			}elseif($_POST["action"]=="yes"){
				good();
			}elseif($_POST["action"]=="no"){
				if(naughty(2)){
					$term=0;
					learn(True);
					save();
					buildPage("I'm dissapointed in you, I didn't think you'd give up so easily...");
				}else{punish();}
			}else{
				if(naughty(10)){
					$term=0;
					learn(True);
					save();
					buildPage("Honey, if you continue to leave during orders, I WILL punish you...");
				}else{punish();}
			}
		}
		elseif(isset($_POST["action"])){
			$a=$_POST["action"];
			if($a=="roll"){
				good(16);
			}elseif($a=="test"){
				good(26);
			}elseif($a=="daily"){
				daily();
			}elseif($a=="hard"){
				harder();
			}elseif($a=="final"){
				good(6);
			}elseif($a=="set"){
				set();
			}elseif($a=="yes"){
				good();
			}elseif($a=="no"){
				$term=0;
				learn(True);
				save();
				buildPage();
			}else{
				buildPage();
			}
		}else{
			buildPage();
		}
	}
}else{
	echo "Something broke your save! Reload the page to Start again...";
}







#===========================================================
tearDown();?>