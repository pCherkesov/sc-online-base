<?php
require("init.php");

$record = new sqli_and_log;

class sqli_and_log {
    static $mess = '';
    static $f_error = '';
    static $n_error = '';
	
    function __construct () {
            switch ($_REQUEST['action']) {
            case "insert_record": 	self::insert_record(); 	break;
            case "insert_work": 	self::insert_work(); 	break;
            case "update_work":		self::update_work();	break;
            case "delete_record": 	self::delete_record(); 	break;
            case "delete_work": 	self::delete_work(); 	break;
            case "add_hardware": 	self::add_hardware(); 	break;
            case "complete":	 	self::complete(); 	break;
            default: echo "<b>!!!ERROR!!!</b><br />";		break;
        }
    }
    // self::$f_error - FATAL ERROR
    // self::$n_error - NON FATAL ERROR
    function insert_record () {
        global $S_CONFIG;

        $data = self::fuck_time($_POST['data'], "-");

        if($_POST['client'] == '') self::$f_error .= "Не выбран клиент<br />"; else $client = addslashes($_POST['client']);
        if ($client != '1') {
            if($_POST['firma_face'] == 'Контактное лицо' || $_POST['firma_face'] == '') self::$n_error .= "Не внесено контактное лицо<br />";
            $client_fio = addslashes($_POST['firma_face']);
            if($_POST['firma_tel'] == 'Контактный телефон' || $_POST['firma_tel'] == '') self::$n_error .= "Не указан контактный телефон<br />";
            $client_tel = addslashes($_POST['firma_tel']);
        }
        else {
            if($_POST['firma_face'] == 'Фамилия клиента' || $_POST['firma_face'] == '') self::$f_error .= "Не внесена фамилия клиента<br />"; else $client_fio = $_POST['firma_face'];
            if($_POST['firma_tel'] == 'Телефон клиента' || $_POST['firma_tel'] == '') self::$n_error .= "Не указан телефон клиента<br />";
            $client_tel = addslashes($_POST['firma_tel']);
        }
        if(!isset($_POST['selected_model']) or (int)$_POST['selected_model'] == '0') self::$f_error .= "Не указана модель<br />";
        else $selected_model = addslashes($_POST['selected_model']);

        if(!isset($_POST['serial'])) {self::$n_error .= "Не указан серийный номер<br />"; $serial = "none";}
        else $serial = addslashes(strtoupper($_POST['serial']));

        if(!isset($_POST['complect'])) self::$n_error .= "Не указана комплектность<br />";
        else $complect = addslashes($_POST['complect']);

        if(!isset($_POST['defect'])) self::$f_error .= "Не указан дефект<br />";
        else $defect = addslashes($_POST['defect']);

        // `id_r`, `pass`, `date`, `string`, `id_client`, `client_fio`, `client_tel`, `id_model`, `complect`, `defect`,
        // `serial`, `counter`, `id_prin`, `id_worker`, `complete`, `buh_check`, `buh_text`, `hidden`
        if(self::$f_error == '') {
            $pass = self::pass_generator();
            $query = "INSERT INTO `".$S_CONFIG['prefix']."remont` VALUE (0, '".md5($pass)."', '".$data."', 'NNNNNNNNNN',
            '".$client."', '".$client_fio."', '".$client_tel."', '".$selected_model."', '".$complect."',
            '".$defect."', '".$serial."', 0, '".$_POST['prin']."',
            1, 'N','0', 'N' , '', 'N')";
            self::record ($query);
            $insert_id = mysql_insert_id();
            //$query = "UPDATE `".$S_CONFIG['prefix']."remont` SET `pass`='".md5($insert_id)."' WHERE `id_r`=".$insert_id." LIMIT 1";
            //self::record ($query);
            self::$mess .= "<b>Запись добавлена</b><br />";
            self::$mess .= "<a href='skeleton.php?act=incomplete&id_r=".$insert_id."'>Перейти к добавленной работе</a><br />";
        }
        //self::view();
        if(isset($_POST['print']) && self::$f_error == '') require("act_priema_html.php");//require("xls.php");
    }
	   
    function insert_work () {
        global $S_CONFIG;
        $data = self::fuck_time($_POST['data'], "-");
        $repair = str_replace("\r\n","<br />",addslashes($_POST['work']));
        $hardware = str_replace("\r\n","<br />",addslashes($_POST['hard']));
        if((int)$_REQUEST['id_r'] == '0') self::$f_error .= "Неправильно передан параметр<br />";
        else
        $query = "INSERT INTO `".$S_CONFIG['prefix']."work` VALUE
            (0, '".$_REQUEST['id_r']."', '". $data."', '".$repair."',
                '".$_POST['price']."', '".$hardware."',
                '".$_POST['hard_price']."', '".$_POST['worker']."',	'N')";
        if(self::$f_error == '') self::record ($query);
        self::$mess .= "<b>Работа добавлена</b><br />";
        self::view();
    }

    function update_work () {
        global $S_CONFIG;
        $data = self::fuck_time($_POST['data'], "-");
        $repair = str_replace("\r\n","<br />",addslashes($_POST['work']));
        $hardware = str_replace("\r\n","<br />",addslashes($_POST['hard']));
        if((int)$_POST['id'] == '0') self::$f_error .= "Неправильно передан параметр<br />";
        else $query = "UPDATE `".$S_CONFIG['prefix']."work` SET date='".$data."',
                                                                text='".$repair."',
                                                                price='".$_POST['price']."',
                                                                hard='".$hardware."',
                                                                hard_price='".$_POST['hard_price']."',
                                                                id_worker='".$_POST['worker']."'
        WHERE `id`=".$_POST['id']." LIMIT 1";
        if(self::$f_error == '') self::record ($query);
        self::$mess .= "<b>Работа изменена</b><br />";
        self::view();
    }
	
	function delete_record () {
		global $S_CONFIG;
		if((int)$_GET['id_del'] == '0') self::$f_error .= "Неправильно передан параметр<br />";
		else {
			$query1="UPDATE `".$S_CONFIG['prefix']."work` SET `hidden`='Y' WHERE `id_r`=".$_GET['id_del'];
			$query2="UPDATE `".$S_CONFIG['prefix']."remont` SET `hidden`='Y' WHERE `id_r`=".$_GET['id_del']." LIMIT 1";
		}
		if(self::$f_error == '') { self::record ($query1); self::record ($query2); }
		self::$mess .= "<b>Запись удалена</b><br />";
		self::view();
	}

	function delete_work () {
		global $S_CONFIG;
		if((int)$_GET['id'] == '0') self::$f_error .= "Неправильно передан параметр<br />";
		else $query="UPDATE `".$S_CONFIG['prefix']."work` SET `hidden`='Y' WHERE `id`=".$_GET['id']." LIMIT 1";
		if(self::$f_error == '') self::record ($query);
		self::$mess .= "<b>Работа удалена</b><br />";
		self::view();
	}
	
	function add_hardware () {
		global $S_CONFIG;
		if(!isset($_POST['new_device'])){
			$query = "INSERT INTO `".$S_CONFIG['prefix']."client` VALUE (0, 
											'".$_POST['client_fio']."', 
											'".$_POST['client_tel']."')";
			if(self::$f_error == '') self::record ($query);
			self::$mess .= "<b>Добавлен новый клиент</b><br />";
			self:: view();

		}
		else {
			switch ($_POST['new_device']){
				case "0": $query = "INSERT INTO `".$S_CONFIG['prefix']."type` VALUE (0, 
											'".$_POST['new_type']."')"; break;
				case "1": $query = "INSERT INTO `".$S_CONFIG['prefix']."brand` VALUE (0, 
											'".$_POST['new_brand']."')"; break;
				case "2": $query = "INSERT INTO `".$S_CONFIG['prefix']."model` VALUE (0, 
											'".$_POST['type']."',
											'".$_POST['brand']."',
											'".$_POST['new_model']."')"; break;
				default: echo  exit("<b>Неправильно переданный параметр</b><br />");
			}
			if(self::$f_error == '') self::record ($query);
			self::$mess .= "<b>Добавлен(а) новый тип(брэнд, модель)</b><br />";
			self::view();
		}
	
	}

	function complete () {
		global $S_CONFIG;
		if((int)$_GET['id_r'] == '0' || (int)$_REQUEST['refer'] == '0') self::$f_error .= "Неправильно передан параметр<br />";
		else {
			if($_REQUEST['refer'] == '2') 
			$query="UPDATE `".$S_CONFIG['prefix']."remont` SET `complete`='Y', `date_complete`='".date("Y-m-d")."' WHERE `id_r`=".$_GET['id_r']." LIMIT 1";
			else 
			$query="UPDATE `".$S_CONFIG['prefix']."remont` SET `complete`='N', `date_complete`='' WHERE `id_r`=".$_GET['id_r']." LIMIT 1";
		}
		if(self::$f_error == '') self::record ($query);
		self::$mess .= "<b>Работа завершена</b><br />";
		self::view();
	
	}

	static function repair_time($rep_time, $parse) {
		if (preg_match("|([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})|i", $rep_time, $date))
				return $date['3'].$parse.$date['2'].$parse.$date['1'];
			else return "--".$parse."--".$parse."----";
	}
	
	static function fuck_time($rep_time, $parse) {
		if (preg_match("|([0-9]{1,2}).([0-9]{1,2}).([0-9]{4})|i", $rep_time, $date))
				return $date['3'].$parse.$date['2'].$parse.$date['1'];
			else return "----".$parse."--".$parse."--";
	}

	static function record ($query) {
	    $query = str_replace("\r\n","",$query);	
	    $query = str_replace("	","",$query);	
		  $file = fopen("Logs/log.txt", "a") or die("Ошибка чтения файла лога");
      flock($file, 2);
      fputs($file, $query."\n");
		  flock($file, 3);
		  fclose($file);
		  $rec = mysql_query($query) or exit(mysql_error());
	}
	
	static function view () {
			$page_header = "Внесение изменений в БД";
			if(!isset($_REQUEST['refer'])){
				$redirect = $_SERVER['HTTP_REFERER'];
			}
			else {
				switch ($_REQUEST['refer']){
					case "1": $redirect = "index.php"; break;
					case "2": $redirect = "skeleton.php?act=incomplete"; break;
					case "3": $redirect = "skeleton.php?act=incomplete&id_r=".$_REQUEST['id_r']; break;
					default: $redirect = $_SERVER['HTTP_REFERER']; break;
				}
			}
			$redirect_sec = $redirect; 
			if(self::$f_error != '') unset ($redirect);
			require ("header.php");
			echo "<table width='95%' border='1' cellspacing='0' cellpadding='1' bgcolor='white'
				bordercolorlight='black' bordercolordark='white'
				onclick=\"window.location='".$redirect_sec."'\">";
			echo "<tr height='150px'><td align='center' valign='center'>";
			if(self::$f_error == '') {
				if(self::$n_error != '') {
						echo "<table width='60%' border='1' cellspacing='0' cellpadding='1'
							bordercolorlight='black' bordercolordark='white' background='Images/fon.png' >";
						echo "<caption><b>Нефатальная ошибка</b></caption>";
						echo "<tr><td align='left'>";
						echo self::$n_error;
						echo "</td></tr></table><br />";
				}
				echo self::$mess;
			}
			else {
				echo "<table width='60%' border='1' cellspacing='0' cellpadding='1'
					bordercolorlight='black' bordercolordark='white' background='Images/fon.png' >";
				echo "<caption><b>Фатальная ошибка</b></caption>";
				echo "<tr><td align='left'>";
				echo self::$f_error;
				echo "</td></tr></table><br />";
			}
			echo "<b>Сейчас вы будете перенаправлены. Или кликните на этом сообщении, если не хотите ждать.</b>";
			echo "</td></tr></table>";

			//--------------------
			require("footer.php");
			
	}
	
	static function pass_generator() {
			$lowercase = "zyxwvutsrqponmlkjihgfedcba"; //символы в нижнем регистре 26
			$uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; //символы в верхнем регистре 26
			$speccase = "!-_+.,"; //специальные символы 6
			$digitcase = "0123456789"; //цифры 10
			$PassCase = $lowercase . $uppercase . $speccase . $digitcase; //68
			$pass = "";
			mt_srand(time()+(double)microtime()*1000000);
			for($i=0;$i<=6;$i++) {
					$pass .= $PassCase[mt_rand(0,67)];
			};
			return ($pass);
	}
}
?>
