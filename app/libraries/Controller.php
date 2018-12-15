<?php
//Base Controller 
//loads models and views

class Controller{
	//load model

	public function model($model){
		//require model file
		require_once '../app/models/'.$model.'.php';

		//INstantiate the model
		return new $model();

	}

	//load views

	public function view($view, $data = []){
		//check for view file
		if(file_exists('../app/views/'.$view.'.php')){
			require_once '../app/views/'.$view.'.php';
		}else{
			//view does not exists
			die('View does not exist');
		}
	}
}

?>