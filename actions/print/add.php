<?php
$args = array(
    'id'     =>  FILTER_VALIDATE_INT,
);

$inputs = filter_input_array(INPUT_GET, $args);
if (!isset($inputs['id'])) die("Неверно передан id");

$query = "SELECT DATE_FORMAT(r.date, '%d.%m.%Y') AS date, r.id_client, c.client, c.client_tel_0, r.client_fio, r.client_tel,
t.type, b.brand, m.model, r.counter, r.serial, p.prin, r.defect, r.complect, r.pass as pincode 
    FROM `remont` AS r 
        LEFT JOIN `client` AS c ON r.id_client = c.id_client 
        LEFT JOIN `model` AS m ON r.id_model = m.id_model 
        LEFT JOIN `type` AS t ON m.id_type = t.id_type
        LEFT JOIN `brand` AS b ON m.id_brand = b.id_brand 
        LEFT JOIN `worker` AS w ON r.id_worker = w.id_worker    
        LEFT JOIN `prin` AS p ON r.id_prin = p.id_prin
    WHERE r.id_r=".$inputs['id']."
    LIMIT 1";

$result = mysqli_query($S_CONFIG['link'], $query) or exit(mysqli_error($S_CONFIG['link']));
while ($work = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $work['id'] = $inputs['id'];
    $work['time'] = date("H:i");
    if ($work['id_client'] == 1){
        $work['client'] = $work['client_fio'];
        $work['client_tel'] = $work['client_tel'];
    }
    else {
        $work['client_tel'] = $work['client_tel_0'];
    }
    $work['device'] = $work['type'] ." ". $work['brand'] ." ". $work['model'];
    $line = $work;
}

render(array('work' => $line), "/form/add");
?>
