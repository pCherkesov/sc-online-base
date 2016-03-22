<?php

class total_month {

private $id_r;
private $date_start;
private $date_complete;
private $brand; 
private $model;
private $client_fio;
private $serial;
private $string;
private $worker;
private $complete;
//---------
static	$total_price;
/*
select `id_r`
from `work` 
where `date` between '01-00-2007' and '31-00-2007' and `hidden`='N' and `id_worker`=2 
order by `id_r`, `id` ASC;

select `id_r`, `client`, `client_fio`, `brand`, `model`
from `remont` as `r`, `client` as `c`, `brand` as `b`, `model` as `m`
where `r.id_r`=$line['id_r'] and `r.id_client`=`c.id_client` and `r.id_model`=`m.id_model` and `m.id_brand`=`b.id_brand` 
and r.complete`='Y' and `r.hidden`='N'
order by `id_r` ASC;

require ("Forms/total_month_header.html");

select `date`, `text`, `price`
from `work` 
where `id_r`=$lines['id_r'] and `hidden`='N' and `id_worker`=2 
order by `id` ASC;
*/
		
	function __construct () {
			$word_month = array ("НУЛЯБРЬ", "ЯНВАРЬ", "ФЕВРАЛЬ", "МАРТ", "АПРЕЛЬ", "МАЙ", "ИЮНЬ", "ИЮЛЬ", "АВГУСТ", "СЕНТЯБРЬ", "ОКТЯБРЬ", "НОЯБРЬ", "ДЕКАБРЬ");
		
		if (!isset($_GET['year'])) $year = date("Y"); else $year = $_GET['year'];
		
		if (!isset($_GET['month'])) {
				$month = "w.date BETWEEN '".date("Y-n")."-01' and '".date("Y-n")."-31'"; 
				$mon = date("n");	
		}
		else {
				$mon = $_GET['month'];	
				if ($mon == '13') {$mon = '1'; $year++;}
				if ($mon == '0') {$mon = '12'; $year--;}
				$month = "w.date BETWEEN '".$year."-".$mon."-01' and '".$year."-".$mon."-31'"; 		
		}

		if (isset($_GET['id_worker'])){
			$povtor_work = "&id_worker=".$_GET['id_worker']; 
			work_detail::$stat_id_worker = $_GET['id_worker'];
		}
		
		else {
			$povtor_work = "";
			work_detail::$stat_id_worker = 0;
		}
		
		echo "<a href='".$_SERVER['PHP_SELF']."?act=total&month=".($mon-1).$povtor_work."&year=".$year."'>
		<img src='Images/Icon/lefo.gif' border='0'></a> ";
		echo "<b> ".$word_month[$mon]." </b>";
		echo " <a href='".$_SERVER['PHP_SELF']."?act=total&month=".($mon+1).$povtor_work."&year=".$year."'>
		<img src='Images/Icon/prafo.gif' border='0'></a>";

		echo "<br />";
		require ("Forms/name_sort_bar.html");
		echo "<br />";
		
		if(isset($_REQUEST['id_worker'])&&$_REQUEST['id_worker']!='*'){
			$worker=" and w.id_worker=".$_REQUEST['id_worker']." and r.id_worker=w.id_worker";
		} else $worker='';

		global $S_CONFIG;
//======================================================
		$query = "SELECT w.id_r FROM work AS w, remont AS r WHERE ".$month.$worker." and w.hidden='N' GROUP BY w.id_r ASC ;";
		//echo $query;
		$result = mysql_query($query) or exit(mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$work[] = $line['id_r'];
		}
		$works = '';
		if (@$work == '') {
			echo "<br /><b>Выборка пуста</b>";
			require("footer.php");
			exit(); 
		}
		for($x=0; $x<count($work); $x++){
			$works .= $work[$x];
			if($x != count($work)-1) $works .= ", "; 
		}
//======================================================
		$query = "SELECT r.id_r, DATE_FORMAT(r.date, '%d.%m.%Y') AS date, b.brand, m.model, 
		c.client, r.client_fio, r.serial, r.string, r.complete, DATE_FORMAT(r.date_complete, '%d.%m.%Y') AS date_complete  
		FROM `".$S_CONFIG['prefix']."remont` AS r, `".$S_CONFIG['prefix']."model` AS m,
		 `".$S_CONFIG['prefix']."client` AS c, `".$S_CONFIG['prefix']."brand` AS b
		WHERE m.id_brand=b.id_brand and r.id_client=c.id_client and r.id_model=m.id_model and r.hidden='N' and r.id_r IN (".$works.") ORDER BY r.date_complete ASC";
//		echo $query;
 	   $result = mysql_query($query) or exit(mysql_error());

       while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
//=======================================================================
			$this->id_r = $line['id_r'];
			$this->date_start = $line['date'];
			$this->date_complete = $line['date_complete'];
			$this->brand = $line['brand'];
			$this->model = $line['model'];
			if ($line['client']=="ч/л"){
				$this->client_fio = $line['client_fio'];
			}
			else {
				$this->client_fio = $line['client'];
			}
			$this->serial = $line['serial'];
			$this->string = $line['string'];
			$this->complete = $line['complete'];
			include ("Forms/total_month_header.html");

			$result_query="SELECT id, date, text, price, id_worker 
							FROM `".$S_CONFIG['prefix']."work`
							WHERE id_r=".$this->id_r." and hidden='N'";
							
			$results = mysql_query($result_query) or exit(mysql_error());
			// Печать результатов в HTML
			self::$total_price = 0;
			while ($lines = mysql_fetch_array($results, MYSQL_ASSOC)) {
				$works_details = new work_detail ($lines['id'],
											 $lines['date'],
											 $lines['text'],
											 $lines['price'],
											 $lines['id_worker']);
				$works_details -> view ();
			}
			work_detail::view_footer( $this->string );
			$works_details = NULL;
		}
//===============================================
		echo "<hr align='center' width='95%' color='#000000' />";
		work_detail::view_total_footer();
	
	}
	static function total_price ($price) {
		self::$total_price	= self::$total_price + $price;
	}
	static function back_total_price () {
		return self::$total_price;
	}

}

