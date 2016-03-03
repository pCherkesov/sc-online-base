<?php
$_REQUEST['act'] = 'complete';
include("view.php");

$template = $S_CONFIG['twig']->loadTemplate('many.html');
$work_obj = new many_work();
echo $template->render(array('data' => $work_obj->render() ));
exit();
?>
