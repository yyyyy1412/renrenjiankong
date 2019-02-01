<?php
error_reporting(0);

date_default_timezone_set('Asia/Shanghai');
$date = date("Y-m-d H:i:s");

require './config.php';

function get_curl($url,$post=0,$referer=0,$cookie=0,$header=0,$ua=0,$nobaody=0){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$httpheader[] = "Accept: */*";
	$httpheader[] = "Accept-Encoding: gzip,deflate,sdch";
	$httpheader[] = "Accept-Language: zh-CN,zh;q=0.8";
	$httpheader[] = "Connection: close";
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
	if($header){
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
	}
	if($cookie){
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	}
	if($referer){
		if($referer==1){
			curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
		}else{
			curl_setopt($ch, CURLOPT_REFERER, $referer);
		}
	}
	if($ua){
		curl_setopt($ch, CURLOPT_USERAGENT,$ua);
	}else{
		curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
	}
	if($nobaody){
		curl_setopt($ch, CURLOPT_NOBODY,1);
	}
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}

function yyets_login($ip,$pwd,$force=false){
	$cookie_file = './cookie/'.md5($ip).'.txt';
	if(file_exists($cookie_file) && $force==false){
		$cookie = file_get_contents($cookie_file);
	}else{
		$url = 'http://'.$ip.'/api/unlock?passwd='.urlencode($pwd);
		$data = get_curl($url,0,0,0,1);
		$arr = json_decode('{'.explode('{',$data)[1],true);
		if (array_key_exists('code',$arr) && $arr['code']==200) {
			preg_match("/session=(.*?);/i", $data, $match);
			$session = $match[1];
			$cookie = 'session='.$session.';';
			file_put_contents($cookie_file, $cookie);
		}else{
			return false;
		}
	}
	return $cookie;
}
function yyets_connection($ip,$pwd){
	$cookie = yyets_login($ip,$pwd);
	if(!$cookie){
		$cookie = yyets_login($ip,$pwd,true);
	}
	if($cookie){
		$url = 'http://'.$ip.'/api/stat';
		$data = get_curl($url,0,0,$cookie);
		$arr = json_decode($data,true);
		if (array_key_exists('code',$arr) && $arr['code']==200) {
			return array('dlspeed'=>$arr['dlspeed'],'upspeed'=>$arr['upspeed']);
		}else{
			return false;
		}
	}else{
		return false;
	}
}