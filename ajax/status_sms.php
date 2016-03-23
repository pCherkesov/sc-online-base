<?php
require("init_j.php");
include "../lib/smsc_api.php";

$smsc_statuses = array(
	-3	=>	"Сообщение не найдено",
	-1	=>	"Ожидает отправки",
	0	=>	"Передано оператору",
	1	=>	"Доставлено",
	3	=>	"Просрочено",
	20	=>	"Невозможно доставить",
	22	=>	"Неверный номер",
	23	=>	"Запрещено",
	24	=>	"Недостаточно средств",
	25	=>	"Недоступный номер",
);

$smsc_errors = array(
	1	=>	"Ошибка в параметрах.",
	2	=>	"Неверный логин или пароль.",
	4	=>	"IP-адрес временно заблокирован.",
	5	=>	"Ошибка удаления сообщения.",
	9	=>	"Попытка отправки более пяти запросов на получение статуса одного и того же сообщения в течение минуты.	",
);

$args = array(
	'id'	=> FILTER_VALIDATE_INT,
	'tel'	=> FILTER_SANITIZE_STRING,	
);

$inputs = filter_input_array(INPUT_POST, $args);

$id = $inputs['id'];
$tel = "7".str_replace(array(" ", "-", "(", ")"), "", $inputs['tel']);

//====== Отправка СМС сообщения ======
try {
    list($status, $time, $err) = get_status($id, $tel);
}
catch (Exception $e) {
    echo $e->getMessage();
}

echo $smsc_statuses[$status].":".$time.":".$smsc_errors[$err];
// $result_query = "INSERT INTO `".$S_CONFIG['prefix']."sms` VALUE(0, '". $idr ."', '". $sms_id ."', CURRENT_TIMESTAMP, '". $msg ."', 1, 0);";

// mysqli_query($S_CONFIG['link'], $result_query) or exit(mysql_error($S_CONFIG['link']));


// //====== Запись статуса в работу ======
// $query = "SELECT `string` FROM `".$S_CONFIG['prefix']."remont` WHERE `id_r` = ".$idr;

// $result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
// while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
// 	$status = $line['string'];
// }

// $status[9] = "Y";

// $query = "UPDATE `".$S_CONFIG['prefix']."remont` SET `string` = '".$status."' WHERE `id_r` = ".$idr." LIMIT 1";

// mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
?>
