<?php 

$envs = parse_ini_file(ROOT_PATH . '/.env');

foreach($envs as $key => $value) {
    $_ENV[$key] = $value;
}