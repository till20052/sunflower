<?php

error_reporting(E_ALL & ~E_STRICT);

define('AppWorkTime', microtime());

include './define.php';
include CORE.DS.'Loader.php';

Loader::loadSystem('Core');

$core = new Core();
$core->initialize();
$core->execute();
