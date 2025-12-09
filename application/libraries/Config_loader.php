<?php defined('BASEPATH') OR exit('No direct script access allowed.');

class Config_loader extends CI_Controller
{
    protected $CI;

    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance(); //read manual: create libraries
        $this->CI->load->model('Model_settings');
		$global_data['settings'] = $this->CI->Model_settings->get_settings();
        
		$this->settings = $global_data['settings'];
        $this->CI->load->vars($global_data);
    }
}