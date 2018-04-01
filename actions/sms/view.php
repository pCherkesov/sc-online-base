<?php

$sms_status = array(
	"0" => "Отправлено", 
	"1" => "Доставлено",
	"8" => "Ошибка"
);


$query = "SELECT DATE_FORMAT(`s`.`date_s`, '%d.%m.%Y %H:%i') as `date_s`, `s`.`tel_s`, `s`.`text_s`, `s`.`status_s`, `s`.`id_smsc`, 
`s`.`id_r`, `r`.`id_client`, `r`.`client_fio`, `c`.`client`, `w`.`worker`
	FROM `sms` as `s` 
		LEFT JOIN `remont` AS r ON r.id_r = s.id_r 
		LEFT JOIN `client` AS c ON r.id_client = c.id_client 
		LEFT JOIN `worker` AS w ON s.author_s = w.id_worker 
	ORDER BY `s`.`date_s` DESC";

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));

$data = array();

if(isset($_REQUEST['search'])) $search = $_REQUEST['search'];
else $search = "";

while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	if ($line['id_client']=="1") {
		$line['client'] = $line['client_fio'];
	}		
	$line['phone'] = $line['tel_s'];
	$line['status_s'] = $sms_status[$line['status_s']];
	$data[] = $line;
}

render($data = array('data' => $data, 'search' => $search));
?>
