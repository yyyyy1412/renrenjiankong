<?php
require './inc.php';

@header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>人人影视传输监控</title>
  <link href="//lib.baomitu.com/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="//lib.baomitu.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <script src="//lib.baomitu.com/jquery/1.12.4/jquery.min.js"></script>
  <script src="//lib.baomitu.com/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <!--[if lt IE 9]>
    <script src="//lib.baomitu.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//lib.baomitu.com/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<div class="container">
<div class="col-xs-12 col-md-10 center-block" style="float: none;">
<div class="header">
	<h3 class="text-muted" align="center">人人影视传输监控</h3>
</div>
<hr>﻿
	<div class="list-group">
		<div class="list-group-item"><b>总下载速度：</b><span id="dlspeedall"></span></div><div class="list-group-item"><b>总下载速度：</b><span id="upspeedall"></span></div>
	</div>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
  <thead><tr><th>节点名称</th><th>下载速度</th><th>上传速度</th></tr></thead>
  <tbody>
<?php for($i=0;$i<count($serverList);$i++){?>
	<tr><td><b><?php echo $serverList[$i][0]?></b></td><td><span id="dlspeed<?php echo $i?>">0</span></td><td><span id="upspeed<?php echo $i?>">0</span></td></tr>
<?php }?>
  </tbody>
  </table>
</div>
</div>
</div>
<script>
var size2String = function(e, t, n) {
					var o, r;
					return o = parseInt(e / t) + ".", r = parseInt(e % t * 10 / t), o + r + n
				}
var formatSize = function(e) {
					return e < 1024 ? e + "B" : e > 1099511627776 ? size2String(e, 1099511627776, "TB") : e > 1073741824 ? size2String(e, 1073741824, "GB") : e > 1048576 ? size2String(e, 1048576, "MB") : size2String(e, 1024, "KB")
				}
function loadServer(){
	$.get("ajax.php?r="+Math.random(1), function(data){
		if(data.code == 0){
			var dlspeed = 0;
			var upspeed = 0;
			$.each(data.data, function(k, v) {
				if(v.code==0){
					$("#dlspeed"+k).html(formatSize(v.dlspeed));
					$("#upspeed"+k).html(formatSize(v.upspeed));
					dlspeed+=v.dlspeed;
					upspeed+=v.upspeed;
				}
			});
			$("#dlspeedall").html(formatSize(dlspeed));
			$("#upspeedall").html(formatSize(upspeed));
		}
		setTimeout("loadServer()", 1000);
	}, 'json');
}
</script>
<script>loadServer()</script>
</body>
</html>