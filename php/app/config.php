<?php
    define('ROOT', realpath(dirname(__FILE__) . '/../'));
    define('TEMPLATE_PATH', ROOT . '/app/views/');
    
    set_include_path(get_include_path() . PATH_SEPARATOR . ROOT);
    
?>