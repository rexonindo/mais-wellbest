<?php
var_dump([
    'intl' => extension_loaded('intl'),
    'mbstring' => extension_loaded('mbstring'),
    'ini' => php_ini_loaded_file(),
    'php' => PHP_VERSION,
]);