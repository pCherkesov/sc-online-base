<?php
// Load JsHttpRequest backend.
require_once "JsHttpRequest/JsHttpRequest.php";
// Create main library object. You MUST specify page encoding!
$JsHttpRequest = new JsHttpRequest("windows-1251");
//
require("init.php");

if(!isset($_REQUEST['reset'])){
	$query="SELECT `id_model`, `model` FROM `".$S_CONFIG['prefix']."model` 
			WHERE `id_type`= '".$_REQUEST['type']."' and `id_brand`='".$_REQUEST['brand']."' 
			GROUP BY `model` ASC";
	$result = mysql_query($query) or exit(mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$id_model[] = $line['id_model'];
		$model[] = $line['model'];
	}
if(!isset($model)){
		$id_model = '1';
		$model = "";
}

	// Store resulting data in $_RESULT array (will appear in req.responseJs).
	$_RESULT = array(
	  "id_model" => @$id_model,
	  "model"    => @$model,
	  "type"     => @$_REQUEST['type'],
	  "brand"    => @$_REQUEST['brand']
	); 
}
else {
	$hardsel='';
//=== ����� ���� ===
	$hardsel .= "<select name='type' onchange=\"document.getElementById('brand').disabled = false; document.getElementById('type').disabled = true;\">";
	$hardsel .= "<option value='0'>�������� ���</option>";
    $query_type = "SELECT * FROM `".$S_CONFIG['prefix']."type` GROUP BY `type` ASC";
    $result_type = mysql_query($query_type) or exit(mysql_error());
     while($option_type=mysql_fetch_assoc($result_type)){
		$hardsel .= "<option value='".$option_type['id_type']."'>".$option_type['type']."</option>";
	}
	$hardsel .= "</select>";
	//=== ����� ������ ===
	$hardsel .= "<select name='brand' disabled onchange=\"document.getElementById('brand').disabled = true; doDevice(document.getElementById('type').value, value);\">";
	$hardsel .= "<option value='0'>�������� �����</option>";
    $query_brand = "SELECT * FROM `".$S_CONFIG['prefix']."brand` GROUP BY `brand` ASC";
    $result_brand = mysql_query($query_brand) or exit(mysql_error());
    while($option_brand=mysql_fetch_assoc($result_brand)){
		$hardsel .= "<option value='".$option_brand['id_brand']."'>".$option_brand['brand']."</option>";
		}
	$hardsel .= "</select>";
	//=== ����� ������ ===
	$hardsel .= "<div id='d_model'>";
	$hardsel .= "<select name='model' disabled >";
	$hardsel .= "<option value='0'>�������� ������</option></select>";
	$hardsel .= "</div>";

	// Store resulting data in $_RESULT array (will appear in req.responseJs).
	$_RESULT = array(
	  "hardsel" => @$hardsel
	); 

}
// Below is unparsed stream data (will appear in req.responseText).


?>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>
