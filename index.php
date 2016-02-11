<?php
$page_header = "-= New work =-";
require("header.php");
require("init.php");
//require("scripts.js");

//=================================================================================================
    echo "<table class=\"priem_main\"><tr><td width='50%'>";
    echo "<form action='request.php' method='post' id='form_remont'>";
    echo "<table class=\"priem_frame\">";

//====== выбор даты ======
    echo "<tr onClick=\"doSecond('calendar', 0, 0);\"><td align='left'>";
	echo "<b>Дата:</b> ";
	echo "<input type='text' class=\"text\" name='data' id='data' size='10' maxlen='10' value='".date("d.m.Y")."' />";
	echo " <img src='Images/Icon/b_calendar.png' title='Календарь' /></td></tr>";
	
//====== выбор клиента ======
	echo "<tr><td align='left'>";
	echo "<fieldset onclick=\"doSecond('name', 'Поиск', 0);\" >";
	echo "<legend><img src='Images/Icon/user_group.png' /> Клиент:</legend>";	
	echo "<table class=\"clientinfo\">";
	echo "<tr><td><b><div id='name_div' name='name_div'>* * *</div></b></td></tr>";
	echo "<tr><td><input type='text' class=\"text\" name='firma_face' id='firma_face' size=\"30\" maxlen='250' disabled=\"disabled\"
			value='Контактное лицо' onfocus=\"if(this.value=='Фамилия клиента'){this.value='';}\" 
			onblur=\"if(this.value==''){this.value='Фамилия клиента';}\" /></td></tr>";
	echo "<tr><td><input type='text' class=\"text\" name='firma_tel' id='firma_tel' size=\"30\" maxlen='250' disabled=\"disabled\"
			value='Контактный телефон' onfocus=\"if(this.value=='Телефон клиента'){this.value='';}\" 
			onblur=\"if(this.value==''){this.value='Телефон клиента';}\" /></td></tr>";
	echo "</table>";
	echo "</td></tr>";
	echo "</fieldset>";		
	echo "</td></tr>";
	
//====== выбор техники ======
	echo "<tr><td>";
	echo "<fieldset>";
	echo "<legend><img src='Images/Icon/printer.png' /> Техника:</legend>";
	echo "<b onClick=\"doSecond('device', 0, 0);\"><div id='d_device' name='d_device'>";
	echo "Укажите принимаемую технику</div></b>";
	echo "</fieldset>";
	echo "</td></tr>";

//====== ввод серийника ======
    echo "<tr><td align='right' 
	onClick=\"doSecond('serial', document.getElementById('client').value, document.getElementById('selected_model').value);\">
	<b>Серийный №:&nbsp;</b><input type='text' class=\"text\" name='serial' id=\"serial\" size='30' maxlen='35' value='' /></td></tr>";
	
//====== ввод дефектов ======
echo "<tr><td onClick=\"doSecond('void', 0, 0);\"><textarea rows='3' name='defect' 
onfocus=\"if(this.value=='Дефекты (со слов клиента)'){this.value='';}\" 
onblur=\"if(this.value==''){this.value='Дефекты (со слов клиента)';}\">Дефекты (со слов клиента)</textarea></td></tr>";

//====== ввод комплекта ======
echo "<tr><td onClick=\"doSecond('void', 0, 0);\"><textarea rows='3' name='complect' 
onfocus=\"if(this.value=='Комплектация'){this.value='';}\" 
onblur=\"if(this.value==''){this.value='Комплектация';}\">Комплектация</textarea></td></tr>";

//====== кто принял ======
    echo "<tr><td align='right' onClick=\"doSecond('void', 0, 0);\"><b>Принял:</b>
	<select name=prin>";
    $result_prin = mysqli_query($S_CONFIG['link'], "SELECT * FROM `".$S_CONFIG['prefix']."prin` WHERE `hidden`='N' GROUP BY `prin` ASC") or exit(mysqli_error());
    while($line_prin = mysqli_fetch_assoc($result_prin)){
        echo "<option value=".$line_prin['id_prin'].">".$line_prin['prin']."</option>";
    }
	echo "</select></td></tr>";
	echo "<tr><td align='right' onClick=\"doSecond('void', 0, 0);\">";
	echo "<b>Распечатать бланк</b> <input type='checkbox' value='1' checked='checked' name='print' title='Печать' class='checkbox' />";
	echo "</td></tr>";
//======= скрытые поля =========
	echo "<input type='hidden' name='client' id='client' value=''>";
	//  echo "<input type='hidden' name='text_client' value=''>";
	//  echo "<input type='hidden' name='client_index' value='firma'>";
	//
	echo "<input type='hidden' name='selected_model' id='selected_model' value=''>";
	echo "<input type='hidden' name='sel_type' id='sel_type' value=''>";
	echo "<input type='hidden' name='sel_brand' id='sel_brand' value=''>";
	echo "<input type='hidden' name='sel_model' id='sel_model' value=''>";
	//
	echo "<input type='hidden' name='action' value='insert_record'>";
//====== кнопка сохранить ======
	echo "<tr><td align='right'><input type='submit' name='saved' value=' Сохранить '></td></tr></table></form>";
//==============================================================
echo "</td><td width='50%' align='center' valign='top'>";
	echo "<table class=\"priem_frame\">";
	echo "<tr><td>";
	echo "<fieldset class=\"priem\">";
	echo "<legend>Фигня :)</legend>";
	echo "<div id='second' class=\"second_frame\"><img src='Images/1174437760018.gif' alt='Прогресс-бар' /></div>";
	echo "</fieldset>";
	echo "</td></tr>";
	echo "</table>";
echo "</td></tr></table>";


require("footer.php");
?>
<script type="text/javascript" language="JavaScript">

function doSecond(direct, sec_param, fo_param) {
    // Create new JsHttpRequest object.
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
    	if (req.readyState == 4) {
			if(req.responseJS.index == '') {
			document.getElementById('second').innerHTML = "<img src='Images/1174437760018.gif' border='0' alt='Прогресс-бар' />";}
			else {document.getElementById('second').innerHTML = req.responseJS.index;}
			// Write result to page element ($_RESULT become responseJS). 
			document.getElementById('result').innerHTML = 'AJAX transmitted complete';
			// Write debug information too (output become responseText).
			document.getElementById('debug').innerHTML = req.responseText;
        }
    }
    // Prepare request object (automatically choose GET or POST).
    req.open(null, 'do_index_loader.php', true);
    // Send data to backend.
    req.send( { dir: direct, sec: sec_param, four: fo_param} );
}

function doEdit(direct, sec_param, fo_param, six_param, eight_param) {
    // Create new JsHttpRequest object.
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
    	if (req.readyState == 4) {
			document.getElementById('second').innerHTML = req.responseJS.index;
			// Write result to page element ($_RESULT become responseJS). 
			document.getElementById('result').innerHTML = 'Edition form transmitted complete';
			// Write debug information too (output become responseText).
			document.getElementById('debug').innerHTML = req.responseText;
        }
    }
    // Prepare request object (automatically choose GET or POST).
    req.open(null, 'do_index_loader.php', true);
    // Send data to backend.
    req.send( { dir: direct, sec: sec_param, four: fo_param, six: six_param, eight: eight_param} );
}

</script>
