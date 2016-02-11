<?php

switch (@$_REQUEST['dir']) {
	case 'add': add($_REQUEST['sec'], $_REQUEST['four']); break;
	case 'type':  add_save($_REQUEST['dir'], $_REQUEST['sec'], $_REQUEST['four'], $_REQUEST['six']); break;
	case 'brand': add_save($_REQUEST['dir'], $_REQUEST['sec'], $_REQUEST['four'], $_REQUEST['six']); break;
	case 'model': add_save($_REQUEST['dir'], $_REQUEST['sec'], $_REQUEST['four'], $_REQUEST['six']); break;
	//----------
	case 'add_client': add_client($_REQUEST['sec']); break;
	case 'add_client_save':  add_client_save($_REQUEST['sec'], $_REQUEST['four'], $_REQUEST['six']); break;
	case 'edit_client_save': edit_client_save($_REQUEST['sec'], $_REQUEST['four'], $_REQUEST['six'], $_REQUEST['eight']); break;
	default: $index .= ""; break;
}

function add ($sec, $four) {
	global $index;
	$index .= "<b>";
	if($sec != '0' && $four != '0'){
		$index .= "Добавление модели";
		$dir = 'model';
	}
	if($sec != '0' && $four == '0'){
		$index .= "Добавление бренда";
		$dir = 'brand';
	}
	if($sec == '0' && $four == '0'){
		$index .= "Добавление типа";
		$dir = 'type';
	}
	$index .= "</b><br />";
	$index .= "<input type='text' name='name' id='name' size='25' maxlen='25' value='' style=\"text-align: center;\" />";
	$index .= "<br /><img src='Images/Icon/nav-stop.png' alt='Отмена' 
	onClick=\"doSecond('device', ".$sec.", ".$four.");\"/>";
	$index .= "&nbsp;<img src='Images/Icon/nav-prefs.png' alt='Добавить' 
	onClick=\"doEdit('".$dir."', '".$sec."', '".$four."', document.getElementById('name').value, 0);\" />";
}

function add_client ($sec) {
	global $index;
	global $S_CONFIG;
	if($sec == '0' || $sec == "") {
		$index .= "<div class=\"frame_caption\">Добавление нового клиента</div>";
		$index .= "<input type='text' class=\"text\" name='name_client' id='name_client' size='30' maxlen='250' value='Название организации' style=\"text-align: center;\" /><br />";
		$index .= "<input type='text' class=\"text\" name='face_client' id='face_client' size='30' maxlen='250' value='Контактное лицо' style=\"text-align: center;\" /><br />";
		$index .= "<input type='text' class=\"text\" name='tel_client' id='tel_client' size='30' maxlen='250' value='Контактный телефон' style=\"text-align: center;\" /><br />";
		$index .= "<input type='hidden' name='id_client' id='id_client' value='' />";
		$dir = "add_client_save";
	}
	else {
		$result = mysql_query("SELECT * FROM `".$S_CONFIG['prefix']."client` WHERE `id_client`='".$sec."'") 
		or exit(mysql_error());
		while($line = mysql_fetch_assoc($result)){
			$index .= "<div class=\"frame_caption\">Редактирование данных клиента</div>";
			$index .= "<input type='text' class=\"text\" name='name_client' id='name_client' size='30' maxlen='250' value='".$line['client']."' style=\"text-align: center;\" /><br />";
			$index .= "<input type='text' class=\"text\" name='face_client' id='face_client'  size='30' maxlen='250' value='".$line['client_face']."' style=\"text-align: center;\" /><br />";
			$index .= "<input type='text' class=\"text\" name='tel_client' id='tel_client' size='30' maxlen='250' value='".$line['client_tel_0']."' style=\"text-align: center;\" /><br />";
			$index .= "<input type='hidden' name='id_client' id='id_client' value='".$sec."' />";
		}
		$dir = "edit_client_save";
	}
	$index .= "<img src='Images/Icon/nav-stop.png' border='0' alt='Отмена' 
	onClick=\"doSecond('name', '', 0);\"/>";
	$index .= "&nbsp;<img src='Images/Icon/nav-prefs.png' border='0' alt='Добавить' 
	onClick=\"doEdit('".$dir."', document.getElementById('name_client').value, document.getElementById('face_client').value, document.getElementById('tel_client').value , document.getElementById('id_client').value);\"/>";
}

