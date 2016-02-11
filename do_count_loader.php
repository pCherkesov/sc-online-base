<?php
// Load JsHttpRequest backend.
require_once "JsHttpRequest/JsHttpRequest.php";
// Create main library object. You MUST specify page encoding!
$JsHttpRequest = new JsHttpRequest("windows-1251");
//
require("init.php");

//$query="SELECT `counter` FROM `".$S_CONFIG['prefix']."remont` WHERE `id_r` = ".@$_REQUEST['idr'];
//$result = mysql_query($query) or exit(mysql_error());
//while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
//	$counter = $line['counter'];
//}

$query1 = "UPDATE `".$S_CONFIG['prefix']."remont` SET `counter` = '".$_REQUEST['count']."' WHERE `id_r` = ".$_REQUEST['idr']." LIMIT 1";
$r1 = mysql_query($query1)or exit(mysql_error());


// Store resulting data in $_RESULT array (will appear in req.responseJs).
$_RESULT = array(
  "idr"   => @$_REQUEST['idr'],
  "count" => @$_REQUEST['count']
); 
// Below is unparsed stream data (will appear in req.responseText).


?>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>
