<?php
// Load JsHttpRequest backend.
require_once "JsHttpRequest/JsHttpRequest.php";
// Create main library object. You MUST specify page encoding!
$JsHttpRequest = new JsHttpRequest("windows-1251");
//
require "config.php";
// Соединение, выбор БД
if(!mysql_connect("localhost",$S_CONFIG['user'],$S_CONFIG['pass']))
{
    echo "<center><H1>!!! Fatal error connect mySQL server !!!</H1></center>";
    exit();
}
else{
    if(mysql_select_db($S_CONFIG['db']))
    {
    	echo "<center><H3>Connected database ".$S_CONFIG['db']."</H3></center>";
    }
    else{
    	echo "<center><H1>Disconnected database =OnLine=</H1></center>";
    }
}
mysql_query('SET character_set_results="cp1251"');
mysql_query("SET NAMES cp1251"); 

if (@$_REQUEST['worker'] == '1') {
		$query="UPDATE `".$S_CONFIG['prefix']."remont` SET `id_worker`='".@$_REQUEST['worker']."' WHERE `id_r`=".@$_REQUEST['idr']." LIMIT 1";
    	$r=mysql_query($query)or exit(mysql_error());

		$worklist = "Работает: <select name='worker' id='worker'>";
		$query_worker = "SELECT * FROM `".$S_CONFIG['prefix']."worker` WHERE hidden='N' GROUP BY `worker` ASC";
		$result_worker = mysql_query($query_worker) or exit(mysql_error());
		while($line_worker = mysql_fetch_assoc($result_worker)){
			 $worklist .= "<option value=".$line_worker['id_worker'].">".$line_worker['worker']."</option>";
		}
		$worklist .= "</select>";
		$worklist .= "<img src='Images/Icon/b_edit.png' border='0' name='save_worker'
		onclick=\"doWorker(document.getElementById('worker')[document.getElementById('worker').selectedIndex].value,".@$_REQUEST['idr'].");\">";
		$act='0';
}
else {
		$query="UPDATE `".$S_CONFIG['prefix']."remont` SET `id_worker`='".@$_REQUEST['worker']."' WHERE `id_r`=".@$_REQUEST['idr']." LIMIT 1";
    	$r=mysql_query($query)or exit(mysql_error());
		$act='1';
		$worklist="";
}

// Store resulting data in $_RESULT array (will appear in req.responseJs).
$_RESULT = array(
  "idr"     => @$_REQUEST['idr'], 
  "worker"  => @$_REQUEST['worker'],
  "act"     => $act,
  "worklist"=> $worklist
); 
// Below is unparsed stream data (will appear in req.responseText).


?><title></title>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>
