<?php
require ("config.php");

require_once '/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('tpl');
$twig = new Twig_Environment($loader, array(
    'cache' => false,//'tpl/cache',
));

// Соединение, выбор БД
@$link = mysqli_connect("127.0.0.1", $S_CONFIG['user'], $S_CONFIG['pass'], $S_CONFIG['db']);

if(!$link) exit("Соединение с mySQL-сервером недоступно: " . mysql_errno() . " - " . mysql_error());
else {
	$S_CONFIG['link'] = $link;
	mysqli_query($S_CONFIG['link'], 'SET character_set_results="UTF8"');
	mysqli_query($S_CONFIG['link'], "SET NAMES UTF8"); 
}
?>