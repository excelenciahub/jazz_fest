<?php if(!defined('BASEPATH')){ require_once("index.html");exit; }
	if(isset($error) && $error!=''){
		error_msg($error);
	}
	else if($this->session->userdata('success')){
		success_msg($this->session->userdata('success'));
		$this->session->unset_userdata('success');
	}
	else if($this->session->userdata('error')){
		error_msg($this->session->userdata('error'));
		$this->session->unset_userdata('error');
	}
	else if($this->session->userdata('warning')){
		warning_msg($this->session->userdata('warning'));
		$this->session->unset_userdata('warning');
	}
	else if($this->session->userdata('info')){
		info_msg($this->session->userdata('info'));
		$this->session->unset_userdata('info');
	}
?>