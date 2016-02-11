<?php
class single_work {

public $complete; 
public $id_r;
private $string; 
private $original_date;
private $complete_date;
private $type;
private $brand;
private $id_model; 
private $model;
//=======================
private $client;
private $id_client;
private $client_fio;
private $client_tel;
private $client_face;
private $client_g_face;
private $client_g_tel;
//=======================
private $complect;
private $prin;
private $worker;
private $serial;
private $counter;
private $defect;
//=======================
private $works_details = array();        
	
	function __construct () {
		global $S_CONFIG;	
		$query="SELECT r.complete, DATE_FORMAT(r.date, '%d.%m.%Y') as date, DATE_FORMAT(r.date_complete, '%d.%m.%Y') as date_complete, r.string, t. type, b.brand, m.id_model, m.model, c.id_client, c.client, c.client_face, c.client_tel_0, r.client_fio, r.client_tel, r.counter, r.serial, r.defect, r.complect, p.prin, w.worker
		FROM `".$S_CONFIG['prefix']."remont` AS r, `".$S_CONFIG['prefix']."model` AS m,
		 `".$S_CONFIG['prefix']."client` AS c, `".$S_CONFIG['prefix']."brand` AS b,
		  `".$S_CONFIG['prefix']."prin` AS p, `".$S_CONFIG['prefix']."worker` AS w, `".$S_CONFIG['prefix']."type` AS t
		WHERE w.id_worker=r.id_worker and r.id_prin=p.id_prin and m.id_brand=b.id_brand and r.id_client=c.id_client and r.id_model=m.id_model and m.id_type=t.id_type and r.id_r=".$_REQUEST['id_r']."
		ORDER BY r.id_r ASC";

 	   $result = mysqli_query($S_CONFIG['link'], $query) or exit(mysql_error());

       while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
//=======================================================================
			$this->complete = $line['complete'];
			$this->id_r = $_REQUEST['id_r'];
			$this->string = $line['string'];
			$this->original_date = $line['date'];
			$this->complete_date = $line['date_complete'];
			$this->type = $line['type'];
			$this->brand = $line['brand'];
			$this->id_model = $line['id_model'];
			$this->model = $line['model'];
			$this->id_client = $line['id_client'];
			if ($line['client']=="ч/л"){
				$this->client = 0;
				$this->client_fio = $line['client_fio'];
			}
			else {
				$this->client = 1;
				$this->client_g_face = $line['client_face'];
				$this->client_g_tel = $line['client_tel_0'];
				$this->client_fio = $line['client'];
			}
			$this->client_face = $line['client_fio'];
			$this->client_tel = $line['client_tel'];

			$this->complect = $line['complect'];
			$this->prin = $line['prin'];
			$this->worker = $line['worker'];
			$this->serial = $line['serial'];
			$this->counter = $line['counter'];
			$this->defect = $line['defect'];
		}
		
		$result_query="SELECT id, DATE_FORMAT(date, '%d.%m.%Y') as date, text, price, hard, hard_price, id_worker 
						FROM `".$S_CONFIG['prefix']."work`
						WHERE id_r=".$_REQUEST['id_r']." and hidden='N'";
						
		$results = mysqli_query($S_CONFIG['link'], $result_query) or exit(mysql_error());
		// Печать результатов в HTML
		$total_price=$total_hard_price=0;
		while ($lines = mysqli_fetch_array($results, MYSQL_ASSOC)) {
			$works = new work_detail ($lines['id'],
										 $lines['date'],
										 $lines['text'],
										 $lines['price'],
										 $lines['hard'],
										 $lines['hard_price'],
										 $lines['id_worker']);
			array_push($this->works_details, $works->view());
		}
                
		// work_detail::view_footer();
		$works = NULL;
                
//		echo "<div class=\"toolbar\">";
//		self::znaki();
//				echo "<button class=\"buttonbar warning\" type=\"button\"><span><span>Удалить работу</span></span></button>";
//		echo "<button class=\"buttonbar\" type=\"button\" onclick=\"location.replace('act_priema_html.php?id_r=".$this->id_r."');\"><span><span>Печать бланка</span></span></button>";
//		echo "<button class=\"buttonbar\" type=\"button\" onclick=\"location.replace('request.php?action=complete&id_r=".$_REQUEST['id_r']."&refer=2');\"><span><span>Завершить работу</span></span></button>";
//		echo "<button class=\"buttonbar primary\" type=\"button\" onclick=\"doEdit(".$this->id_r.", 0);\"><span><span>Добавить запись</span></span></button>";
//                echo "</div>";
//                echo "<br /><br />";

