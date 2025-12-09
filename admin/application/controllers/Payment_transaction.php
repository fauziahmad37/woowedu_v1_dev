<?php

class Payment_transaction extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		check_Loggin();
		$this->load->model(['model_common','model_payment_transaction']);
		$this->load->helper('url');
		$this->load->helper('slug');
		$this->load->helper('assets');
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
		$this->load->library('csrfsimple');
	}

	public function index()
	{
		$data['title'] = 'Payment Transaction';
		$data['page'] = 'Payment Transaction';
		$data['breadcrumb'] = 'Payment Transaction';
		$data['icon'] = 'fa fa-credit-card';
		$data['path'] = 'admin/payment_transaction/index';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$this->template->load('template', 'payment_transaction/index', $data);
	}

	function getAll()
	{
		$draw = $this->input->get('draw', TRUE);
		$limit = $this->input->get('length', TRUE);
		$offset = $this->input->get('start', TRUE);
		$filters = $this->input->get('columns');

		$order = $this->input->get('order');

		if ($limit < 0) {
			$limit = 10;
		}

		$data = $this->model_payment_transaction->getAll($filters, $limit, $offset, $order);

		foreach ($data as $key => $val) {
			// jika field_table adalah bundling
			if ($val['field_table'] == 'bundle') {
				// get data bundling
				$bundling = $this->db->select('*')->from('checkout_items')
					->join('bundling_packages', 'bundling_packages.id = checkout_items.item_id', 'left')
					->where('checkout_id', $val['field_id'])
					->get()->row_array();
				
				$detail = [
					'package_name' => $bundling['package_name'],
					'image' => $this->config->item('url_client') . 'assets/images/bundlings/' . $bundling['package_image'],
					'price' => $bundling['price'],
					'discount' => $val['discount'],
				];
			} else {
				
			}

			$data[$key]['detail'] = $detail;
		}

		$resp = [
			'draw' => $draw,
			'data' => $data,
			'recordsTotal' => $this->model_payment_transaction->countAll($filters),
			'recordsFiltered' => $this->model_payment_transaction->countAll($filters)
		];

		echo json_encode($resp, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}
}
