<?php
include dirname(__FILE__) .  '/../php/app/config.php';

include 'vendor/Slim/Slim.php';
include 'vendor/Mustache/Mustache.php';


$app = new Slim();

function get_supported_models() {
	return array('ad', 'job', 'company', 'event', 'venue');
}

/* Middleware Function for API-auth */
function authenticate($user, $key, $referrer = ''){
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

function get_templates(){
	return "";
}

$app->get('/', function() use ($app) {
	echo(render('index'));
});

$app->get('/category', function() use ($app) {
	$app->response()->header('Content-Type', 'application/json');
	$result = array();
	$models = get_supported_models();
	foreach($models as $model){
		$result[] = array('uri' => $model, 'name' => ucfirst($model));
	}
	echo json_encode($result);
});

$app->map('/:model(/:id)(/)', authenticate('api-key', 'api-pwd', 'referrer'), function($model, $id = 0) use ($app) {
	$app->response()->header('Content-Type', 'application/json');
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
            if($model === 'ad'){
				$ads = array(
					array(
						'name' => 'Ad number 1',
						'id' => 1
					),
					array(
						'name' => 'Ad number 2',
						'id' => 2
					)
				);
				echo(json_encode($ads));
			}
        } else if ($app->request()->isPost()) {
            //Create :model, expect data
            echo("Create " . $model);
        }
        
    }
})->via('GET', 'POST', 'PUT', 'DELETE')->conditions(array('model' => '(' . implode(get_supported_models(), '|') . ')'));

$app->get('/js/templates.js', function(){
	echo('get templates');
});

$app->run();
?>