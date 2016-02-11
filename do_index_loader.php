<?php
// Load JsHttpRequest backend.
require_once "JsHttpRequest/JsHttpRequest.php";
// Create main library object. You MUST specify page encoding!
$JsHttpRequest = new JsHttpRequest("windows-1251");
//
require("init.php");

$index = "";

switch (@$_REQUEST['dir']) {
	case 'void': $index = ""; break;
	case 'name': include ("do_index/name_loader.php"); break;
	case 'calendar': include ("do_index/calendar_loader.php"); break;
	case 'device': include ("do_index/device_loader.php"); break;
	case 'serial': include ("do_index/serial_loader.php"); break;
	//--------
	case 'add': include ("do_index/add_loader.php"); break;
	case 'type': include ("do_index/add_loader.php"); break;
	case 'brand': include ("do_index/add_loader.php"); break;
	case 'model': include ("do_index/add_loader.php"); break;
	case 'add_client': include ("do_index/add_loader.php"); break;
	case 'add_client_save': include ("do_index/add_loader.php"); break;
	case 'edit_client_save': include ("do_index/add_loader.php"); break;
	//--------
	default: $index = ""; break;
}

// Store resulting data in $_RESULT array (will appear in req.responseJs).
$_RESULT = array(
  "second"   => @$_REQUEST['dir'],
  "index" => @$index
);
// Below is unparsed stream data (will appear in req.responseText).

echo "<pre>";
echo "<b>Request method:</b> " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "<b>Loader used:</b> " . $JsHttpRequest->LOADER . "\n";
echo "<b>_REQUEST:</b> " . print_r($_REQUEST, 1);
echo "</pre>";
?>
