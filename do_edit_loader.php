<?php
// Load JsHttpRequest backend.
require_once "JsHttpRequest/JsHttpRequest.php";
// Create main library object. You MUST specify page encoding!
$JsHttpRequest = new JsHttpRequest("windows-1251");
//
require("init.php");

if($_REQUEST['value']=="0"){
		$action = "insert_work";
		$date = date("d.m.Y");
		$text = $price = $hard = $hard_price = "";	
}
else{
		$action = "update_work";
		$result_query="SELECT date, text, price, hard, hard_price, id_worker
				FROM `".$S_CONFIG['prefix']."work`
				WHERE id=".$_REQUEST['value'];
		$results = mysql_query($result_query) or exit(mysql_error());
		while ($lines = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$date = $lines['date'];							
			$text = $lines['text'];
			$price = $lines['price'];
			$hard = $lines['hard'];
			$hard_price = $lines['hard_price'];
			$id_worker = $lines['id_worker'];
		}
		$date = repair_time($date, ".");
	    $text = str_replace("<br />", "\r\n",$text);
	    $hard = str_replace("<br />", "\r\n",$hard);

} 
$editmenu = "";

$editmenu .= "<form action='request.php' method='post' id='edit_menu'>";
//<style='visibility: hidden;'>";
$editmenu .= "<input type='hidden' name='action' value='".$action."'>";
$editmenu .= "<input type='hidden' name='id_r' value='".@$_REQUEST['id_r']."'>";
$editmenu .= "<input type='hidden' name='id' value='".$_REQUEST['value']."'>";
$editmenu .= "<table width='98%' border='0' cellSpacing='0' cellPadding='0' align='center' valign='top'>";
$editmenu .= "<tr><td align='center'>";
$editmenu .= "<b>Дата: </b><input type='text' name='data' size='10' maxlen='10' value='".$date."'>";
$editmenu .= "<img src='Images/Icon/b_calendar.png' border='0' 
onclick=\'document.getElementById('data').value=\"".date("d")."\"+\".\"+\"".date("m")."\"+\".\"+\"".date("Y")."\" \' />";
$editmenu .= "</td></tr>";
$editmenu .= "<tr><td align='center'>";
$editmenu .= "<textarea name='work' cols='60' rows='4' class='rteiframe'>".$text."</textarea>";
$editmenu .= "<textarea name='hard' cols='60' rows='4' class='rteiframe'>".$hard."</textarea>";
$editmenu .= "</td></tr>";
$editmenu .= "<tr><td align='center'>";
$editmenu .= "<b>Price: </b><input type='text' name='price' size='10' maxlen='10' value='".$price."'>";
$editmenu .= "<b>Hardware price: </b><input type='text' name='hard_price' size='10' maxlen='10' value='".$hard_price."'><br />";
$editmenu .= "</td></tr>";
$editmenu .= "<tr><td align='center'>";
$editmenu .= "<b>Worker: </b>";
$editmenu .= "<select name='worker'>";
	$query_worker="SELECT * FROM `".$S_CONFIG['prefix']."worker` WHERE `hidden`='N' ORDER BY `worker` ASC";
	$result_worker=mysql_query($query_worker) or exit(mysql_error());
	while($line_worker=mysql_fetch_assoc($result_worker)){
		 $editmenu .= "<option value=".$line_worker['id_worker'];
		 if(isset($id_worker) && $line_worker['id_worker'] == $id_worker) $editmenu .= " selected "; 
     $editmenu .= ">".$line_worker['worker']."</option>";
	}
$editmenu .= "</select>";
$editmenu .= "<img name='add' src='Images/Icon/checkbox.png' border='0' onClick=\"edit_menu.submit();\">";
$editmenu .= "</td></tr>";
$editmenu .= "</table>";
//===Переписать в Аякс===
//if($this->complete=="N")
//$editmenu .= "<a href='".$_SERVER['PHP_SELF']."?act=$_REQUEST[act]&id=$_REQUEST[id_r]&completed=1'>Complete</a><br />";
//else $editmenu .= "<a href='".$_SERVER['PHP_SELF']."?act=$_REQUEST[act]&id=$_REQUEST[id_r]&uncompleted=1'>Uncomplete</a><br />";
//=======================
$editmenu .= "</form>";

// Store resulting data in $_RESULT array (will appear in req.responseJs).
$_RESULT = array(
	"editmenu" => @$editmenu,
	"id"	   => @$_REQUEST['value'],
	"idr"      => @$_REQUEST['id_r']
); 

function repair_time($rep_time, $parse) {
	if (preg_match("|([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})|i", $rep_time, $date))
			return $date['3'].$parse.$date['2'].$parse.$date['1'];
		else return "--".$parse."--".$parse."----";
}
// Below is unparsed stream data (will appear in req.responseText).
?>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>
