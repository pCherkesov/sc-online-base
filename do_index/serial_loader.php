<?php
$index .= "<div class=\"frame_caption\">Серийные номера:</div>";

$index .= "<select name='all_serial' id=\"all_serial\" size='20' class=\"devicelist\"
onChange=\"document.getElementById('serial').value = document.getElementById('all_serial')[selectedIndex].value;  \">";

$query_client = "SELECT `serial` FROM `".$S_CONFIG['prefix']."remont` 
WHERE `id_client` = '".$_REQUEST['sec']."' and `id_model` = '".$_REQUEST['four']."' GROUP BY `serial` ASC";
$result_client = mysqli_query($S_CONFIG['link'], $query_client) or exit(mysqli_error($S_CONFIG['link']));
while($line_client = mysqli_fetch_assoc($result_client)){
	$index .= "<option value='".$line_client['serial']."'>".$line_client['serial']."</option>";
}
$index .= "</select>";
?>
