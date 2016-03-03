<?php
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

require("init.php");

if(!isset($_REQUEST['r'])) die("FATAL ERROR!!!");
$route = explode("/", $_REQUEST['r']);

if(isset($_REQUEST['id'])) $main['title'] = "Просмотр записи #".$_REQUEST['id'];
else $main['title'] = "Просмотр списка записей";

if(!isset($route[1])) include("actions/". $route[0] ."/view.php");
else include("actions/". $route[0] ."/". $route[1] .".php");
		
$template = $S_CONFIG['twig']->loadTemplate($route[0] .'.html');
echo $template->render(array('main' => $main, 'header' => $header, 'works' => $works));


exit();


    
if (isset($_REQUEST['act']) and $_REQUEST['act']=='total') {
	include ('total_month.php');
	$work_obj = new total_month ();
} 
else {
	if (!isset($_REQUEST['id_r'])) {
		$template = $S_CONFIG['twig']->loadTemplate('many.html');

		include('class_many_work.php');
		$work_obj = new many_work();

		echo $template->render(array('data' => $work_obj->render() ));
	} 
	else {
		$template = $S_CONFIG['twig']->loadTemplate('single.html');

		include('/actions/single/view.php');

		echo $template->render(array('main' => $main, 'header' => $header, 'works' => $works));
	}
}

?>