		//self::edit();
	
	}
	function znaki (){
		$znaki = $this->string;
		$icons = array();
		for ($i=0; $i < strlen($znaki); $i++) { 
			array_push($icons, $znaki[$i]);
		}
		return $icons;
//		echo "<div class=\"statusbar\">";
//		echo "<img src='Images/Icon/s_0_".$znaki[0].".png'  name='s_0' id='s_0' title='Просрочено' 
//			onclick=\"doStatus(0, ".$_REQUEST['id_r'].")\"/>";
//		echo "<img src='Images/Icon/s_1_".$znaki[1].".png'  name='s_1' id='s_1' title='Проблема' 
//			onclick=\"doStatus(1, ".$_REQUEST['id_r'].")\"/>";
//		echo "<img src='Images/Icon/s_2_".$znaki[2].".png'  name='s_2' id='s_2' title='Клиент согласен'
//			onclick=\"doStatus(2, ".$_REQUEST['id_r'].")\"/>";
//		echo "<img src='Images/Icon/s_3_".$znaki[3].".png'  name='s_3' id='s_3' title='Требуется соглашение' 
//			onclick=\"doStatus(3, ".$_REQUEST['id_r'].")\"/>";
//		echo "<img src='Images/Icon/s_4_".$znaki[4].".png'  name='s_4' id='s_4' title='!!! ВНИМАНИЕ !!!' 
//			onclick=\"doStatus(4, ".$_REQUEST['id_r'].")\"/>";
//		echo "<img src='Images/Icon/s_5_".$znaki[5].".png'  name='s_5' id='s_5' title='Ремонт закончен' 
//			onclick=\"doStatus(5, ".$_REQUEST['id_r'].")\"/>";
//		echo "<img src='Images/Icon/s_6_".$znaki[6].".png'  name='s_6' id='s_6' title='Неудача' 
//			onclick=\"doStatus(6, ".$_REQUEST['id_r'].")\"/>";
//		echo "<img src='Images/Icon/s_7_".$znaki[7].".png'  name='s_7' id='s_7' title='Ждём поставки' 
//			onclick=\"doStatus(7, ".$_REQUEST['id_r'].")\"/>";
//		echo "</div>";
}

	function edit (){
		echo "<div id='edit_menus'>";
		//echo "<img src='Images/Icon/b_edit_button.gif' border='0' name='edit_status_button'
		//onclick=\"doEdit(".$this->id_r.", 0);\" />";
		echo "</div>";	
	}
        
    function render () {
            return array (
            	'complete' => $this->complete,
                'id_r' => $this->id_r,
                'string' => $this->string,
                'original_date' => $this->original_date,
                'complete_date' => $this->complete_date,
                'type' => $this->type,
                'brand' => $this->brand,
                'id_model' => $this->id_model,
                'model' => $this->model,
                'id_client' => $this->id_client,
                'client_fio' => $this->client_fio,
                'client_tel' => $this->client_tel,
                'complect' => $this->complect,
                'prin' => $this->prin,
                'worker' => $this->worker,
                'serial' => $this->serial,
                'counter' => $this->counter,
                'defect' => $this->defect,
                'znaki' => $this->znaki(),
                'works' => $this->works_details,
                'total_price' => work_detail::$total_price,
				'total_hard_price' => work_detail::$total_hard_price,
                'worker_colors' => work_detail::$worker_colors,
            );           
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
		static $worker_colors;
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
                        
			if(!isset(self::$worker_colors)) {
				$query = "SELECT `color` FROM `".$S_CONFIG['prefix']."worker`";
				$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysql_error());
				self::$worker_colors[] = "FFFFFF";
				while($option = mysqli_fetch_assoc($result)){
					self::$worker_colors[] = $option['color'];
				}
			}
		}
	
		public function view () {
			//include ("Forms/single_work_tr.html");
			self::$total_price = self::$total_price + $this->price;
			self::$total_hard_price = self::$total_hard_price + $this->hard_price;
                        return array (
                          'date' => $this->date,
                          'text' => $this->text,
                          'price' => $this->price,
                          'hard_text' => $this->hard,
                          'hard_price' => $this->hard_price,
                          'id_worker' => $this->id_worker,
                        );
		}
		
		static function view_footer () {
			//include ("Forms/single_work_footer.html");
		}
}
?>