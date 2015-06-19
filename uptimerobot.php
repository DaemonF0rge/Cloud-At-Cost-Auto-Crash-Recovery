<?php 
//This is a PHP webhook page for uptime robot to try and restart/turn on
//your Cloud at Cost server in the event the server crashes.
//https://dns.or.ip/uptimerobot.php?auth_key=CHANGETHIS&
//For Security reason its always recommended to configure ssl
include(dirname(__FILE__) . '/cloudatcost.class.php');
//DEFINE VARARABLES
$auth_key= 'CHANGETHIS'; //For Security
$servers= [
	'MonitorID' => 'ServerID', 
	'000000000' => '000000000' //
];
$api_key=null;//set if you don't want to set in the get string
$api_login=null; //set if you don't want to set in the get string



if(isset($_GET['api_key'])&& isset($_GET['api_login'])){
	$api_key= $_GET['api_key'];
	$api_login= $_GET['api_login'];

}
$server_id=null; //leave null
//VAILDATE REQUEST
if(isset($_GET['auth_key']) && $_GET['auth_key'] == $auth_key){
	if(isset($_GET['req_type']) && isset($_GET['monitorID'])){
		$monitor_id=strval($_GET['monitorID']);
		if(isset($servers[$monitor_id])){
			$req_type= $_GET['req_type'];
			$server_id=strval($servers[$monitor_id]);
		}else{
			echo 'failure';
			exit();
		}
			
	}else{
		echo 'failure';
		exit();
	}
}else{
	echo 'failure';
	exit();
}

//PROCCESS REQUEST
//
$cac= new cloudatcost($api_key, $api_login);
	$server_l= $cac->listServers();
	$server_r= $cac->getServer(intval($server_id));
	if(isset($server_r['error'])){
		echo 'failure';
		exit();
	}
	if(isset($server_r['status']) && $server_r['status'] == 'Powered On'){
		if($cac->reboot($server_id)){
			echo 'success';
		}else{
			echo 'failure';
		}
		exit();
	}else{
		if($cac->reboot($server_id, 'poweron')){
			echo 'success';
		}else{
			echo 'failure';
		}
		exit();
	}
echo 'failure';
