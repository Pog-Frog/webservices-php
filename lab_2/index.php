<?php

require_once "vendor/autoload.php";
require_once "config.php";

use App\Controllers\RequestController;
use App\Models\MySQLHandler;

$handler = new MySQLHandler("products");
$request_cont = new RequestController($handler);

$request_cont->handle_request();

?>