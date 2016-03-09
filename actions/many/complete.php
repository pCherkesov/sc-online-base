<?php
include("view.php");

$work_obj = new many_work();

render($data = array('data' => $work_obj->render()));
?>
