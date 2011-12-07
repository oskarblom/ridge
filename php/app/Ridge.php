<?php
	include 'vendor/Slim/Slim.php';
	include 'vendor/Mustache/Mustache.php';
	
	class Ridge {
		private $models;
		public $app;
		private $template_path;
		private $mongo;
		private $db;
		
		
	 	function __construct($params = array()){
			if(array_key_exists('models', $params)) {
				$this->models = $params['models'];
			}
			$this->template_path = $params['template_path'];
			$this->app = new Slim();
			$this->mongo = new Mongo();
			$this->db = $this->mongo->ridge;
		}
		
		public function authenticate($user, $key, $referrer = ''){
		    return true;
		}

		public function render($view, $data = array(), $layout = 'layout', $echo = true) {
			//TODO: Id if XMLHttpRequest and in that case return json
		    $content = "";
		    try {
		        $view_template = file_get_contents($this->template_path . $view . '.html');
		        $layout_template = file_get_contents($this->template_path . $layout . '.html');
		        $full_template = str_replace('{{{content}}}', $view_template, $layout_template); 
		        $mustache = new Mustache;
		        $content = $mustache->render($full_template, $data);
		    } catch (Exception $e) {

		    }
		
			if($echo) {
				echo($content);
			} else {
				return $content; 
			}
		    
		}

		function get_templates(){
			$templates = array();
			
			if ($handle = opendir($this->template_path)) {
			  
			  /* This is the correct way to loop over the directory. */
			    while (false !== ($file = readdir($handle))) {
			        if(preg_match("/^(\w+)\.html/", $file, $matches)) {
						$templates[$matches[1]] = file_get_contents($this->template_path . $file);
					}
			    } 				
				closedir($handle);
			}
			
			$content = "window.templates = " . json_encode($templates) . ";";
			
			return $content;
		}
		
		public function list_items($model, $params = array()){
			$collection = $this->db->$model;
			$items = $collection->find();
			return $items;
		}
		
		public function get_item($model, $id, $params = array()){
			$collection = $this->db->$model;
			return $collection->findOne(array('id' => $id));
		}
		
		public function add_item($model, $data, $params = array()){
			$collection = $this->db->$model;
			$collection->insert($data);
		}
		
		public function update_item($model, $id, $data, $params = array()){
			$collection = $this->db->$model;
			$collection->save($data);
		}
		
		public function delete_item($model, $id, $params = array()){
			$collection = $this->db->$model;
			$collection->remove(array('id' => $id));
		}
		
		public function get_models(){
			return $this->models;
		}
		
		public function set_models($models){
			$this->models = $models;
		}
		
		public function add_model($model){
			$this->models[] = $model;
		}
	}
?>