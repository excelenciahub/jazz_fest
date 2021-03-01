<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

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
		
	}

	public function password(){
		$data['header_icon'] = 'fa fa-cog text-purple';
		$data['header_title'] = 'Change Passowrd';
		$this->load->template('ChangePassword',$data);
	}
	public function ChangePassword(){
		$this->form_validation->set_rules('current_password', 'Current Password', 'required');
		$this->form_validation->set_rules('new_password', 'New Password', 'required');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

		if($this->form_validation->run() == false){
			$this->session->set_flashdata('error', validation_errors());
		}
		else{
			$_POST['admin_id'] = $this->session->userdata('admin_id');
			
			$response = json_decode($this->AdminModel->ChangePassword($this->input->post()),true);
			if($response['status']==1){
				$this->session->set_flashdata('success',$response['message']);
			}
			else{
				$this->session->set_flashdata('error',$response['message']);
			}
		}
		redirect($this->agent->referrer());
	}
	public function SignOut(){
		$this->session->unset_userdata('admin_id');
		redirect(base_url());
	}

}