class work_detail {
	 	protected $id;
		protected $date;
		protected $text;
		protected $price;
		protected $id_worker;
		static $stat_id_worker;
		static $total_month_price = 0;

		function __construct ($id, $date, $text, $price, $id_worker) {
			global $S_CONFIG;
			$this->id = $id;
			$this->date = $date;
			$this->text = $text;
			$this->price = $price;
			$this->id_worker = $id_worker;
			$query = "SELECT `color` 
			FROM `".$S_CONFIG['prefix']."worker`";
			$result = mysql_query($query) or exit(mysql_error());
			$this->worker_color[] = "FFFFFF";
			while($option = mysql_fetch_assoc($result)){
				$this->worker_color[] = $option['color'];
			}

		}
	
		public function view () {
			include ("Forms/total_month_tr.html");
			total_month::total_price($this->price);
			if(self::$stat_id_worker == 0 || $this->id_worker == self::$stat_id_worker)
				self::$total_month_price = self::$total_month_price + $this->price;
		}
		
		static function view_footer ( $znaki ) {
		  $string  = "<img src='Images/Icon/s_0_".$znaki[0].".png' border='0' name='s_0' alt='Просрочено' />";
  		$string .= "<img src='Images/Icon/s_1_".$znaki[1].".png' border='0' name='s_1' alt='Проблема' />";
  		$string .= "<img src='Images/Icon/s_2_".$znaki[2].".png' border='0' name='s_2' alt='Клиент согласен' />";
  		$string .= "<img src='Images/Icon/s_3_".$znaki[3].".png' border='0' name='s_3' alt='Требуется соглашение' />";
  		$string .= "<img src='Images/Icon/s_4_".$znaki[4].".png' border='0' name='s_4' alt='!!! ВНИМАНИЕ !!!' />";
  		$string .= "<img src='Images/Icon/s_5_".$znaki[5].".png' border='0' name='s_5' alt='Ремонт закончен' />";
  		$string .= "<img src='Images/Icon/s_6_".$znaki[6].".png' border='0' name='s_6' alt='Неудача' />";
  		$string .= "<img src='Images/Icon/s_7_".$znaki[7].".png' border='0' name='s_7' alt='Ждём поставки' />";
		
			include ("Forms/total_month_footer.html");
		}
		
		static function view_total_footer () {
			include ("Forms/total_month_summed_footer.html");
		}
}
?>
