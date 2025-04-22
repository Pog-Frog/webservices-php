<?php

$env = parse_ini_file('.env');

define('API_KEY', $env['API_KEY']);
define('DATA_FILE_URL', __DIR__ . '/egypt.cities.json');