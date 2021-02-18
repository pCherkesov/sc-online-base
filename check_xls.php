<?php
require_once("init.php");
$_HOME = False;
if (!isset($inputs['edit_id'])) {
	$args = array(
		'edit_id'		    => FILTER_VALIDATE_INT,
	);

	$inputs = filter_input_array(INPUT_POST, $args);
}

if (!isset($inputs['edit_id'])) $redirect['error_text'] = "Unknown work ID";

$xls = new COM('Excel.Application');

if($_HOME == TRUE) {
	$xls->Application->Visible = 1;
	$xls->Workbooks->Open('c:\servers\OpenServer\domains\online-service.local\check_xls.xls');
}
else {
	$xls->Application->Visible = 0;
	$xls->Workbooks->Open('c:\OpenServer\domains\online-service.local\check_xls.xls');
}

$sheet = $xls->Worksheets('check');
$sheet->activate;
//$xls->Workbooks->Add();
		
$query = "SELECT t.type, b.brand, m.model, r.id_client, c.client, c.client_tel_0, r.client_fio, r.client_tel, r.serial 
	FROM `remont` AS r 
	LEFT JOIN `model` AS m ON r.id_model = m.id_model 
	LEFT JOIN `type` AS t ON m.id_type = t.id_type 
	LEFT JOIN `brand` AS b ON m.id_brand = b.id_brand 
	LEFT JOIN `client` AS c ON r.id_client = c.id_client 
	WHERE r.id_r = ".$inputs['edit_id']." 
	LIMIT 1";
$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));

while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

		$rangeValue = $xls->Range("A11");
		$rangeValue->Value = iconv("UTF-8", "cp1251", "Товарный чек №". $inputs['edit_id']);
	//----------------------
		if ($line['client'] == "ч/л"){
			$client_fio = $line['client_fio'];
		}
		else {
			$client_fio = $line['client'];
		}
		$rangeValue = $xls->Range("A12");
		$rangeValue->Value = iconv("UTF-8", "cp1251", "Заказчик: ". $client_fio);
	//-----------------------
		$rangeValue = $xls->Range("A13");
		$rangeValue->Value = iconv("UTF-8", "cp1251", "Ремонт: ".$line['type']." ".$line['brand']." ".$line['model']);
	//-----------------------
		$rangeValue = $xls->Range("A14");
		$rangeValue->Value = iconv("UTF-8", "cp1251", "s/n: ".$line['serial']);
}

$result_query="SELECT text, price, hard, hard_price
                FROM `".$S_CONFIG['prefix']."work`
                WHERE id_r=".$inputs['edit_id']." and hidden='N'";
                
$results = mysqli_query($S_CONFIG['link'], $result_query) or exit(mysqli_error($S_CONFIG['link']));

$num = 1;
$line = 17;
$total_price = 0;
while ($lines = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    if($lines['price'] > 0) {
    	$rangeValue = $xls->Range("A". $line);
		$rangeValue->Value = $num .". ". iconv("UTF-8", "cp1251", $lines['text']);
    	$rangeValue = $xls->Range("A". ($line+1));
    	$rangeValue->Value = iconv("UTF-8", "cp1251", "Стоимость . . . . . . . . . . . . . . . . . . . . .");
    	$rangeValue = $xls->Range("D". ($line+1));
		$rangeValue->Value = iconv("UTF-8", "cp1251", $lines['price'] ." руб.");
        $total_price += $lines['price'];
        $line = $line + 2;
        $num++;
    }
    if($lines['hard_price'] > 0) {
		if(isJson($lines['hard'])) {
			$hard = json_decode($lines['hard'], true);
			foreach ($hard as $count => $h) {
				$rangeValue = $xls->Range("A". $line);
				$rangeValue->Value = iconv("UTF-8", "cp1251", $num. ". ". $h['edit_hard']);
				$rangeValue = $xls->Range("A". ($line+1));
				$rangeValue->Value = iconv("UTF-8", "cp1251", "Стоимость . . . . . . . . . . . . . . . . . . . . .");
				$rangeValue = $xls->Range("D". ($line+1));
				$rangeValue->Value = iconv("UTF-8", "cp1251", $h['edit_hardprice'] ." руб.");
				$line = $line + 2;
				$num++;
			}
			$total_price += $lines['hard_price'];
		}
		else {
			$rangeValue = $xls->Range("A". $line);
			$rangeValue->Value = iconv("UTF-8", "cp1251", $num .". ".  str_replace("<br />", ", ", $lines['hard']));
			$rangeValue = $xls->Range("A". ($line+1));
			$rangeValue->Value = iconv("UTF-8", "cp1251", "Стоимость . . . . . . . . . . . . . . . . . . . . .");
			$rangeValue = $xls->Range("D". ($line+1));
			$rangeValue->Value = iconv("UTF-8", "cp1251", $lines['hard_price']." руб.");
			$total_price += $lines['hard_price'];
			$line = $line + 2;
			$num++;
		}
    }
}
$rangeValue = $xls->Range("D24");
$rangeValue->Value = iconv("UTF-8", "cp1251", $total_price." руб.");

$rangeValue = $xls->Range("A25");
$rangeValue->Value = iconv("UTF-8", "cp1251", date("H:i"));
$rangeValue = $xls->Range("B25");
$rangeValue->Value = iconv("UTF-8", "cp1251", date("d.m.Y"));

if($_HOME == TRUE) {
}
else {
	
	$sheet->PrintOut(); //$ActivePrinter='\\\\MIROTWOREZ-PC\\Zebra LP2742');
	$xls->Workbooks[1]->SaveAs(recursiveRename('c:\temp\print\\'. $inputs['edit_id']));
	$xls->Quit();
}

//$xls->Release();
$xls = Null; 
$range = Null;


function recursiveRename ($name, $num=0) {
	clearstatcache();
	if (file_exists($name ."-". $num .".xls")){
		// echo "<h3>Такой чек уже был распечатан</h3>";		
		$num++;
		return recursiveRename($name, $num);
	}
	return $name."-".$num.".xls";
}

?>
