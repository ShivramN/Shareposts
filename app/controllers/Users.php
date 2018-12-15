<?php





class Users extends Controller{
	public function __construct(){

		$this->userModel = $this->model('User');
	}

	public function register(){

		//sanitize post data

		$_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);


		//Check for post

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			//Process 	Form
			$data = [
				'name' => trim($_POST['name']),
				'email' =>  trim($_POST['email']),
				'password' =>  trim($_POST['password']),
				'confirm_password' => trim($_POST['confirm_password']),
				'name_err' => '',
				'email_err' => '',
				'password_err' => '',
				'confirm_password_err' => ''

			];

			//validate
			if(empty($data['name'])){
				$data['name_err'] = 'PLease enter Name';
			}

			if(empty($data['email'])){
				$data['email_err'] = 'PLease enter Email';
			}else{
				if($this->userModel->findUserByEmail($data['email'])){
					$data['email_err'] = 'Email already registered';
				}
			}

			if(empty($data['password'])){
				$data['password_err'] = 'PLease enter Password';
			}elseif(strlen($data['password']) < 6){
				$data['password_err'] = 'Password must contain minimum 6 characters';
			}

			if(empty($data['confirm_password'])){
				$data['confirm_password_err'] = 'PLease confirm Password';
			}else{

				if($data['password'] != $data['confirm_password']){
					$data['confirm_password_err'] = 'Password do not match';
				}
			}

			if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) ){
				

				$data['password'] = password_hash($data['password'],PASSWORD_DEFAULT); 

				//Register User

				if($this->userModel->register($data)){
					flash('register_success','You are registered and can log in');
					redirect('/users/login');
				}else{
					die('Something went wrong');
				}
			}else{
				$this->view('users/register',$data);
			}
		}else{
			$data = [
				'name' => '',
				'email' => '',
				'password' => '',
				'confirm_password' => '',
				'name_err' => '',
				'email_err' => '',
				'password_err' => '',
				'confirm_password_err' => ''

			];

			//Load view
			$this->view('users/register',$data);
		}
	}

	public function login(){
		//Check for post

		$_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			//Process 	Form
			$data = [
				
				'email' =>  trim($_POST['email']),
				'password' =>  trim($_POST['password']),
							
				'email_err' => '',
				'password_err' => '',

			];

			if(empty($data['email'])){
				$data['email_err'] = 'Please enter email';
			}

			if(empty($data['password'])){
				$data['password_err'] = 'Please enter Password';
			}

			//check for user email

			if($this->userModel->findUserByEmail($data['email'])){
				//User found
				//check and set logged in user
				$loggedInUser = $this->userModel->login($data['email'],$data['password']);

				if($loggedInUser){
					//Create Session
					$this->createUserSession($loggedInUser);
				}else{
					$data['password_err'] = 'Password incorrect';
					$this->view('users/login',$data);
				}
			}else{

				$data['email_err'] = 'No User Found';
			}

			if(empty($data['email_err']) && empty($data['password_err']) ){


			}else{
				$this->view('users/login',$data);
			}



		}else{
			$data = [
				
				'email' => '',
				'password' => '',
				
				'email_err' => '',
				'password_err' => '',
				

			];

			
			//Load view
			$this->view('users/login',$data);
		}
	}
	public function createUserSession($user){
			$_SESSION['user_id'] = $user->id;
			$_SESSION['user_email'] = $user->email;
			$_SESSION['user_name'] = $user->name;

			redirect('/posts');
		}


		public function logout(){
			unset($_SESSION['user_id']);
			unset($_SESSION['user_name']);
			unset($_SESSION['user_email']);

			redirect('/users/login');
		}
}

?>