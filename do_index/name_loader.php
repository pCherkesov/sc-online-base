<?php
$do_index_tel = '';
$do_index_face = '';
$index .= "<div class=\"frame_caption\">Выберите клиента:</div>";
$index .= <<<HTML
<input type='text' class="names_search" name='names_search' id='names_search'
onfocus="if(this.value=='Поиск'){this.value='';}"
onblur="if(this.value==''){this.value='Поиск';}"
HTML;
$index .= "value='".$_REQUEST['sec']."' />";
$index .= " <img src=\"Images/Icon/search.png\" title=\"Поиск по имени\"
	    onclick=\"doSecond('name', document.getElementById('names_search').value, 0);\"><br/>";

$index .= "<select name='names' id=\"names\" class=\"devicelist\" size=\"20\"
onChange=\"document.getElementById('client').value = document.getElementById('names')[selectedIndex].value; 
document.getElementById('name_div').innerHTML=document.getElementById('names')[selectedIndex].text; 
document.getElementById('firma_face').value=document.getElementById('id_faces')[selectedIndex].text; 
document.getElementById('firma_tel').value=document.getElementById('id_names')[selectedIndex].text; 
document.getElementById('firma_face').disabled = false; document.getElementById('firma_tel').disabled = false;\" >";

$index .= "<option value='1'>* частное лицо *</option>";
$do_index_face .= "<option value='1'>Фамилия клиента</option>";
$do_index_tel .= "<option value='1'>Телефон клиента</option>";

if($_REQUEST['sec'] == 'Поиск' or $_REQUEST['sec'] == '') $search_name = "";
else $search_name = "WHERE `client` LIKE '%".$_REQUEST['sec']."%'";

$query_client = "SELECT * FROM `".$S_CONFIG['prefix']."client` ".$search_name." ORDER BY client ASC";
$result_client = mysqli_query($S_CONFIG['link'], $query_client) or exit(mysql_error());
while($line_client = mysqli_fetch_assoc($result_client)){
	if($line_client['id_client'] != '1') {
		$index .= "<option value='".$line_client['id_client']."'>".$line_client['client']."</option>";
		//$index .= "<option value='".$line_client['id_client']."'>".$line_client['client']." (".$line_client['id_client'].")</option>";
		$do_index_face .= "<option value='".$line_client['id_client']."'>".$line_client['client_face']."</option>";
		$do_index_tel .= "<option value='".$line_client['id_client']."'>".$line_client['client_tel_0']."</option>";
	}
}
$index .= "</select>";
$index .= "<br />";
$index .= "<table class=\"buttonbar\"><tr><td width='50%'>";
$index .= "<img src='Images/Icon/nav-add.png' border='0' alt='Добавить' 
onClick=\"doEdit('add_client', 0, 0, 0);\"/><td>";
$index .= "<img src='Images/Icon/edit1.png' border='0' alt='Редактировать' 
onClick=\"doEdit('add_client', document.getElementById('names').value, 0, 0); \"/>";
$index .= "</td></tr></table>";

$index .= "<select name='id_names' id='id_names' style='display: none;'>";
$index .= $do_index_tel;
$index .= "</select>";
$index .= "<select name='id_faces' id='id_faces' style='display: none;'>";
$index .= $do_index_face;
$index .= "</select>";
?>