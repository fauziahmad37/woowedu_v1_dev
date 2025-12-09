<?php
 
class Chat extends MY_Controller {
	
	function __construct(){
		parent::__construct();
 		$this->load->library('session');
		$this->load->model('model_chat');
		$this->load->model('model_kelas');
	}

	function index(){ 
		$data['page_css'] = ['assets/css/chat.css'];

		$user_level 				= $this->session->userdata('user_level');
		if($user_level == 3 ){ 
			$data['friend_list'] =   $this->model_chat->get_teacher_class(); 
		}elseif($user_level == 4 ){	
		
			$data['friend_list'] =  $this->model_chat->get_teacher_list(); 
		}
 
		$this->template->load('template', 'chat/index', $data);
	}
	
	function chatbox()
	{
		$user_level 				= $this->session->userdata('user_level'); 
		$data['class_id'] = $this->session->userdata('class_id');
		$data['username'] = $this->session->userdata('username');
		if($user_level == 3 ){  
			$histrory_params['limit'] = 100;
			$histrory_params['offset'] = 0;			
			$histrory_params['uc_from'] = $this->session->userdata('class_id');	
			$data['chat_history'] =  $this->model_chat->get_history($histrory_params);
		}elseif($user_level == 4 ){	 
			$histrory_params['limit'] = 100;
			$histrory_params['offset'] = 0;
			$histrory_params['uc_from'] = $this->session->userdata('class_id');
			$data['chat_history'] =  $this->model_chat->get_history($histrory_params);
		}		
		$this->load->view('chat/chatbox',$data);		
	}
	
	function savechat()
	{
		$data = [  
		'uc_from' => $_POST['_from'], 
		'uc_to_id' => $_POST['_to'] ,
		'uc_message' => $_POST['_msg'],
		'uc_date' => $_POST['_date'] 
		];

		$this->db->insert('users_chat', $data);			
	}
 

}
