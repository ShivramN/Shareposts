<?php

/**
 * 
 */
class Pages extends Controller{
	public function __construct(){

	}

	public function about(){
		$data = [
			'title' => 'About us',
			'description'=> 'App to share posts with other users'

		];
		$this->view('pages/index',$data);
	}


	public function index(){


	if(isLoggedin()){
		redirect('/posts');
	}

		$data = [
			'title' => 'SharePosts',
		    'description' => 'Simple Social Network using Siva MVC Framework'

		];

		$this->view('pages/index',$data);
	}
}

?>