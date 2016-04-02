<?php

if($_REQUEST['sec'] != '0' && $_REQUEST['four'] != '0'){
	$index .= "<div class=\"frame_caption\">Выберите модель:</div>";
	$index .= "<select name='model' id=\"model\" size='20' class=\"devicelist\" 
		  onchange=\"document.getElementById('d_device').innerHTML=document.getElementById('sel_type').value + ' ' +  document.getElementById('sel_brand').value + ' ' + document.getElementById('model')[selectedIndex].text; 
		  document.getElementById('sel_model').value=document.getElementById('model')[selectedIndex].text; 
		  document.getElementById('selected_model').value=value; \">";
	
	$query = "SELECT `id_model`, `model` FROM `".$S_CONFIG['prefix']."model` 
			WHERE `id_type`= '".$_REQUEST['sec']."' and `id_brand`='".$_REQUEST['four']."' 
			ORDER BY `model` ASC";
	$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
	while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$index .= "<option value='".$line['id_model']."'>".$line['model']."</option>";
		//$index .= "<option value='".$line['id_model']."'>".$line['model']." (".$line['id_model'].")</option>";
		$id_model[] = $line['id_model'];
		$model[] = $line['model'];
	}
	$index .= "</select>";

	$index .= "<br />";
	$index .= "<table class=\"buttonbar\"><tr><td width='50%'>";
	$index .= "<img src='Images/Icon/nav-left.png' alt='Назад' 
		  onClick=\"doSecond('device', ".$_REQUEST['sec'].", 0);\"/></td><td>";
}
if($_REQUEST['sec'] != '0' && $_REQUEST['four'] == '0'){
	//=== выбор брэнда ===
	$index .= "<div class=\"frame_caption\">Выберите брэнд:</div>";
	$index .= "<select name='brand' id=\"brand\" size='20' class=\"devicelist\" 
		  onchange=\"doSecond('device', ".$_REQUEST['sec'].", value); 
		  document.getElementById('sel_brand').value=document.getElementById('brand')[selectedIndex].text; 
		  document.getElementById('d_device').innerHTML=document.getElementById('sel_type').value + ' ' + document.getElementById('brand')[selectedIndex].text;\">";
	
	$query_brand = "SELECT * FROM `".$S_CONFIG['prefix']."brand` WHERE `id_type`= ".$_REQUEST['sec']." ORDER BY `brand` ASC";
	$result_brand = mysqli_query($S_CONFIG['link'], $query_brand) or exit(mysqli_error($S_CONFIG['link']));
	while($option_brand=mysqli_fetch_assoc($result_brand)){
		$index .= "<option value='".$option_brand['id_brand']."'>".$option_brand['brand']."</option>";
		//$index .= "<option value='".$option_brand['id_brand']."'>".$option_brand['brand']." (".$option_brand['id_brand'].")</option>";
	}
	$index .= "</select>";

	$index .= "<br />";
	$index .= "<table class=\"buttonbar\"><tr><td width='50%'>";
	$index .= "<img src='Images/Icon/nav-left.png' alt='Назад'
		  onClick=\"doSecond('device', 0, 0);\" /></td><td>";
}
if($_REQUEST['sec'] == '0' && $_REQUEST['four'] == '0'){
//=== выбор типа ===
	$index .= "<div class=\"frame_caption\">Выберите тип:</div>";
	$index .= "<select name='type' id=\"type\" size='20' class=\"devicelist\" 
	onchange=\"doSecond('device', document.getElementById('type').value, 0); 
	document.getElementById('sel_type').value=document.getElementById('type')[selectedIndex].text; 
	document.getElementById('d_device').innerHTML=document.getElementById('type')[selectedIndex].text;\">";

	$query_type = "SELECT * FROM `".$S_CONFIG['prefix']."type` ORDER BY `type` ASC";
	$result_type = mysqli_query($S_CONFIG['link'], $query_type) or exit(mysqli_error($S_CONFIG['link']));
	while($option_type=mysqli_fetch_assoc($result_type)){
		$index .= "<option value='".$option_type['id_type']."'>".$option_type['type']."</option>";
		//$index .= "<option value='".$option_type['id_type']."'>".$option_type['type']." (".$option_type['id_type'].")</option>";
	}
	$index .= "</select>";

	$index .= "<br />";
	$index .= "<table class=\"buttonbar\"><tr><td width='50%'>";
	$index .= "&nbsp;</td><td>";
}
	$index .= "&nbsp;<img src='Images/Icon/nav-add.png' alt='Добавить' 
		  onClick=\"doEdit('add', ".$_REQUEST['sec'].", ".$_REQUEST['four'].", 0);\"/></td>";
	$index .= "</tr></table>";
?>
