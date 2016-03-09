<?php
require_once("init.php");

echo "<html><body onload='setTimeout(window.close, 30000)'>";

if (!isset($_GET['id_r'])) die("Unknown work ID"); 
else $print_id = $_GET['id_r'];

$xls = new COM('Excel.Application');
$xls->Application->Visible = 0;
$xls->Workbooks->Open('C:\xampp\htdocs\online-service.ru\Forms\check.xls');
$sheet = $xls->Worksheets('check');
$sheet->activate;
//$xls->Workbooks->Add();
		
$query="SELECT t.type, b.brand, m.model, c.client, c.client_tel_0, r.client_fio, r.client_tel, r.serial
FROM `".$S_CONFIG['prefix']."remont` AS r, `".$S_CONFIG['prefix']."type` AS t, `".$S_CONFIG['prefix']."model` AS m, `".$S_CONFIG['prefix']."client` AS c, `".$S_CONFIG['prefix']."brand` AS b, `".$S_CONFIG['prefix']."prin` AS p
WHERE t.id_type=m.id_type and m.id_brand=b.id_brand and r.id_client=c.id_client and r.id_model=m.id_model and r.id_r=".$print_id."
LIMIT 1";
$result = mysql_query($query) or exit(mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {

		$rangeValue = $xls->Range("A11");
		$rangeValue->Value = "Товарный чек №".$print_id;
	//----------------------
		if ($line['client'] == "ч/л"){
			$client_fio = $line['client_fio'];
		}
		else {
			$client_fio = $line['client'];
		}
		$rangeValue = $xls->Range("A12");
		$rangeValue->Value = "Заказчик: ".$client_fio;
	//-----------------------
		$rangeValue = $xls->Range("A13");
		$rangeValue->Value = "Ремонт: ".$line['type']." ".$line['brand']." ".$line['model'];
	//-----------------------
		$rangeValue = $xls->Range("A14");
		$rangeValue->Value = "s/n: ".$line['serial'];
}

$result_query="SELECT text, price, hard, hard_price
                FROM `".$S_CONFIG['prefix']."work`
                WHERE id_r=".$print_id." and hidden='N'";
                
$results = mysql_query($result_query) or exit(mysql_error());

$num = 1;
$line = 17;
$total_price = 0;
while ($lines = mysql_fetch_array($results, MYSQL_ASSOC)) {
    if($lines['price'] > 0) {
    	$rangeValue = $xls->Range("A".$line);
		$rangeValue->Value = $num.". ".$lines['text'];
    	$rangeValue = $xls->Range("A".($line+1));
    	$rangeValue->Value = "Стоимость . . . . . . . . . . . . . . . . . . . . .";
    	$rangeValue = $xls->Range("D".($line+1));
		$rangeValue->Value = $lines['price']." руб.";
        $total_price += $lines['price'];
        $line = $line + 2;
        $num++;
    }
    if($lines['hard_price'] > 0) {
    	$rangeValue = $xls->Range("A".$line);
		$rangeValue->Value = $num.". ".$lines['hard'];
    	$rangeValue = $xls->Range("A".($line+1));
    	$rangeValue->Value = "Стоимость . . . . . . . . . . . . . . . . . . . . .";
    	$rangeValue = $xls->Range("D".($line+1));
		$rangeValue->Value = $lines['hard_price']." руб.";
        $total_price += $lines['hard_price'];
        $line = $line + 2;
        $num++;
    }
}
$rangeValue = $xls->Range("D24");
$rangeValue->Value = $total_price." руб.";

$rangeValue = $xls->Range("A25");
$rangeValue->Value = date("H:i");
$rangeValue = $xls->Range("B25");
$rangeValue->Value = date("d.m.Y");

$sheet->PrintOut(); //$ActivePrinter='\\\\MIROTWOREZ-PC\\Zebra LP2742');

$xls->Workbooks[1]->SaveAs(recursiveRename('C:\xampp\htdocs\online-service.ru\print\\'.$print_id));

$xls->Quit();

//$xls->Release();
$xls = Null; 
$range = Null;

echo "<h1>Чек №".$print_id." отправлен на печать</h1>";
echo "</body>";
function recursiveRename ($name, $num=0) {
	clearstatcache();
	if (file_exists($name."-".$num.".xls")){
		echo "<h3>Такой чек уже был распечатан</h3>";		
		$num++;
		return recursiveRename($name, $num);
	}
	return $name."-".$num.".xls";
}

?>
