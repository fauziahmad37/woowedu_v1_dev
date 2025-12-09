<?php
class Model_settings extends CI_Model{
	
	private $settings = "settings";

	public function get_settings()
	{
		$this->db->select('a.*');
		$this->db->from('settings a');
		$this->db->where('id', 1);
		$query=$this->db->get();
		$data = $query->row();
		return $data;
	}

	public function get_htm($jenis=0)
	{
		$this->db->select('a.*');
		$this->db->from('tiket_masuk a');
		$this->db->where('jenis_rombongan', $jenis);
		$query=$this->db->get();
		$data = $query->row();
		return $data;
	}

	public function edit()
	{
		$data = array(
			'harga_dewasa' => $this->input->post('dewasa_non', true),
			'harga_anak' => $this->input->post('anak_non', true),
			'harga_lansia' => $this->input->post('lansia_non', true),
			'harga_dewasa_weekend' => $this->input->post('dewasa_non_weekend', true),
			'harga_lansia_weekend' => $this->input->post('lansia_non_weekend', true),
			'harga_anak_weekend' => $this->input->post('anak_non_weekend', true),
			'harga_dewasa_libur' => $this->input->post('dewasa_non_libur', true),
			'harga_lansia_libur' => $this->input->post('lansia_non_libur', true),
			'harga_anak_libur' => $this->input->post('anak_non_libur', true)
		);

		$this->db->where('id', 1);
		$this->db->update('tiket_masuk', $data); 
		
		$data = array(
			'harga_dewasa' => $this->input->post('dewasa_rombongan', true),
			'harga_anak' => $this->input->post('anak_rombongan', true),
			'harga_lansia' => $this->input->post('lansia_rombongan', true),
			'harga_dewasa_weekend' => $this->input->post('dewasa_rombongan_weekend', true),
			'harga_lansia_weekend' => $this->input->post('lansia_rombongan_weekend', true),
			'harga_anak_weekend' => $this->input->post('anak_rombongan_weekend', true),
			'harga_dewasa_libur' => $this->input->post('dewasa_rombongan_libur', true),
			'harga_lansia_libur' => $this->input->post('lansia_rombongan_libur', true),
			'harga_anak_libur' => $this->input->post('anak_rombongan_libur', true)
		);

		$this->db->where('id', 2);
		$this->db->update('tiket_masuk', $data); 

		$data = array(
			'harga_dewasa' => $this->input->post('dewasa_sekolah', true),
			'harga_anak' => $this->input->post('anak_sekolah', true),
			'harga_lansia' => $this->input->post('lansia_sekolah', true),
			'harga_dewasa_weekend' => $this->input->post('dewasa_sekolah_weekend', true),
			'harga_lansia_weekend' => $this->input->post('lansia_sekolah_weekend', true),
			'harga_anak_weekend' => $this->input->post('anak_sekolah_weekend', true),
			'harga_dewasa_libur' => $this->input->post('dewasa_sekolah_libur', true),
			'harga_lansia_libur' => $this->input->post('lansia_sekolah_libur', true),
			'harga_anak_libur' => $this->input->post('anak_sekolah_libur', true)
		);

		$this->db->where('id', 3);
		$this->db->update('tiket_masuk', $data); 
		
		
		$data = array(
			'biaya_deposit' => $this->input->post('biaya_deposit', true)
		);

		$this->db->where('id', 1);
		return $this->db->update('settings', $data); 		
	}

	public function editjam()
	{
		$data = array( 
			'jam_buka' => $this->input->post('jam_buka', true),
			'jam_tutup' => $this->input->post('jam_tutup', true),
			'jam_buka_libur' => $this->input->post('jam_buka_libur', true),
			'jam_tutup_libur' => $this->input->post('jam_tutup_libur', true), 
			 
		);

		$this->db->where('id', 1);
		return $this->db->update('settings', $data); 
	}


	public function editstruk()
	{
		$_struk_header = str_replace ("%0D%0A", "\n",  $this->input->post('struk_header', true));
		$_struk_footer = str_replace ("%0D%0A", "\n",  $this->input->post('struk_footer', true));
		$data = array( 
			'struk_header' => $_struk_header,
			'struk_footer' => $_struk_footer
		);

		$this->db->where('id', 1);
		return $this->db->update('settings', $data); 
	}


	public function edit_link()
	{
		$data = array(			
			'facebook_url' => add_https($this->input->post('facebook_url', true)),
			'twitter_url' => add_https($this->input->post('twitter_url', true)),
			'instagram_url' => add_https($this->input->post('instagram_url', true)),
			'youtube_url' => add_https($this->input->post('youtube_url', true)),
			'telegram_url' => add_https($this->input->post('telegram_url', true))
		);

		$this->db->where('id', 1);
		return $this->db->update('settings', $data);
	}


