<?php
require './inc.php';

@header('Content-Type: text/html; charset=UTF-8');

$data = array();
foreach($serverList as $server){
	$res = yyets_connection($server[1],$server[2]);
	if(is_array($res)){
		$data[] = array('code'=>0,'ip'=>$server[0],'dlspeed'=>$res['dlspeed'],'upspeed'=>$res['upspeed']);
	}else{
		$data[] = array('code'=>-1,'ip'=>$server[0]);
	}
}
$result = array('code'=>0, 'data'=>$data);
echo json_encode($result);