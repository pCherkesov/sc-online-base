<?php
// Load JsHttpRequest backend.
require_once "JsHttpRequest/JsHttpRequest.php";
// Create main library object. You MUST specify page encoding!
$JsHttpRequest = new JsHttpRequest("windows-1251");
//
require("init.php");

$query="SELECT `string` FROM `".$S_CONFIG['prefix']."remont` WHERE `id_r` = ".@$_REQUEST['idr'];
$result = mysql_query($query) or exit(mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$status = $line['string'];
}

//$status = @$_REQUEST['stat'];

if ($status{@$_REQUEST['q']} == "Y") $status{@$_REQUEST['q']} = "N"; else $status{@$_REQUEST['q']} = "Y";

$idr = $status{@$_REQUEST['q']};

$query1 = "UPDATE `".$S_CONFIG['prefix']."remont` SET `string` = '".$status."' WHERE `id_r` = ".@$_REQUEST['idr']." LIMIT 1";
$r1 = mysql_query($query1)or exit(mysql_error());


// Store resulting data in $_RESULT array (will appear in req.responseJs).
$_RESULT = array(
  "q"     => @$_REQUEST['q'],
  "idr"   => @$idr,
  "status"=> @$status
); 
// Below is unparsed stream data (will appear in req.responseText).


?>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>
