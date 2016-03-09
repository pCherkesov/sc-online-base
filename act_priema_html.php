<?php
require_once("init.php");

if (!isset($_GET['id_r'])) $print_id = $insert_id; else $print_id = $_GET['id_r'];
	
$query="SELECT DATE_FORMAT(r.date, '%d.%m.%Y') AS date, t.type, b.brand, m.model, c.client, c.client_tel_0, r.client_fio, r.client_tel, r.counter, r.serial, p.prin, r.defect, r.complect, r.pass
FROM `".$S_CONFIG['prefix']."remont` AS r, `".$S_CONFIG['prefix']."type` AS t, `".$S_CONFIG['prefix']."model` AS m, `".$S_CONFIG['prefix']."client` AS c, `".$S_CONFIG['prefix']."brand` AS b, `".$S_CONFIG['prefix']."prin` AS p
WHERE t.id_type=m.id_type and m.id_brand=b.id_brand and r.id_client=c.id_client and r.id_model=m.id_model and r.id_prin=p.id_prin and r.id_r=".$print_id."
LIMIT 1";

$result = mysql_query($query) or exit(mysql_error());

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $report = file_get_contents("Forms/act_priema_new.html");

    $report = str_replace("{%time%}", date("H:i"), $report);
    $report = str_replace("{%num%}", $print_id, $report);
    $report = str_replace("{%date%}", $line['date'], $report);

    if ($line['client'] == "÷/ë"){
        $client_fio = $line['client_fio'];
        $client_tel = $line['client_tel'];
    }
    else {
        $client_fio = $line['client'];
        $client_tel = $line['client_tel_0'];
    }
    $report = str_replace("{%client%}", iconv("cp1251", "UTF-8", $client_fio), $report);
    $report = str_replace("{%client_tel%}", iconv("cp1251", "UTF-8", $client_tel), $report);
    $report = str_replace("{%device%}", iconv("cp1251", "UTF-8", $line['type']."\n".$line['brand']." ".$line['model']), $report);
    $report = str_replace("{%serial%}", iconv("cp1251", "UTF-8", $line['serial']), $report);
    if(isset($pass)) $report = str_replace("{%passwd%}", iconv("cp1251", "UTF-8", $pass), $report);
    else $report = str_replace("{%passwd%}", iconv("cp1251", "UTF-8", $line['pass']), $report);
    
    $report = str_replace("{%defect%}", iconv("cp1251", "UTF-8", $line['defect']), $report);
    $report = str_replace("{%complect%}", iconv("cp1251", "UTF-8", $line['complect']), $report);

    $report = str_replace("{%prin%}",iconv("cp1251", "UTF-8",  $line['prin']), $report);
}
echo $report;
?>
