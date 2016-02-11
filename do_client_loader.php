<?php
// Load JsHttpRequest backend.
require_once "JsHttpRequest/JsHttpRequest.php";
// Create main library object. You MUST specify page encoding!
$JsHttpRequest = new JsHttpRequest("windows-1251");
//
require("init.php");

echo "<img src='Images/Icon/temp/nav-left.png' border ='0' onclick=\"window.location='".$_SERVER['HTTP_REFERER']."'\" /><br />";
echo "<table width='95%' border='1' cellspacing='0' cellpadding='1' background='Images/fon.png'
		bordercolorlight='black' bordercolordark='white'>";
echo "<tr><td valign='center'>";

if((int)$_REQUEST['idr'] != '0') $id_r = $_REQUEST['idr'];
else {
		echo "<b>Номер работы введён неправильно</b>";
		echo "</td></tr></table>";
		exit();
}
//echo md5(395);
if($_REQUEST['pass'] == "password") $pass = '';
else $pass = md5($_REQUEST['pass']);
$client = new single_work ($id_r, $pass);

class single_work {

//public $complete; 
private $id_r;
private $pass;
private $string; 
private $original_date; 
private $type;
private $brand; 
private $model;
//private $client;
private $client_tel;
private $client_fio;
//=======================
private $complect;
private $prin;
private $worker;
private $serial;
private $counter;
private $defect;
		
	
	function __construct ($id_r, $pass) {
		global $S_CONFIG;	
		$this->id_r = $id_r;
		$this->pass = $pass;
		
		$query="SELECT r.complete, r.date, r.string, t. type, b.brand, m.model, c.client, c.client_tel_0, r.client_fio, r.client_tel, r.counter, r.serial, r.defect, r.complect, p.prin, w.worker
		FROM `".$S_CONFIG['prefix']."remont` AS r, `".$S_CONFIG['prefix']."model` AS m,
		 `".$S_CONFIG['prefix']."client` AS c, `".$S_CONFIG['prefix']."brand` AS b,
		  `".$S_CONFIG['prefix']."prin` AS p, `".$S_CONFIG['prefix']."worker` AS w, `".$S_CONFIG['prefix']."type` AS t
		WHERE w.id_worker=r.id_worker and r.id_prin=p.id_prin and m.id_brand=b.id_brand and r.id_client=c.id_client and r.id_model=m.id_model and m.id_type=t.id_type and r.id_r=".$this->id_r." and r.pass='".$this->pass."' 
		LIMIT 1";
 	   $result = mysql_query($query) or exit(mysql_error());
       while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	   		if (empty($result)) {
					echo "<b>Выбранной работы не существует, либо пароль введён неправильно.</b>";
					echo "</td></tr></table>";
					exit();
			}
//=======================================================================
			$this->complete = $line['complete'];
			$this->string = $line['string'];
			$this->original_date = $line['date'];
			$this->type = $line['type'];
			$this->brand = $line['brand'];
			$this->model = $line['model'];
			if ($line['client']=="ч/л"){
				$this->client_fio = $line['client_fio'];
				$this->client_tel = $line['client_tel'];
			}
			else {
				$this->client_fio = $line['client'];
				$this->client_tel = $line['client_tel_0'];
			}
			$this->complect = $line['complect'];
			$this->prin = $line['prin'];
			$this->worker = $line['worker'];
			$this->serial = $line['serial'];
			$this->counter = $line['counter'];
			$this->defect = $line['defect'];
		}

		include ("Forms/client_work_header.html");

		echo "<table width='99%' border='1' cellspacing='0' cellpadding='3' bgcolor='#FFFFFF'
		bordercolorlight='black' bordercolordark='white' align='center'>";
		
		
		$result_query="SELECT id, date, text, price, hard, hard_price, id_worker 
						FROM `".$S_CONFIG['prefix']."work`
						WHERE id_r=".$this->id_r." and hidden='N'";
						
		$results = mysql_query($result_query) or exit(mysql_error());
		// Печать результатов в HTML
		$total_price=$total_hard_price=0;
		while ($lines = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$works_details = new work_detail ($lines['id'],
										 $lines['date'],
										 $lines['text'],
										 $lines['price'],
										 $lines['hard'],
										 $lines['hard_price'],
										 $lines['id_worker']);
			$works_details -> view ();
		}
		work_detail::view_footer();
		$works_details = NULL;
		echo "</td></tr></table>";
//		if($this->complete=="N")
//			echo "<a href='request.php?action=complete&id_r=".$_REQUEST['id_r']."&refer=2'><img src='Images/Icon/p_complete.gif' border='0' /></a>";
//			else echo "<a href='request?action=complete&id_r=".$_REQUEST['id_r']."&refer=3'>Uncomplete</a>";

//===============================================
		echo "<br /><hr align='center' width='98%' color='#000000' />";
//		self::edit();
	
	}
	function znaki (){
		$znaki = $this->string;
//		echo "<br />";
		echo "<img src='Images/Icon/s_0_".$znaki[0].".png' border='0' name='s_0' alt='Просрочено' />";
		echo "<img src='Images/Icon/s_1_".$znaki[1].".png' border='0' name='s_1' alt='Проблема' />";
		echo "<img src='Images/Icon/s_2_".$znaki[2].".png' border='0' name='s_2' alt='Клиент согласен' />";
		echo "<img src='Images/Icon/s_3_".$znaki[3].".png' border='0' name='s_3' alt='Требуется соглашение' />";
		echo "<img src='Images/Icon/s_4_".$znaki[4].".png' border='0' name='s_4' alt='!!! ВНИМАНИЕ !!!' />";
		echo "<img src='Images/Icon/s_5_".$znaki[5].".png' border='0' name='s_5' alt='Ремонт закончен' />";
		echo "<img src='Images/Icon/s_6_".$znaki[6].".png' border='0' name='s_6' alt='Неудача' />";
}

//	function edit (){
//		echo "<div id='edit_menus'>";
//		echo "<img src='Images/Icon/b_edit_button.gif' border='0' name='edit_status_button'
//		onclick=\"doEdit(".$this->id_r.", 0);\" />";
//		echo "</div>";	
//	}
	static function repair_time($rep_time, $parse) {
		if (preg_match("|([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})|i", $rep_time, $date))
				return $date['3'].$parse.$date['2'].$parse.$date['1'];
			else return "--".$parse."--".$parse."----";
	}

}

class work_detail {
	 	protected $id;
		protected $date;
		protected $text;
		protected $price;
		protected $hard;
		protected $hard_price;
		protected $id_worker;
		protected $worker_color;
		static $total_price = 0;
		static $total_hard_price = 0;

		function __construct ($id, $date, $text, $price, $hard, $hard_price, $id_worker) {
			global $S_CONFIG;
			$this->id = $id;
			$this->date = $date;
			$this->text = $text;
			$this->price = $price;
			$this->hard = $hard;
			$this->hard_price = $hard_price;
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
			include ("Forms/client_work_tr.html");
			self::$total_price = self::$total_price + $this->price;
			self::$total_hard_price = self::$total_hard_price + $this->hard_price;
		}
		
		static function view_footer () {
			include ("Forms/client_work_footer.html");
		}
}

	// Store resulting data in $_RESULT array (will appear in req.responseJs).
	$_RESULT = array(
//	  "pass"     => @$_REQUEST['pass'],
	  "id_r"    => @$_REQUEST['idr']
	); 

// Below is unparsed stream data (will appear in req.responseText).

?>