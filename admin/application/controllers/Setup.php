<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
				check_loggin();

		$this->load->model('model_common');
		$this->load->model('model_device');
		$this->load->model('model_users');
		$this->load->model('model_setup');
		$this->load->helper('url');
		$this->load->helper('slug');
		$this->load->library('phpexcel/PHPExcel/IOFactory') ;
  }
	
 
 // Start Setting
  function setting() {
	  if ($this->input->post('submit-setting')) {
	      if (!empty($_FILES['logo']['name'])) {
	        $config['upload_path'] = './uploads/logo/';
	        $config['allowed_types'] = 'gif|jpg|png';
	        $config['max_size'] = 2000;
	        $config['file_name'] = $this->session->userdata('username').'_'.date('YmdHis');

	        // Load library upload
	        $this->load->library('upload', $config);
        
	        // Jika terdapat error pada proses upload maka exit
	        if (!$this->upload->do_upload('logo')) {
	            exit($this->upload->display_errors());
	        }

	        $data['img_logo'] = $this->upload->data()['file_name'];
	      }
		  
	      if (!empty($_FILES['bg-login']['name'])) {
	        $config['upload_path'] = './uploads/logo/';
	        $config['allowed_types'] = 'gif|jpg|png';
	        $config['max_size'] = 2000;
	        $config['file_name'] = $this->session->userdata('username').'_'.date('YmdHis');

	        // Load library upload
	        $this->load->library('upload', $config);
        
	        // Jika terdapat error pada proses upload maka exit
	        if (!$this->upload->do_upload('bg-login')) {
	            exit($this->upload->display_errors());
	        }

	        $data['img_login'] = $this->upload->data()['file_name'];
	      }
          $data['style'] = $this->input->post('style');
          $data['title'] = $this->input->post('title');
		  $data['language'] = $this->input->post('site_lang');
		  $query = $this->model_setup->updateConf(1,$data);
          if ($query) {
            $message = array('status' => true, 'message' => $this->lang->line('success'));
			$this->session->set_userdata($data);

          } else {
            $message = array('status' => false, 'message' => $this->lang->line('error'));
          }
		  // switch lang
		  $language = $this->input->post('site_lang');
	      $language = ($language != "") ? $language : "english";
	      $this->session->set_userdata('site_lang', $language);
		  
		  $this->session->set_flashdata('message_setting', $message);
          redirect('setup/setting', 'refresh');
		  
	  }
	  $data['pageTitle'] = 'Setting';
	  $data['conf'] = $this->model_setup->getConfID(1)->row();
	  $data['pageContent'] = $this->load->view('setup/setting', $data, TRUE);		
	  $this->load->view('template/layout', $data);	
  }
  // End Setting
  
     
   
}
