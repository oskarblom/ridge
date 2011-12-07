<?php
include dirname(__FILE__) .  '/../php/app/config.php';
include 'app/Ridge.php';

$ridge = new Ridge(
	array(
		'models' => array('ad', 'job', 'event', 'venue', 'company'),
		'template_path' => TEMPLATE_PATH
	)
);

$ridge->app->get('/', function() use ($ridge) {
	$ridge->render('index');
});

$ridge->app->get('/category', function() use ($ridge) {
	$ridge->app->response()->header('Content-Type', 'application/json');
	$result = array();
	$models = $ridge->get_models();
	foreach($models as $model){
		$result[] = array('uri' => $model, 'name' => ucfirst($model));
	}
	echo json_encode(
		$response = array(
			'status' => 200,
			'data' => $result
		)
	);
});

$ridge->app->map('/:model(/:id)(/)', $ridge->authenticate('api-key', 'api-pwd', 'referrer'), function($model, $id = 0) use ($ridge) {
	$app = $ridge->app;
	$app->response()->header('Content-Type', 'application/json');
	
	$response = array(
		'status' => 200,
		'data' => ''
	);
	
    if($id > 0) {
        if($app->request()->isGet()) { //get a single item of :model
            $ridge->get_item($model, $id);
        } else if ($app->request()->isPut()){ //update a single item of :model
            $ridge->update_item($model, $id, $data);
        } else if ($app->request()->isDelete()) { //delete a single item of :model
            $ridge->delete_item($model, $id);
        }
    } else {
        if($app->request()->isGet()) {
            $response['data'] = $ridge->list_items($model);
			
        } else if ($app->request()->isPost()) {
            $ridge->add_item($model, $data);
        }
    }
	echo(json_encode($response));
})->via('GET', 'POST', 'PUT', 'DELETE')->conditions(array('model' => '(' . implode($ridge->get_models(), '|') . ')'));

$ridge->app->get('/js/templates.js', function() use ($ridge){
	$ridge->app->response()->header('Content-Type', 'text/javascript');
	echo($ridge->get_templates());
});

$ridge->app->run();
?>