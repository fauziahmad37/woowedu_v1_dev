<?php

class Materi extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		check_Loggin();
		$this->load->model(['model_common', 'model_materi']); 
		$this->load->helper('url');
		$this->load->helper('slug');
		$this->load->helper('assets');	
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
		$this->load->library('csrfsimple');
	}

	public function index() {
			$data['pageTitle']	= 'Data Materi';
	//$data['tableName']	= 'tbl_device';
	$data['csrf_token']	= $this->csrfsimple->genToken();
	$data['page_js']	= [  
		['path' => 'assets/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js', 'defer' => true],
		['path' => 'assets/new/libs/summernote/summernote-bs4.min.js', 'defer' => true],
		['path' => 'assets/new/libs/moment/min/moment.min.js', 'defer' => true],
		['path' => 'assets/new/libs/bootstrap4-datetimepicker/js/bootstrap-datetimepicker.min.js', 'defer' => true],
		['path' => 'assets/new/js/pages/_materi.js', 'defer' => true],
	]; 
	$data['page_css']	= [  
		'assets/node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
		'assets/new/libs/summernote/summernote-bs4.min.css',
		'assets/new/libs/bootstrap4-datetimepicker/css/bootstrap-datetimepicker.min.css',
		'assets/new/css/fpersonno.css'
	]; 

	$this->template->load('template', 'materi/index', $data);
	}
		
		
	public function get_parent()
	{		
		$sid = $_POST['sid'];
		$data_materi	= $this->model_materi->get_materi_by_subject($sid);

		
		$option='';
		foreach ($data_materi as $value)
		{
			$option .= '<option value="'.$value['materi_id'].'">'.$value['title'].'</option>';
		}			
		echo '<select type="text" class="form-control form-control-sm" name="a_materi_parent" data-live-search="true"><option value="">==Pilih==</option>'.$option.'</select>';
 
		exit;
	}	

	public function get_require()
	{		
		$sid = $_POST['sid'];
		$data_materi	= $this->model_materi->get_materi_require_by_subject($sid);

		
		$option='';
		foreach ($data_materi as $value)
		{
			$option .= '<option value="'.$value['materi_id'].'">'.$value['title'].'</option>';
		}			
		echo '<select type="text" class="form-control form-control-sm" name="a_materi_require" data-live-search="true"><option value="">==Pilih==</option>'.$option.'</select>';
 
		exit;
	}	
		
		
	public function save()
	{
//var_dump($_POST);

		//var_dump($_FILES);
//exit;
		$title        = trim($_POST['a_materi_title']);
		$subject      = trim($_POST['a_materi_subject']);
		$parent      = trim($_POST['a_materi_parent']);
		$require      = trim($_POST['a_materi_require']); 
		$ava_date     = trim($_POST['a_materi_date']);
		$description  = trim($_POST['a_materi_note']);
		$subject_name = trim($_POST['a_materi_subject_text']);
		// video
		$video_name   = $_FILES['a_materi_video']['name'];
		$video_size   = $_FILES['a_materi_video']['size'];
    $this->db->where('LOWER(title) = ', strtolower(trim($title)));
    $_exists = $this->db->get_where('materi', ['subject_id' => $subject])->num_rows();

		if($_exists > 0){
			 $status = 'Gagal'; 							
		}else{ 
			$dir = '../indocerdas/uploads/materi/'; 
			if(!is_dir($dir))  @mkdir($dir, 0777); 
			$filename = $dir.DIRECTORY_SEPARATOR.str_replace(' ', '_', strtolower($subject_name)).'-'.str_replace(' ', '_', strtolower($title)); 
			$ext = pathinfo(basename($video_name), PATHINFO_EXTENSION); 
			$move = move_uploaded_file($_FILES['a_materi_video']['tmp_name'], FCPATH.$filename.'.'.$ext);
			if(!$move) 
			{ 
				 $status = 'Gagal'; 	 		
			}else{ 
				$data = [
						'title'             => $title, 
						'subject_id'        => $subject,
						'available_date'    => $ava_date,
						'materi_file'       => $filename.'.'.$ext,
						'note'              => $description
							];
				if(!empty($parent)) $data['parent_id']=$parent;
				if(!empty($require)) $data['materi_require']=$require;
				$this->db->insert('materi', $data) ;		
			  $status = 'Berhasil'; 				
			}
		}
			echo "<script>
				alert('Data ".$status." diinput');
				window.location.href = '". base_url()."materi';
				</script>"; 		 
	}	

	/**
	 * View Materi Global
	 */
	public function tutorial() {
		$data['pageTitle']	= 'Data Materi Global';
		//$data['tableName']	= 'tbl_device';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [  
			['path' => 'assets/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js', 'defer' => true],
			['path' => 'assets/new/libs/summernote/summernote-bs4.min.js', 'defer' => true],
			['path' => 'assets/new/libs/moment/min/moment.min.js', 'defer' => true],
			['path' => 'assets/new/libs/bootstrap4-datetimepicker/js/bootstrap-datetimepicker.min.js', 'defer' => true],
			['path' => 'assets/new/js/pages/_materi_global.js', 'defer' => true],
		]; 
		$data['page_css']	= [  
			'assets/node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
			'assets/new/libs/summernote/summernote-bs4.min.css',
			'assets/new/libs/bootstrap4-datetimepicker/css/bootstrap-datetimepicker.min.css',
			'assets/new/css/fpersonno.css'
		]; 

		$this->template->load('template', 'materi/index_global', $data);
	}

	public function store_global(){
		$title      = trim($_POST['a_title']);
		$link      	= isset($_POST['a_link']) ? $_POST['a_link']: null;
		$type     	= $_POST['a_type'];
		$parent_id 	= isset($_POST['parent_id']) ? $_POST['parent_id'] : null;

		// file
		$file_name   	= $_FILES['input-file1']['name'];
		$file_size   	= $_FILES['input-file1']['size'];

		$dir = 'assets/files/materi/materi-global/';
		if(!is_dir($dir))  @mkdir($dir, 0777);
		$filename 	= base64_encode($file_name);
		$ext 		= pathinfo(basename($file_name), PATHINFO_EXTENSION);

		$move 		= move_uploaded_file($_FILES['input-file1']['tmp_name'], str_replace('admin','',FCPATH).$dir.$filename.'.'.$ext);

		$data = [
			'title'         => $title,
			'parent_id'		=> $parent_id,
			'materi_type'	=> ($type == 'file') ? 1 : 2,
		];

		// jika tipe nya file
		if($type == 'file'){

			// jika simpan file ke server gagal
			if(!$move){
				$status = 'Gagal';
			} else {

			// jika simpan file berhasil
				$data['materi_file'] = $filename.'.'.$ext;
				$data['file_size'] = $file_size/1000;

				$this->db->insert('materi_global', $data) ;		
				$status = 'Berhasil';
			}
		} else {
			$data['materi_url']	= $link;

			$this->db->insert('materi_global', $data) ;		
			$status = 'Berhasil';
		}
		
		echo "<script>
			alert('Data ".$status." diinput');
			window.location.href = '". base_url()."materi/tutorial';
			</script>"; 		 
	}
}
