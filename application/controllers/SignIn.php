<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SignIn extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct(){
		parent::__construct();
		$this->load->model("AdminModel");
	}

	public function index()
	{
		$this->load->view('SignIn');
	}
	public function process(){
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == FALSE){
			$response['status'] = 0;
			$response['message'] = 'Username and password is required.';
		}
		else{
			$response = $this->AdminModel->SignIn($this->input->post());
		}
		echo json_encode($response);
	}
}
