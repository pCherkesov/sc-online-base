<?php
require("init_j.php");

if (!isset($_GET['id_worker'])) $id_worker = 1;
else $id_worker = $_GET['id_worker'];

$query = "UPDATE `".$S_CONFIG['prefix']."remont` SET `id_worker`='".$id_worker."' WHERE `id_r`='".$_GET['id']."' LIMIT 1";
echo $query;
$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));

?>