	function wahana_count($id){   
		if(!empty($id)) {
			$this->db->where('id', $id);
		}
		$this->db->select('count(1) as total');
		$this->db->from('kelas');
		$query=$this->db->get();
		return $query->row()->total; 
	}	
 
	function wahana_search($limit,$start,$search,$col,$dir){ 
		$this->db->select('*');		
		$this->db->from('kelas');   
		if ($limit != -1) 	
		$this->db->limit($limit,$start);
		$this->db->order_by($col,$dir);
		$query=$this->db->get();

		if($query->num_rows()>0)
		{
			return $query->result();  
		}
		else
		{
			return null;
		}
	}

	function wahana_search_count($search){

		$this->db->select('count(1) as total');
		$this->db->select('*');		
		$this->db->from('kelas');   
		$query=$this->db->get();
		return $query->row()->total; 
	} 
	
	function wahana_limit($id,$limit,$start,$col,$dir){    
		if(!empty($id)) {
			$this->db->where('class_id', $id);
		}
		if ($limit != -1) 	
		$this->db->limit($limit,$start);
		$this->db->order_by($col,$dir);
		$this->db->select('*');		
		$this->db->from('kelas');   
		$query=$this->db->get();

		if($query->num_rows()>0)
		{
			return $query->result(); 
		}
		else
		{
			return null;
		}
	}
	
	
	
	function printer_count($id){   
		if(!empty($id)) {
			$this->db->where('id', $id);
		}
		$this->db->select('count(1) as total');
		$this->db->from('printer');
		$query=$this->db->get();
		return $query->row()->total; 
	}	
 
	function printer_search($limit,$start,$search,$col,$dir){ 
		$this->db->select('*');		
		$this->db->from('printer');   	
		if(!empty($search['ip_client'])) $this->db->where('ip_client', $search['ip_client']); 
		if(!empty($search['ip_printer'])) $this->db->where('ip_printer', $search['ip_printer']);    
		if ($limit != -1) 	
		$this->db->limit($limit,$start);
		$this->db->order_by($col,$dir);
		$query=$this->db->get();

		if($query->num_rows()>0)
		{
			return $query->result();  
		}
		else
		{
			return null;
		}
	}

	function printer_search_count($search){

		$this->db->select('count(1) as total');
		$this->db->from('printer');   	
		if(!empty($search['ip_client'])) $this->db->where('ip_client', $search['ip_client']); 
		if(!empty($search['ip_printer'])) $this->db->where('ip_printer', $search['ip_printer']);  
		$query=$this->db->get();
		return $query->row()->total; 
	} 
	
	function printer_limit($id,$limit,$start,$col,$dir){    
		if(!empty($id)) {
			$this->db->where('id', $id);
		}
		if ($limit != -1) 	
		$this->db->limit($limit,$start);
		$this->db->order_by($col,$dir);
		$this->db->select('*');		
		$this->db->from('printer');   
		$query=$this->db->get();

		if($query->num_rows()>0)
		{
			return $query->result(); 
		}
		else
		{
			return null;
		}
	}
	
	// Auditrail 
	public function get_all_log(array $filter = NULL, int $limit = NULL, int $offset = NULL) {
		$this->compiledLogQuery();
		if(!empty($filter) && is_array($filter))  {
				$i=0;
				foreach($filter as $f) {
						if(empty($f['search']['value'])) continue;
						$key= $f['data'];
						$val = $f['search']['value'];
						if($i === 0) 
								$this->db->where('LOWER('.$key.') LIKE \'%'.$this->db->escape_like_str(strtolower($val)).'%\''); 
						else
								$this->db->or_where('LOWER('.$key.') LIKE \'%'.$this->db->escape_like_str(strtolower($val)).'%\'');
						$i++;
				}
		}
		if(!empty($limit))
		$this->db->limit($limit, $offset);
		$res = $this->db->get();
		return $res->result_array();
	}	

	public function count_all_log(array $filter = NULL) {
		$this->compiledLogQuery();
		if(!empty($filter) && is_array($filter))  {
				$i=0;
				foreach($filter as $f) {
						if(empty($f['search']['value'])) continue;
						$key= $f['data'];
						$val = $f['search']['value'];
						if($i === 0) 
								$this->db->where('LOWER('.$key.') LIKE \'%'.$this->db->escape_like_str(strtolower($val)).'%\''); 
						else
								$this->db->or_where('LOWER('.$key.') LIKE \'%'.$this->db->escape_like_str(strtolower($val)).'%\'');
						$i++;
				}
		}
		return $this->db->count_all_results();
	}
	
	private function compiledLogQuery() {
		$this->db->select('*'); 
		$this->db->get_compiled_select('actionlog', FALSE);
	}   
	
}