<?php
require ("config.php");

// Load TWIG
require_once '/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('tpl');
$twig = new Twig_Environment($loader, array(
    'cache' => false,//'tpl/cache',
));

$S_CONFIG['twig'] = $twig;
unset($twig);

// MySQL Connect
$link = mysqli_connect("127.0.0.1", $S_CONFIG['user'], $S_CONFIG['pass'], $S_CONFIG['db']);

if(!$link) exit("Соединение с mySQL-сервером недоступно: ".mysqli_errno($link)." - ".mysqli_error($link));

$S_CONFIG['link'] = $link;
unset($link);


mysqli_query($S_CONFIG['link'], 'SET character_set_results="UTF8"');
mysqli_query($S_CONFIG['link'], "SET NAMES UTF8"); 


function isJson($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}

?>
