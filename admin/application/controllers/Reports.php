<?php defined('BASEPATH') or exit('No direct script access allowed');
use mikehaertl\wkhtmlto\Pdf;
class Reports extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		check_loggin();     
			$this->load->model(['model_common']);
			$this->load->model(['model_student']);
		$this->load->helper('assets');
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
	}


 
	public function detail_all_reports()
	{
		$id = $this->input->post('id');
		if(isset($id) and !empty($id)){
			$row = $this->m_reports->detail_all_transaction($id);
			$output = '';

			if($row->host_gender == 1){
				$gender = 'Pria';
			}else{
				$gender = 'Wanita';
			}

			if($row->type_events == '1'){
				$desk_events = '<span class="text-success">'.$row->desk_events.' <span class="badge badge-success float-right">NORMAL</span></span>';
			}else if($row->type_events == '2'){
				$desk_events = '<span class="text-warning">'.$row->desk_events.' <span class="badge badge-warning float-right">EXCEPTION</span></span>';
			}else if($row->type_events == '3'){
				$desk_events = '<span class="text-danger">'.$row->desk_events.' <span class="badge badge-danger float-right">ALARM</span></span>';
			}else{
				$desk_events = '<span class="text-danger">undifined<span class="badge badge-danger float-right">UNDIFINED</span></span>';
			}
			
			if($row->tl_photo != '')
			{
				$tl_photo = '<img class="img-fluid rounded" src="'. $row->tl_photo .'" />';
			}else{
				$tl_photo = '<img class="img-fluid rounded" src="'. base_url('assets/new/images/ava-user.png') .'" />';
			}

			$output .= '
					<div class="table-responsive">
						<table class="table table-hover table-nowrap mb-0">
							<tbody>
								<tr>
									<th rowspan="15">
										<center>
											<div class="media">
												'.$tl_photo.'
											</div>
										</center>
									</th>
								</tr>
								<tr>
									<th scope="row">Event Date</th>
									<th width="2%"> : </th>
									<td>'.date('d F Y, H:m:is', strtotime($row->tl_time)).'</td>
									<th scope="row">Area Name</th>
									<th width="2%"> : </th>
									<td>'.$row->area_name.'</td>
								</tr>
								<tr>
									<th scope="row">Device Name</th>
									<th width="2%"> : </th>
									<td>'.$row->device_name.'</td>
									<th scope="row">Event Point</th>
									<th width="2%"> : </th>
									<td>'.$row->tl_event.'</td>
								</tr>
								<tr>
									<th scope="row">Event Description</th>
									<th width="2%"> : </th>
									<td colspan="4">'.$desk_events.'</td>
								</tr>
								<tr>
									<th scope="row">Personel ID</th>
									<th width="2%"> : </th>
									<td>'.$row->tl_userid.'</td>
									<th scope="row">Gender</th>
									<th width="2%"> : </th>
									<td>'.$gender.'</td>
								</tr>
								<tr>
									<th scope="row">Personel Name</th>
									<th width="2%"> : </th>
									<td colspan="4">'.$row->tl_persname.'</td>
								</tr>
								<tr>
									<th scope="row">Department ID</th>
									<th width="2%"> : </th>
									<td>'.$row->department_id.'</td>
									<th scope="row">Department Name</th>
									<th width="2%"> : </th>
									<td>'.$row->department_name.'</td>
								</tr>
								
								<tr>
									<th scope="row">Verification Type</th>
									<th width="2%"> : </th>
									<td>'.$row->tl_verifytype.'</td>
									<th scope="row">Reader Name</th>
									<th width="2%"> : </th>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>';
			echo $output;
		}
		else {
			echo '<center><ul class="list-group"><li class="list-group-item">'.'Tidak Ada Data'.'</li></ul></center>';
		}
	}

	public function reptransaksi()
	{
		$data['pageTitle']		= 'Report - Transaksi';
		$data['tableName']		= 'tbl_reportall';
		$data['page_js']		= [
			['path' => 'assets/new/libs/datatables.net-fixedColumn/js/dataTables.fixedColumns.min.js', 'defer' => true]
		];
		$data['page_css']		= [
			'assets/new/libs/datatables.net-fixedColumn/css/fixedColumns.dataTables.min.css'
		];
		$data['wahana']		= $this->m_transaction->get_wahana();
		$data['recusers']		= $this->m_transaction->get_users();
		$data['rombongan']		= $this->m_transaction->get_rombongan();
		$data['jenis_bayar']		= $this->m_transaction->get_jenis_bayar();
		$this->template->load('template', 'reports/reptransaksi/index', $data);
	}

 	public function view_reptransaksi()
	{
 		$search = array();
		$filhostid = '';

 
		$columns = array(
			0 => 'id',
			1 => 'tanggal',
			2 => 'jenis_bayar', 			
			3 => 'nomor_referensi'
		);
 

		//searching
		if (!empty($_POST['sStart'])) $search['start_date'] = $_POST['sStart'];
		if (!empty($_POST['sEnd'])) $search['end_date'] = $_POST['sEnd'];
		if (!empty($_POST['sRombongan'])) $search['jenis_rombongan'] = $_POST['sRombongan'];
		if (!empty($_POST['sJenisBayar'])) $search['jenis_bayar'] = $_POST['sJenisBayar'];
		if (!empty($_POST['sUser'])) $search['user_input'] = $_POST['sUser']; 

		$limit = $this->input->post('length');
		$start = $this->input->post('start');
		$order = $columns[$this->input->post('order')[0]['column']];
		$dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->m_transaction->trans_count($filhostid);
		$totalFiltered = $totalData;

		if(empty($search))
		{            
			$posts = $this->m_transaction->trans_limit($filhostid,$limit,$start,$order,$dir);
		}
		else {
			$posts =  $this->m_transaction->trans_search($limit,$start,$search,$order,$dir);
			$totalFiltered = $this->m_transaction->trans_search_count($search);
		}
		
		$rombongan  = $this->m_transaction->get_rombongan();
		$jenis_bayar  = $this->m_transaction->get_jenis_bayar();
		$data = array(); 
			
		if (!empty($posts)) {
			foreach ($posts as $post) {
				$transData['DT_RowId'] = $post->id;
				$transData['tl_id'] = $post->id;
				$transData['tanggal'] = $post->tanggal;
				$transData['no_transaksi'] = $post->nomor_trans; 				
				$transData['jenis_bayar'] = $jenis_bayar[$post->jenis_bayar]; 				 
				$transData['no_ref'] = $post->nomor_referensi; 
				$transData['user'] = $this->m_common->getusername($post->user_input); 
				$transData['kuantitas'] = $post->jml_dewasa + $post->jml_anak + $post->jml_lansia;
				$transData['harga_jual'] = $post->harga_dewasa;
				$transData['service_fee'] = $post->service_fee;
				$transData['total_jual'] = $post->harga_dewasa * ($post->jml_dewasa + $post->jml_anak + $post->jml_lansia);
				$transData['total_fee'] = $post->service_fee * ($post->jml_dewasa + $post->jml_anak + $post->jml_lansia);
				$data[] = $transData;
			}
		}

		$json_data = array(
			"draw"			  => intval($this->input->post('draw')),
			"recordsTotal"	  => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"			  => $data
		);

		echo json_encode($json_data); 
	}


	public function rekapnilai()
	{
		$data['pageTitle']		= 'Laporan - Rekap Nilai';
		$data['tableName']		= 'tbl_reportall';
 
 
		$data['page_js'] = [ 
			['path' => 'assets/new/js/pages/_rekapnilai.js', 'defer' => true]
		]; 
		$this->template->load('template', 'reports/rekapnilai', $data);		
		
	}
	
	
 	public function get_rekap_nilai()
	{
    
    header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET');
		header('Content-Type: application/json');		
		
		if($_SERVER['REQUEST_METHOD'] !== 'GET') {
				http_response_code(405);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
				return;
		}
				
		$draw   = $this->input->get('draw');
		$limit  = $this->input->get('length');
		$offset = $this->input->get('start');
		$filter = $this->input->get('columns'); 
		$rec   = $this->model_student->get_all_student($filter, $limit, $offset);
		$count  = $this->model_student->count_all_student($filter);
    $countFilter = $this->model_student->count_all_student($filter);
		//var_dump($rec);
		$i=0;
		foreach($rec as $val){
			$rec[$i]['nilai_tugas'] = $this->model_student->get_nilai_tugas($val['student_id'],$val['subject_id']);
			$rec[$i]['nilai_ujian']=  $this->model_student->get_nilai_ujian($val['student_id'],$val['subject_id']); 
			$i++;
		}
		$datas =   array(
			"draw"			  => $draw,
			"recordsTotal"	  => $count,
			"recordsFiltered" => $countFilter,
			"data"			  => $rec
		);
		http_response_code(200);
		echo json_encode($datas, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit();
	}


	
	public function detailnilai()
	{
		$data['pageTitle']		= 'Laporan - Detail Nilai';
		$data['tableName']		= 'tbl_reportall';
		$data['page_js'] = [ 
			['path' => 'assets/new/js/pages/_detailnilai.js', 'defer' => true]
		]; 
		$this->template->load('template', 'reports/detailnilai', $data);
	}

	public function search_detail_nilai()
	{
		$data['data_nilai']		=   '';
		
		$tbl = $this->load->view('reports/detailnilairesult', $data,true);
		echo $tbl;		
	}
	
}
