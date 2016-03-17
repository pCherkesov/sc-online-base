<?php
require_once("init.php");

if (!isset($_GET['id_r'])) $print_id = $insert_id; else $print_id = $_GET['id_r'];
	
$query="SELECT DATE_FORMAT(r.date, '%d.%m.%Y') AS date, t.type, b.brand, m.model, c.client, c.client_tel_0, r.client_fio, r.client_tel, r.counter, r.serial, p.prin, r.defect, r.complect, r.pass
FROM `".$S_CONFIG['prefix']."remont` AS r, `".$S_CONFIG['prefix']."type` AS t, `".$S_CONFIG['prefix']."model` AS m, `".$S_CONFIG['prefix']."client` AS c, `".$S_CONFIG['prefix']."brand` AS b, `".$S_CONFIG['prefix']."prin` AS p
WHERE t.id_type=m.id_type and m.id_brand=b.id_brand and r.id_client=c.id_client and r.id_model=m.id_model and r.id_prin=p.id_prin and r.id_r=".$print_id."
LIMIT 1";

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysql_error($S_CONFIG['link']));

while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $report = file_get_contents("Forms/act_priema_new.html");

    $report = str_replace("{%time%}", date("H:i"), $report);
    $report = str_replace("{%num%}", $print_id, $report);
    $report = str_replace("{%date%}", $line['date'], $report);

    if ($line['client'] == "ч/л"){
        $client_fio = $line['client_fio'];
        $client_tel = $line['client_tel'];
    }
    else {
        $client_fio = $line['client'];
        $client_tel = $line['client_tel_0'];
    }
    $report = str_replace("{%client%}", $client_fio, $report);
    $report = str_replace("{%client_tel%}", $client_tel, $report);
    $report = str_replace("{%device%}", $line['type']."\n".$line['brand']." ".$line['model'], $report);
    $report = str_replace("{%serial%}", $line['serial'], $report);
    if(isset($pass)) $report = str_replace("{%passwd%}", $pass, $report);
    else $report = str_replace("{%passwd%}", $line['pass'], $report);
    
    $report = str_replace("{%defect%}", $line['defect'], $report);
    $report = str_replace("{%complect%}", $line['complect'], $report);

    $report = str_replace("{%prin%}", $line['prin'], $report);
}
echo $report;
?>
