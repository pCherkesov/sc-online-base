<?php
class many_work {

private $works;
private $data = array();
private $urls = array('lefturl' => "", 'month' => "", 'righturl' => "");

    function __construct () {
		global $S_CONFIG;
		switch($_REQUEST['act']){
                        // Выбрать все работы с разбивкой по месяцам
			case "all":
				$add_query = " and r.hidden='N'";

				if (!isset($_GET['year'])) $year = date("Y"); else $year = $_GET['year'];
                                $month = "r.date BETWEEN '".$year."-01-01' and '".$year."-12-31'"; 
                                
                                $this->urls['month'] = $year;
                                $this->urls['lefturl'] = $_SERVER['PHP_SELF']."?act=".$_REQUEST['act']."&year=".($year-1);
                                $this->urls['righturl'] = $_SERVER['PHP_SELF']."?act=".$_REQUEST['act']."&year=".($year+1);

				break;

                        // Выбрать незавершённые
			case "incomplete":
				$add_query = " and r.complete='N' and r.hidden='N'"; 
				$month = "r.date BETWEEN '2006-00-00' and '2020-12-31'";
				break;

                        // Выбрать завешённые с разбивкой по месяцам
			case "complete":
				$add_query= " and r.complete='Y' and r.hidden='N'";
				
				$word_month = array ("НУЛЯБРЬ", "ЯНВАРЬ", "ФЕВРАЛЬ", "МАРТ", "АПРЕЛЬ", "МАЙ", "ИЮНЬ", "ИЮЛЬ", "АВГУСТ", "СЕНТЯБРЬ", "ОКТЯБРЬ", "НОЯБРЬ", "ДЕКАБРЬ");
				
				if (!isset($_GET['year'])) $year = date("Y"); else $year = $_GET['year'];

				if (!isset($_GET['month'])) {
					$month = "r.date BETWEEN '".$year."-".date("n")."-01' and '".$year."-".date("n")."-31'"; 
					$mon = date("n");	
				}
				else {
					$mon = $_GET['month'];
					if ($mon == '13') {$mon = '1'; $year++;}
					if ($mon == '0') {$mon = '12'; $year--;}
					$month = "r.date BETWEEN '".$year."-".$mon."-01' and '".$year."-".$mon."-31'"; 
				}
				
				if (isset($_GET['id_worker'])) $povtor_work = "&id_worker=".$_GET['id_worker']; else $povtor_work = "";

                                $this->urls['lefturl'] = $_SERVER['PHP_SELF']."?act=".$_REQUEST['act']."&month=".($mon-1).$povtor_work."&year=".$year;
                                $this->urls['month'] = $word_month[$mon]. " " . $year;
                                $this->urls['righturl'] = $_SERVER['PHP_SELF']."?act=".$_REQUEST['act']."&month=".($mon+1).$povtor_work."&year=".$year;

                                break;
			default: 
				echo "Script error"; 
				//include("footer.php");
				exit(); 
				break;
		}

                // Поиск
		if (isset($_REQUEST['search'])){
		  $add_query = " and r.hidden='N'";
			$month = "r.date BETWEEN '2006-00-00' and '2020-12-31'";
			if (isset($_REQUEST['client']) && $_REQUEST['client']!='0') 
				$client = " and r.id_client=".$_REQUEST['client']; else $client = "";
			if (isset($_REQUEST['model']) && $_REQUEST['model']!='0') 
				$model = " and r.id_model=".$_REQUEST['model']; else $model = "";
			if (isset($_REQUEST['serial']) && $_REQUEST['serial']!='') 
				$serial =  " and r.serial='".$_REQUEST['serial']."'"; else $serial ="";
		} else $client = $model = $serial = "";
		
		// Дополнительная выборка по работнику
        if(isset($_REQUEST['id_worker'])&&$_REQUEST['id_worker']!='*'){
			$worker=" and w.id_worker=r.id_worker and w.id_worker=".$_REQUEST['id_worker'];
		} else $worker='';


		$query = "SELECT r.complete, r.id_r, DATE_FORMAT(r.date, '%d.%m.%Y') as date, r.string, b.brand, m.model, c.client, c.client_tel_0, r.client_fio, r.client_tel, r.serial, r.id_worker
		FROM `".$S_CONFIG['prefix']."remont` AS r, `".$S_CONFIG['prefix']."model` AS m,
		`".$S_CONFIG['prefix']."client` AS c, `".$S_CONFIG['prefix']."brand` AS b, 
		`".$S_CONFIG['prefix']."worker` AS w
		WHERE 
		".$month." 
		 and m.id_brand=b.id_brand
		 ".$model." 
		 and r.id_model=m.id_model
		 ".$client." 
		and r.id_client=c.id_client
		".$worker.
		$serial.
		$add_query." 
		GROUP BY  r.date, r.id_r ASC";
//		echo "<br />".$query."<br />";
	    $result = mysqli_query($S_CONFIG['link'], $query) or exit(mysql_error());
		
            // Подключение
            //include ('Forms/name_sort_bar.html');
            //include ('Forms/search_bar_table.html');
		
	    while($option_query=mysqli_fetch_assoc($result)){
			$this->works[$option_query['id_r']] = new vision (
				$option_query['complete'], 
				$option_query['id_r'], 
				$option_query['string'], 
				$option_query['date'],
				$option_query['brand'], 
				$option_query['model'], 
				$option_query['client'],
				$option_query['client_tel_0'],
				$option_query['client_fio'],
				$option_query['client_tel'],
				$option_query['serial'],
				$option_query['id_worker']
			);

			array_push($this->data, $this->works[$option_query['id_r']]->view($_REQUEST['act']));
			$this->works[$option_query['id_r']] = NULL;
		}
	    //echo "</table><br />";
	}

