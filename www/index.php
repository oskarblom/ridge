<?php
include dirname(__FILE__) .  '/../php/app/config.php';

include 'vendor/Slim/Slim.php';
include 'vendor/Mustache/Mustache.php';


$app = new Slim();
$supported = array('ad', 'job', 'company', 'event', 'venue');

/* Middleware Function for API-auth */
function authenticate(){
    return true;
}

function render($view, $data = array(), $layout = 'layout') {
    $content = "";
    try {
        $view_template = file_get_contents(TEMPLATE_PATH . $view . '.html');
        
        $layout_template = file_get_contents(TEMPLATE_PATH . $layout . '.html');

        $full_template = str_replace('{{{content}}}', $view_template, $layout_template); 

        $mustache = new Mustache;

        $content = $mustache->render($full_template, $data);
    } catch (Exception $e) {
        
    }
    return $content; 
}

$app->get('/', function(){
    echo(render('index'));
});

$app->map('/api/:model(/:id)', 'authenticate', function($model, $id = 0) use ($app) {
    if($id > 0) {
        if($app->request()->isGet()) { //get a single item of :model
            echo("get $id item of $model");
        } else if ($app->request()->isPut()){ //update a single item of :model
            echo("update $id item of $model");
        } else if ($app->request()->isDelete()) { //delete a single item of :model
            echo("delete $id item of $model");
        }
    } else {
        if($app->request()->isGet()) {
            //List all :model
            echo("List all " . $model);
        } else if ($app->request()->isPost()) {
            //Create :model, expect data
            echo("Create " . $model);
        }
        
    }
})->via('GET', 'POST', 'PUT', 'DELETE')->conditions(array('model' => '(' . implode($supported, '|') . ')'));


$app->post('/ad', function(){
    die("create ad");
});



$app->run();
?>