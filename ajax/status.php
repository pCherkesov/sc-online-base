<?php
require("init_j.php");

$query = "SELECT `string` FROM `".$S_CONFIG['prefix']."remont` WHERE `id_r` = ".@$_REQUEST['idr'];

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	$status = $line['string'];
}

if ($status{@$_REQUEST['q']} == "Y") $status{@$_REQUEST['q']} = "N"; else $status{@$_REQUEST['q']} = "Y";

$idr = $status{@$_REQUEST['q']};

$query1 = "UPDATE `".$S_CONFIG['prefix']."remont` SET `string` = '".$status."' WHERE `id_r` = ".@$_REQUEST['idr']." LIMIT 1";

$r1 = mysqli_query($S_CONFIG['link'], $query1)or exit(mysqli_error($S_CONFIG['link']));

?>
