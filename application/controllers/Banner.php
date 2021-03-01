<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends MY_Controller {

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
		$this->load->model("BannerTypeModel");
		$this->load->model("BannerModel");
		$this->load->model("ResizeImage");
        $this->load->model("StopWords");
	}

	public function index()
	{
		$filter = array();
		$types = json_decode($this->BannerModel->getType(),true)['records'];
		$filter[2] = array('label'=>'Type','data'=>$types,'col'=>'3');
		$filter[3] = array('label'=>'Status','data'=>array('1'=>'Enabled','0'=>'Disabled'));

		$data['header_icon'] = 'fa fa-building text-purple';
		$data['header_title'] = 'Banner';
		$data['action'] = 'view';
		$data['filters'] = $filter;
		$this->load->template('Banner',$data);
	}
	public function add(){
		$data['header_icon'] = 'fa fa-building text-purple';
		$data['header_title'] = 'Banner';
		$data['action'] = 'add';
		$data['slug'] = '';
		$data['name'] = '';
		$data['image'] = '';
		$data['type'] = '';
		$data['controller'] = $this;

		$data['types'] = json_decode($this->BannerTypeModel->select())->records;

		$this->load->template('Banner',$data);
	}
	public function save(){
		$this->form_validation->set_rules('type', 'Type', 'required');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$slug = $this->input->post('slug');
		if($slug=='' && empty($_FILES['image']['name'])){
			$this->form_validation->set_rules('image', 'Image', 'required');
		}
		
		if($this->form_validation->run() == false){
			$this->session->set_flashdata('error', validation_errors());
		}
		else{
			if($_FILES['image']['name']!=''){
				$image_name = time().'_'.str_replace(' ','_',$_FILES['image']['name']);
				
				$config['upload_path']   = $this->config->item('banners_dir');
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['file_name'] = $image_name;

				$this->load->library('upload', $config);
				if(!$this->upload->do_upload('image', false)){
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error',$error);
					if($slug==''){
						redirect($this->agent->referrer());
					}
					else{
						redirect(base_url().'Banner/edit/'.$this->input->post('slug'));
					}
				}
				else{
					$upload_data = $this->upload->data();
					$image_name = $upload_data['file_name'];
					$_POST['image'] = $image_name;
					
					$resizeObj = new ResizeImage($this->config->item('banners_dir').$image_name);
					$resizeObj -> resizeImage(135, 100, 'crop');
					$resizeObj -> saveImage($this->config->item('banners_dir').get_filename($image_name,false).'-135x100.'.get_extension($image_name),$this->config->item('banners_dir').$image_name, 100);
					$resizeObj -> resizeImage(360, 258, 'crop');
					$resizeObj -> saveImage($this->config->item('banners_dir').get_filename($image_name,false).'-360x258.'.get_extension($image_name),$this->config->item('banners_dir').$image_name, 100);
					$resizeObj -> resizeImage(800, 500, 'exact');
					$resizeObj -> saveImage($this->config->item('banners_dir').get_filename($image_name,false).'-800x500.'.get_extension($image_name),$this->config->item('banners_dir').$image_name, 100);
					$resizeObj -> resizeImage(800, 400, 'exact');
					$resizeObj -> saveImage($this->config->item('banners_dir').get_filename($image_name,false).'-800x400.'.get_extension($image_name),$this->config->item('banners_dir').$image_name, 100);
					$resizeObj -> resizeImage(800, 210, 'exact');
					$resizeObj -> saveImage($this->config->item('banners_dir').get_filename($image_name,false).'-800x210.'.get_extension($image_name),$this->config->item('banners_dir').$image_name, 100);
				}
			}
			$_POST['where'] = $slug;
			$_POST['slug'] = $this->slugify($this->input->post('name'),BANNER_MASTER," AND `slug`!='".$this->input->post('slug')."'");
			$data = $this->input->post();
			foreach($data as $key=>$val){
				$data[$key] = $this->db_input($val);
			}
			$response = json_decode($this->BannerModel->save($data),true);
			if($response['status']==1){
				$this->session->set_flashdata('success',$response['message']);
			}
			else{
				$this->session->set_flashdata('error',$response['message']);
			}
		}
		if($slug==''){
			redirect($this->agent->referrer());
		}
		else{
			redirect(base_url().'Banner/edit/'.$this->input->post('slug'));
		}
	}
	public function DeleteImage($slug,$image){
		$response = json_decode($this->BannerModel->DeleteImage($slug,$image),true);
		if($response['status']==1){
			$this->session->set_flashdata('success',$response['message']);
		}
		else{
			$this->session->set_flashdata('error',$response['message']);
		}
		redirect($this->agent->referrer());
	}
	public function status(){
		$slug = $this->input->post('slug');
        $status = $this->input->post('status');
        $response = $this->BannerModel->status($slug,$status);
        echo $response;exit;
	}
	public function delete(){
		$slug = $this->input->post('slug');
        $response = $this->BannerModel->delete($slug);
        echo $response;exit;
	}
	public function edit($slug){
		$data = json_decode($this->BannerModel->edit($slug),true);
		$data['header_icon'] = 'fa fa-building text-purple';
		$data['header_title'] = 'Banner';
		$data['action'] = 'edit';
		$data['controller'] = $this;
		$data['types'] = json_decode($this->BannerTypeModel->select())->records;
		$this->load->template('Banner',$data);
	}
	public function select(){
		$column = array(
				0=>array('column'=>'id','prefix'=>'bm'),
				1=>array('column'=>'name','prefix'=>'bm'),
				2=>array('column'=>'type','prefix'=>'bm'),
				3=>array('column'=>'status','prefix'=>'bm')
			);
		//printr($_REQUEST);
		$status = '';
		$where = '';
		$orderby = array();
		$start = isset($_REQUEST['start'])?$this->db_input($_REQUEST['start']):0;
		$length = isset($_REQUEST['length'])?$this->db_input($_REQUEST['length']):RECORD_PER_PAGE;
		$where = $this->filter($_REQUEST,$column);
		$orderby = $this->order($_REQUEST,$column, true);
		$total_records = $this->BannerModel->count($status);
		$records = json_decode($this->BannerModel->select($status,$where,$orderby,$start,$length));
		$filtered_records = $this->BannerModel->count($status,$where);
		$record = array();
		foreach($records->records as $key=>$val){
			$array = array();
			$array[] = isset($_REQUEST['order'][0]['column'])&&$_REQUEST['order'][0]['column']==0&&$_REQUEST['order'][0]['dir']=='desc'?strval($filtered_records-$start-$key):strval($start+$key+1);
			$array[] = $this->db_output($val->name);
			$array[] = $this->db_output($val->type_name);
			if($val->status==1){
				$array[] = '<button type="button" slug="'.$this->db_output($val->slug).'" value="0" class="btn btn-xs btn-default text-olive btn-status"><i class="fa fa-check"></i> Enabled</button>';
			}
			else{
				$array[] = '<button type="button" slug="'.$this->db_output($val->slug).'" value="1" class="btn btn-xs btn-default text-yellow btn-status"><i class="fa fa-warning"></i> Disabled</button>';
			}
			
			$array[] = '<a href="'.base_url().'Banner/edit/'.$this->db_output($val->slug).'" id="'.$this->db_output($val->slug).'" class="btn btn-xs btn-default text-blue"><i class="fa fa-edit"></i> Edit</a><button type="button" slug="'.$this->db_output($val->slug).'" class="btn btn-xs btn-default text-red btn-delete"><i class="fa fa-trash"></i> Delete</button>';
			array_push($record,$array);
		}
		$data = array(
				"draw"            => isset ( $_REQUEST['draw'] ) ? $this->db_input($_REQUEST['draw'],true) : 0,
				"recordsTotal"    => intval( $total_records ),
				"recordsFiltered" => intval( $filtered_records ),
				"data"            => $record,
			);
		echo json_encode($data);exit;
		
	}
}