function add_save ($dir, $sec, $four, $six) {
	global $index;
	global $S_CONFIG;

	switch ($dir){
		case "type":  $query = "INSERT INTO `".$S_CONFIG['prefix']."type` VALUE (0, 
									'".$six."')"; 
						$index_tmp = "Добавлен новый тип"; 
						$index_tmp_1 = $six; break;
		case "brand": $query = "INSERT INTO `".$S_CONFIG['prefix']."brand` VALUE (0, 
									'".$sec."',
                  '".$six."')"; 
						$index_tmp = "Добавлен новый бренд"; 
						$index_tmp_1 = $six; break;
		case "model": $query = "INSERT INTO `".$S_CONFIG['prefix']."model` VALUE (0, 
									'".$sec."',
									'".$four."',
									'".$six."')";
						$index_tmp = "Добавлена новая модель"; 
						$index_tmp_1 = $six; break;
		default: $index .= "<b>Неправильно переданный параметр</b>";
	}
	record($query);
	$index .= "<h3 onclick=\"doSecond('device', ".$sec.", ".$four.");\">";
	$index .= $index_tmp."<br /><br />";
	$index .= "<img src='Images/Icon/add-printer.png' border='0' /><br />"; 
	$index .= $index_tmp_1."</h3>";
}

function add_client_save ($sec, $four, $six) {
	global $index;
	global $S_CONFIG;
	if ($sec != "") {
  	$query = "INSERT INTO `".$S_CONFIG['prefix']."client` VALUE (0, 
  									'".$sec."', 
									'".$four."',
  									'".$six."'); ";
  	record ($query);
  	$insert_id = mysql_insert_id();
  	$index .= "<h3 onclick=\"doSecond('name', '', 0);\" >Добавлен новый клиент<br /><br />";
  	$index .= "<img src='Images/Icon/user_1.png' border='0' /><br />"; 
  	$index .= $sec."<br />контакт. лицо ".$four."<br />тел. ".$six."</h3>";
	} 
  else {
  	$index .= "<h3 onclick=\"doSecond('name', '', 0);\">ОШИБКА<br /><br />";
  	$index .= "<img src='Images/Icon/user_1.png' border='0' /><br />";
  	$index .= $sec."<br />Не внесено наименование</h3>";
  }
}

function edit_client_save ($sec, $four, $six, $eight) {
	global $index;
	global $S_CONFIG;
 	if ($sec != "") {	
  	$query = "UPDATE `".$S_CONFIG['prefix']."client` 
  		SET `client`='".$sec."', `client_face`='".$four."', `client_tel_0`='".$six."' 
  		WHERE `id_client`='".$eight."' LIMIT 1";
  	record ($query);
  	$index .= "<h3 onclick=\"doSecond('name', '', 0); \">Данные клиента изменены</b><br /><br />";
  	$index .= "<img src='Images/Icon/user_1.png' border='0' /><br />"; 
  	$index .= $sec."<br />конт. лицо: ".$four."<br />тел. ".$six."</h3>";
 	}
 	else {
  	$index .= "<h3 onclick=\"doSecond('name', 0, 0);\">ОШИБКА<br /><br />";
  	$index .= "<img src='Images/Icon/user_1.png' border='0' /><br />";
  	$index .= $sec."<br />Не внесено наименование</h3>";
 	}
}

function record ($query) {
	$query = str_replace("\r\n","",$query);	
	$query = str_replace("	","",$query);	
	$file = fopen("Logs/hardware.txt", "a") or die("Ошибка чтения файла лога");
	flock($file, 2);
	fputs($file, $query."\n");
	flock($file, 3);
	fclose($file);
	$rec = mysql_query($query) or exit(mysql_error());
}

?>
