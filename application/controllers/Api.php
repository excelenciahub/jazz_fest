<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

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
		$this->load->model("ArtistsCategoryModel");
		$this->load->model("ArtistsModel");
		$this->load->model("BannerTypeModel");
		$this->load->model("BannerModel");
		$this->load->model("FestivalDayModel");
		$this->load->model("FestivalModel");
		$this->load->model("PhotoFrameModel");
		$this->load->model("PreEventsModel");
		$this->load->model("PartnersModel");
		$this->load->model("FoodsModel");
		$this->load->model("ActivityModel");
	}

	public function index()
	{
		$response = array();
		
		echo json_encode($response);
	}

	public function all(){
		$response = array();
		
		/*
		$banners = json_decode($this->BannerModel->select(1),true)['records'];
		foreach($banners as $key=>$val){
			if($val['id']==1){
				$val['image'] = $this->config->item('banners_url').get_thumb($val['image'],'800x400');
				$response['big_banner'] = $val;
			}
			else if($val['id']==2){
				$val['image'] = $this->config->item('banners_url').get_thumb($val['image'],'800x210');
				$response['small_banner'] = $val;
			}
		}
		*/
		
		$banner_type = json_decode($this->BannerTypeModel->select(1),true)['records'];
		foreach($banner_type as $k=>$v){
			$where = "`bm`.`type`='".$v['id']."'";
			$banners = json_decode($this->BannerModel->select(1,$where),true)['records'];
			foreach($banners as $key=>$val){
				if($v['id']==1){
					$val['image'] = $this->config->item('banners_url').get_thumb($val['image'],'800x400');
				}
				else if($v['id']==2){
					$val['image'] = $this->config->item('banners_url').get_thumb($val['image'],'800x210');
				}
				$response['banner'][$v['name']][] = $val;
			}
		}

		$orderby = array(array('column'=>'id','order'=>'desc'));
		$festivals = json_decode($this->FestivalModel->select(1,"",$orderby,0,1),true)['records'];
		foreach($festivals as $key=>$val){
			$festivals[$key]['image'] = $this->config->item('festivals_url').get_thumb($val['image'],'800x500');
			$festivals[$key]['description'] = htmlspecialchars_decode(stripslashes($val['description']));
		}
		$response['festivals'] = isset($festivals[0])?$festivals[0]:array();
		
		$artists = json_decode($this->ArtistsModel->select(1),true)['records'];
		$arr = array();
		foreach($artists as $key=>$val){
			$val['image'] = $this->config->item('artists_url').get_thumb($val['image'],'800x500');
			$val['description'] = htmlspecialchars_decode(stripslashes($val['description']));
			$arr[$val['category_id']]['name'] = $val['category_name'];
			$arr[$val['category_id']]['records'][] = $val;
		}
		$response['artists'] = array_values($arr);

		$preevents = json_decode($this->PreEventsModel->select(1),true)['records'];
		foreach($preevents as $key=>$val){
			$preevents[$key]['image'] = $this->config->item('preevents_url').get_thumb($val['image'],'800x500');
			$preevents[$key]['description'] = htmlspecialchars_decode(stripslashes($val['description']));
		}
		$response['preevents'] = $preevents;

		$festivaldays = json_decode($this->FestivalDayModel->select(1),true)['records'];
		foreach($festivaldays as $key=>$val){
			$festivaldays[$key]['image'] = $this->config->item('festivalday_url').get_thumb($val['image'],'800x500');
			$festivaldays[$key]['description'] = htmlspecialchars_decode(stripslashes($val['description']));
		}
		$response['festivaldays'] = $festivaldays;

		$photoframe = json_decode($this->PhotoFrameModel->select(1),true)['records'];
		
		foreach($photoframe as $key=>$val){
			$photoframe[$key]['image'] = $this->config->item('photoframes_url').$val['image'];
		}
		
		$response['photoframes'] = $photoframe;

		$partners = json_decode($this->PartnersModel->select(1),true)['records'];
		foreach($partners as $key=>$val){
			$partners[$key]['image'] = $this->config->item('partners_url').$val['image'];
		}
		$response['partners'] = $partners;

		$foods = json_decode($this->FoodsModel->select(1),true)['records'];
		foreach($foods as $key=>$val){
			$foods[$key]['image'] = $this->config->item('foods_url').$val['image'];
		}
		$response['foods'] = $foods;

		$activity = json_decode($this->ActivityModel->select(1),true)['records'];
		foreach($activity as $key=>$val){
			$activity[$key]['image'] = $this->config->item('activity_url').$val['image'];
			$activity[$key]['description'] = htmlspecialchars_decode(stripslashes($val['description']));
		}
		$response['activity'] = $activity;

		$data['status'] = 1;
		$data['data'] = $response;
		echo json_encode($data,JSON_HEX_TAG);
	}
}
