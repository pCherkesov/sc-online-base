<?php

if($_REQUEST['sec'] != '0' && $_REQUEST['four'] != '0'){
	$index .= "<select name='model' size='20' style='width: 100%;'  onchange=\"document.getElementById('d_device').innerHTML=document.getElementById('sel_type').value + ' ' +  document.getElementById('sel_brand').value + ' ' + document.getElementById('model')[selectedIndex].text; document.getElementById('sel_model').value=document.getElementById('model')[selectedIndex].text; document.getElementById('selected_model').value=value; \">";
	
	$query = "SELECT `id_model`, `model` FROM `".$S_CONFIG['prefix']."model` 
			WHERE `id_type`= '".$_REQUEST['sec']."' and `id_brand`='".$_REQUEST['four']."' 
			ORDER BY `model` ASC";
	$result = mysql_query($query) or exit(mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$index .= "<option value='".$line['id_model']."'>".$line['id_model'].": ".$line['model']."</option>";
		$id_model[] = $line['id_model'];
		$model[] = $line['model'];
	}
	$index .= "</select>";

	$index .= "<br />";
	$index .= "<table border='0' width='80%'><tr><td width='50%'>";
	$index .= "<img src='Images/Icon/nav-left.png' border='0' alt='Назад' 
	onClick=\"doSecond('device', ".$_REQUEST['sec'].", 0);\"/></td><td>";
}
if($_REQUEST['sec'] != '0' && $_REQUEST['four'] == '0'){
	//=== выбор брэнда ===
	$index .= "<select name='brand' size='20' style='width: 100%;' onchange=\"doSecond('device', ".$_REQUEST['sec'].", value); document.getElementById('sel_brand').value=document.getElementById('brand')[selectedIndex].text; document.getElementById('d_device').innerHTML=document.getElementById('sel_type').value + ' ' + document.getElementById('brand')[selectedIndex].text;\">";
    $query_brand = "SELECT * FROM `".$S_CONFIG['prefix']."brand` WHERE `id_type`= ".$_REQUEST['sec']." ORDER BY `brand` ASC";
    $result_brand = mysql_query($query_brand) or exit(mysql_error());
    while($option_brand=mysql_fetch_assoc($result_brand)){
		$index .= "<option value='".$option_brand['id_brand']."'>".$option_brand['id_brand'].": ".$option_brand['brand']."</option>";
		}
	$index .= "</select>";

	$index .= "<br />";
	$index .= "<table border='0' width='80%'><tr><td width='50%'>";
	$index .= "<img src='Images/Icon/nav-left.png' border='0' alt='Назад'
	onClick=\"doSecond('device', 0, 0);\" /></td><td>";
}
if($_REQUEST['sec'] == '0' && $_REQUEST['four'] == '0'){
//=== выбор типа ===
	$index .= "<select name='type' size='20' style='width: 100%;' onchange=\"doSecond('device', document.getElementById('type').value, 0); document.getElementById('sel_type').value=document.getElementById('type')[selectedIndex].text; document.getElementById('d_device').innerHTML=document.getElementById('type')[selectedIndex].text;\">";
    $query_type = "SELECT * FROM `".$S_CONFIG['prefix']."type` ORDER BY `type` ASC";
    $result_type = mysql_query($query_type) or exit(mysql_error());
     while($option_type=mysql_fetch_assoc($result_type)){
		$index .= "<option value='".$option_type['id_type']."'>".$option_type['id_type'].": ".$option_type['type']."</option>";
	}
	$index .= "</select>";

	$index .= "<br />";
	$index .= "<table border='0' width='80%'><tr><td width='50%'>";
	$index .= "&nbsp;</td><td>";
}
	$index .= "&nbsp;<img src='Images/Icon/nav-add.png' border='0' alt='Добавить' 
	onClick=\"doEdit('add', ".$_REQUEST['sec'].", ".$_REQUEST['four'].", 0);\"/></td>";
	$index .= "</tr></table>";
?>
