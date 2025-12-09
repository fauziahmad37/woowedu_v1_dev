<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_report extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

	 

	function current_employee($start,$end){  
 
 
		$this->db->select('host_id, host_name,host_nip, company_name, host_unit_kerja,host_jabatan,host_no_kartu,tl_time,tl_sn');
		$this->db->from('host');
		$this->db->join('company','host_company_id=company_id');
		$this->db->join('devtranslog','tl_userid=host_nip');
		$this->db->where('tl_time >=', $start.' 00:00:00');
		$this->db->where('tl_time <=', $end.' 23:59:59');
		$this->db->order_by('tl_time','asc');
 		
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

	function current_visitor($start,$end,$location,$checktype){  
 
 
		$this->db->select('visitor_id, v.nama, company_name_manual, reason_name,visit_start,floor_manual,no_kartu');
		$this->db->from('visitor v');
		$this->db->join('reason r','r.reason_id=v.reason_id','left');
		$this->db->where('visit_start >=', $start.' 00:00:00');
		$this->db->where('visit_start <=', $end.' 23:59:59');
		if(!empty($location))$this->db->where('location_site', $location);
	//	if(!empty($checktype))$this->db->where('status_checkout', $checktype);
		
		//$this->db->where('status_checkout', 0);
	//	$where = '(status_checkout is null or status_checkout=0)';
	//	$this->db->where($where);		
		
		if($checktype=='0' || $checktype=='1')$this->db->where('status_checkout', $checktype);
		
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
	
	function visitor_count_station($start,$end){  
		//$this->db->select('users.username');
		$this->db->select('visitor.user_input,users.username,COUNT(visitor.user_input) as total');
		$this->db->from('visitor');
		$this->db->join('users','users.userid=visitor.user_input');
		//$this->db->join('visitor v','t.tl_visitor_id=v.visitor_id');
		$this->db->where('visitor.visit_start >=', $start.' 00:00:00');
		$this->db->where('visitor.visit_start <=', $end.' 23:59:59');
		//$this->db->where('user_input', $id);
		$this->db->group_by('visitor.user_input,users.username');
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

	function host_count_station($start,$end){  
		$this->db->select('devtranslog.tl_userid');
		//$this->db->select('COUNT(devtranslog.tl_userid) as total');
		$this->db->from('devtranslog');
		//$this->db->join('devtranslog','devtranslog.tl_userid=host.host_nip');
		//$this->db->join('users','users.userid=visitor.user_input');
		//$this->db->join('visitor v','t.tl_visitor_id=v.visitor_id');
		$this->db->where('devtranslog.tl_userid !=','0');
		//$this->db->where('devtranslog.tl_userid =','1269320491');
		$this->db->where('devtranslog.tl_time >=', $start.' 00:00:00');
		$this->db->where('devtranslog.tl_time <=', $end.' 23:59:59');
		//$this->db->where('user_input', $id);
		$this->db->group_by('devtranslog.tl_userid');
		$query=$this->db->get();
		
		if($query->num_rows()>0)
		{
			//return $query->result();
			return $query->num_rows();
		}
		else
		{
			return null;
		}
	}



	function visitor_by_date($start,$end,$nik=''){  
 
 
		$this->db->select('visitor_id, v.nama, company_name_manual, reason_name,photo_identitas,photo_visitor,work_unit_manual,
											visit_start,floor_manual,no_kartu,tl_time,tl_inoutstatus,alias,mail,no_identitas,host_name_manual');
		$this->db->from('visitor v');
		$this->db->join('reason r','r.reason_id=v.reason_id','left');
		$this->db->join('devtranslog','tl_visitor_id=visitor_id','left');
		$this->db->join('devicedetail','tl_sn=sn','left');
		$this->db->where('visit_start >=', $start.' 00:00:00');
		$this->db->where('visit_start <=', $end.' 23:59:59'); 
		if(!empty($nik))
		$this->db->where('no_identitas',$nik); 
		$this->db->order_by('visitor_id','desc'); 
		
		$query=$this->db->get();

		if($query->num_rows()>0)
		{
			return $query->result(); 
		}
		else
		{
			return false;
		}
	}

	function host_by_date($start,$end,$nik=''){  
 
 
		/*$this->db->select('visitor_id, v.nama, company_name_manual, reason_name,photo_identitas,photo_visitor,work_unit_manual,
											visit_start,floor_manual,no_kartu,tl_time,tl_inoutstatus,alias,mail,no_identitas,host_name_manual');*/
		$this->db->select('host_id, host_name, company_name,host_departemen,host_group,host_nip,host_direktorat,host_unit_kerja,tl_time,tl_inoutstatus,alias');
											
		$this->db->from('host');
		//$this->db->join('reason r','r.reason_id=v.reason_id','left');
		$this->db->join('company','host.host_company_id=company.company_id','left');
		$this->db->join('devtranslog','tl_userid=host_nip','left');
		$this->db->join('devicedetail','tl_sn=sn','left');
		$this->db->where('tl_time >=', $start.' 00:00:00');
		$this->db->where('tl_time <=', $end.' 23:59:59'); 
		if(!empty($nik))
		$this->db->where('no_identitas',$nik); 
		$this->db->order_by('host_id','desc'); 
		
		$query=$this->db->get();

		if($query->num_rows()>0)
		{
			return $query->result(); 
		}
		else
		{
			return false;
		}
	}


	function history_visitor($nik){  
 
 
		$this->db->select('visitor_id,v.nama, host_name_manual, reason_name,visit_start,visit_end,floor_manual,no_kartu');
		$this->db->from('visitor v');
		$this->db->join('reason r','r.reason_id=v.reason_id','left'); 
		$this->db->where('no_identitas',$nik); 
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
	
	function history_host($nik){  
 
 
		$this->db->select('host_id,host_name,host_company_id,host_dept_id,host_nip');
		$this->db->from('host');
		//$this->db->join('reason r','r.reason_id=v.reason_id','left'); 
		$this->db->where('host_nip',$nik); 
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
	
	function log_visitor($visitor_id){  
 
 
		$this->db->select('tl_time,tl_inoutstatus,alias');
		$this->db->from('devicedetail');
		$this->db->join('devtranslog','tl_sn=sn','right'); 
		$this->db->where('tl_visitor_id',$visitor_id); 
		$query=$this->db->get();

		if($query->num_rows()>0)
		{
			return $query->result(); 
		}
		else
		{
			return false;
		}
	}	
	
	function log_host($host_id,$sdate,$edate){  
 
 
		$this->db->select('tl_time,tl_inoutstatus,alias');
		$this->db->from('devicedetail');
		$this->db->join('devtranslog','tl_sn=sn','right'); 
		$this->db->where('tl_userid',$host_id); 
		$this->db->where('tl_time >=', $sdate.' 00:00:00');
		$this->db->where('tl_time <=', $edate.' 23:59:59'); 
		$query=$this->db->get();

		if($query->num_rows()>0)
		{
			return $query->result(); 
		}
		else
		{
			return false;
		}
	}	

	
	
	
}
