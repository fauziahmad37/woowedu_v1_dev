<?php defined('BASEPATH') or exit('No direct script access allowed');

class Language extends CI_Controller
{
    public function english()
    {
        $this->session->unset_userdata('lang');
        $this->session->set_userdata('lang', 'english');
        // redirect('/', 'refresh');
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function indonesia()
    {
        $this->session->unset_userdata('lang');
        $this->session->set_userdata('lang', 'indonesia');
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function germany()
    {
        $this->session->unset_userdata('lang');
        $this->session->set_userdata('lang', 'germany');
        redirect($_SERVER['HTTP_REFERER']);
    }
}
