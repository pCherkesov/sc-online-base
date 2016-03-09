<?php
require("init.php");

if(!isset($_REQUEST['r'])) $_REQUEST['r'] = "many/incomplete";

$route = explode("/", $_REQUEST['r']);

if(isset($_REQUEST['id'])) $main['title'] = "Просмотр записи #".$_REQUEST['id'];
else $main['title'] = "Просмотр списка записей";

if(!isset($route[1])) include("actions/". $route[0] ."/view.php");
else include("actions/". $route[0] ."/". $route[1] .".php");
		
function render($data = array(), $template = FALSE) {
	global $S_CONFIG;
	if($template == FALSE) {
		global $route;
		$template = $route[0];
	}
	$tpl = $S_CONFIG['twig']->loadTemplate($template .'.html');
	echo $tpl->render($data);
}

?>
