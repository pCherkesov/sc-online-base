<?PHP
if(isset($_REQUEST['id_r'])) $page_header = "Просмотр записи #".$_REQUEST['id_r'];
else $page_header = "Просмотр списка записей";

//require("header.php");
require("init.php");
 
$a = new work_vision;

/*
$_REQUEST['id_r']
$_REQUEST['act']

$_POST['search']
$_POST['data']
$_POST['client']
$_POST['model']

$_POST['serial']

$_REQUEST['id_worker']

$_GET['completed']
$_GET['uncompleted']
$_GET['tab_del']

$_POST['add']
$_GET['del']
$_POST['edited']
$_POST['save_worker']
*/

class work_vision {

static private $instance = NULL;
public $work_obj;

	private function __clone () {
	}
	
	function __construct () {
            global $twig;
            
            if (isset($_REQUEST['act']) and $_REQUEST['act']=='total'){
			include ('total_month.php');
			$this->work_obj = new total_month ();
		} else
		if (!isset($_REQUEST['id_r'])){
                    $template = $twig->loadTemplate('many.html');

                    include('class_many_work.php');
                    $work_obj = new many_work();
                    //print_r($work_obj->render());

                    echo $template->render(array('data' => $work_obj->render() ));
		} 
		else {
                    $template = $twig->loadTemplate('single.html');

                    include('class_single_work.php');
                    $work_obj = new single_work();

                    echo $template->render(array('header' => $work_obj->render() ));
		}
    
    //echo "<h1>Данная версия БД ОнЛайн является shareware :)</h1>"; 
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
	
}
//--------------------
//require("footer.php");
?>
