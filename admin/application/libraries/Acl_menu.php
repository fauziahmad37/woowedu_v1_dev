<?php

class Acl_menu {
    
    private $ci;
    public function __construct() {
        $this->ci = &get_instance();
        $this->ci->load->model('model_users');
        //$this->ci->load->library('uri');
        $lang = ($this->ci->session->userdata('lang')) ? $this->ci->session->userdata('lang') : config_item('language');
		$this->ci->lang->load('message', $lang);
    }

    public function menu($sess) {
        $data = $this->ci->model_users->getMenuParents($sess);
				
        return $this->setMenu($data);
    }

    private function setMenu($data, $parent = 0, $parents = []) {

        if($parent == 0) {
            foreach($data as $d) {
                if($d['menu_parent'] != 0 && !in_array($d['menu_parent'], $parents))
                    $parents[] = $d['menu_parent'];
            }
        }

        $output = '';
        foreach($data as $d) {
            $segment = explode('/', $d['menu_link']);
            if($d['menu_parent'] == $parent) { 
								if(empty($d['menu_link'])){
									$linknya = "#";
									$has_arrow = 'has-arrow ';
                }else{
									$linknya = base_url($d['menu_link']);
									$has_arrow = '';
								}
                
								$output .= '<li class="'.($this->ci->uri->segment(count($segment)) == $segment[count($segment)-1] ? 'active' : '').'">'; 
                $output .= '<a href="'.$linknya.'" class="'.$has_arrow.' waves-effect '.($this->ci->uri->segment(count($segment)) == $segment[count($segment)-1] ? 'active' : '' ).'">';
                $output .= '<i class="'.$d['menu_icon'].'"></i>';
                $output .= '<span>'.$d['menu_name'].'</span>';
                $output .= '</a>'; 
                if(in_array($d['menu_id'], $parents)) {
                    $output .= '<ul class="sub-menu" aria-expanded="false">';
                    $output .= $this->setMenu($data, $d['menu_id'], $parents);
                    $output .= '</ul>';
                }
                $output .= '</li>';
            }
            
        }   

        return $output;
    }

    /*
        <li class="<?= $this->uri->segment(2) == 'person' &&  $this->uri->segment(3) != "" ? 'mm-active' : '' ?>">
            <a href="<?= base_url() . 'personel/person/' ?>" class="waves-effect <?= $this->uri->segment(2) == 'person' &&  $this->uri->segment(3) != "" ? 'active' : '' ?>">
                <i class="bx bx-user-circle"></i>
                <span><?= $this->lang->line('woow_menu_person_data'); ?></span>
            </a>
		</li>

    */
}