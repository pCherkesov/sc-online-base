<?php

$sms_status = array(
	"0" => "Отправлено", 
	"1" => "Доставлено",
	"8" => "Ошибка"
);


$query = "SELECT DATE_FORMAT(`s`.`date_s`, '%d.%m.%Y %H:%i') as `date_s`, `s`.`text_s`, `s`.`status_s`, `s`.`id_smsc`, 
`r`.`id_r`, `r`.`id_client`, `r`.`client_fio`, `r`.`client_tel`, `c`.`client`, `c`.`client_tel_0`,`w`.`worker`
FROM `".$S_CONFIG['prefix']."sms` as `s`, `".$S_CONFIG['prefix']."remont` as `r`, `".$S_CONFIG['prefix']."client` as `c`, `".$S_CONFIG['prefix']."worker` AS `w`
WHERE `r`.`id_r` = `s`.`id_r` AND `r`.`id_client` = `c`.`id_client` AND `w`.`id_worker` = `s`.`author_s`
ORDER BY `s`.`date_s` DESC";

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));

$data = array();

if(isset($_REQUEST['search'])) $search = $_REQUEST['search'];
else $search = "";

while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	if ($line['id_client']=="1") {
		$line['client'] = $line['client_fio'];
		$line['phone'] = $line['client_tel'];
	} else {
		$line['phone'] = $line['client_tel_0'];
	}
	
	$line['status_s'] = $sms_status[$line['status_s']];
	$data[] = $line;
}


// print_r($search);
render($data = array('data' => $data, 'search' => $search));
?>
