<?php
require("init_j.php");
include "../libs/smsc_api.php";

$idr = $_POST['idr'];
$tel = "7".str_replace(array(" ", "-", "(", ")"), "", $_POST['tel']);
$msg =  iconv("cp1251", "UTF-8", $_POST['msg']);

//exit($idr. " - " .$tel. " - " . $msg);

try {
        list($sms_id, $sms_cnt, $cost, $balance) = send_sms($tel, $msg, $sender = "s.cOnline");
}
catch (Exception $e) {
        echo $e->getMessage();
}
 
$result_query = "INSERT INTO `".$S_CONFIG['prefix']."sms` VALUE(0, ".$idr.", CURRENT_TIMESTAMP, '".$msg."', 1, 0);";

$results = mysqli_query($S_CONFIG['link'], $result_query) or exit(mysql_error());
?>