    function render () {
        return array (
            'works' => $this->data,
            'month' => $this->urls['month'],
            'lefturl' => $this->urls['lefturl'],
            'righturl' => $this->urls['righturl'],
        );
    }
}

class vision {
	public $complete; 
	private $id_r;
	private $string; 
	private $date; 
	private $brand; 
	private $model;
	private $client;
	private $client_tel_0;
	private $client_tel;
	private $client_fio;
	private $serial;
	private $id_worker;
	static $worker_colors;
	
	function __construct ($complete, $id_r, $string, $date, $brand, $model, $client, $client_tel_0, $client_fio, $client_tel, $serial, $id_worker) {
		global $S_CONFIG;
		$this->complete = $complete;
		$this->id_r = $id_r;
		$this->string = $string;
		$this->date = $date;
		$this->brand = $brand;
		$this->model = $model;
		if ($client=="ч/л"){
			$this->client_fio = $client_fio;
			$this->client_tel = $client_tel;
		}
		else {
			$this->client_fio = $client;
			$this->client_tel = $client_tel_0;
		}
		$this->serial = $serial;
		$this->id_worker = $id_worker;
		
		if(!isset(self::$worker_colors)) {
			$query = "SELECT `color` FROM `".$S_CONFIG['prefix']."worker`";
			$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysql_error());
			self::$worker_colors[] = "FFFFFF";
			while($option = mysqli_fetch_assoc($result)){
				self::$worker_colors[] = $option['color'];
			}
		}

	}
	
	public function view ($act) {
		//include_once ('Forms/many_work_table.html');
		//include ('Forms/many_work_tr.html');
        return array(
            'complete' => $this->complete, 
            'id_r' => $this->id_r,
            'string' => $this->string, 
            'date' => $this->date, 
            'brand' => $this->brand, 
            'model' => $this->model,
            'client' => $this->client,
            'client_tel_0' => $this->client_tel_0,
            'client_tel' => $this->client_tel,
            'client_fio' => $this->client_fio,
            'serial' => $this->serial,
            'id_worker' => $this->id_worker,
            'worker_colors' => self::$worker_colors,
            'znaki' => $this->znaki(),
        );
	}

	private function znaki () {
		$znaki='';
		for ($x = 0; $x < 8; $x++){
			$znaks[] = substr($this->string, $x, 1);
		}
		if ($znaks[0]=='Y')$znaki=$znaki."<img src='Images/Icon/s_0_Y.png' border='0' name='s_0' alt='Просрочено'/>";
		if ($znaks[1]=='Y')$znaki=$znaki."<img src='Images/Icon/s_1_Y.png' border='0' name='s_1' alt='Проблема'/>";
		if ($znaks[2]=='Y')$znaki=$znaki."<img src='Images/Icon/s_2_Y.png' border='0' name='s_2' alt='Клиент согласен'/>";
		if ($znaks[3]=='Y')$znaki=$znaki."<img src='Images/Icon/s_3_Y.png' border='0' name='s_3' alt='Требуется соглашение'/>";
		if ($znaks[4]=='Y')$znaki=$znaki."<img src='Images/Icon/s_4_Y.png' border='0' name='s_4' alt='!!! ВНИМАНИЕ !!!'/>";
		if ($znaks[5]=='Y')$znaki=$znaki."<img src='Images/Icon/s_5_Y.png' border='0' name='s_5' alt='Работа завершена'/>";
		if ($znaks[6]=='Y')$znaki=$znaki."<img src='Images/Icon/s_6_Y.png' border='0' name='s_6' alt='Неудача'/>";
		if ($znaks[7]=='Y')$znaki=$znaki."<img src='Images/Icon/s_7_Y.png' border='0' name='s_7' alt='Ждём поставки' />";

		if ($znaki=='') return "&nbsp;";
		
		return $znaki;
	}	
}
?>
