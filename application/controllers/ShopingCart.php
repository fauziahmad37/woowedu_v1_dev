<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ShopingCart extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */

    function __construct()
    {
        parent::__construct();
        $this->load->model('model_task');
        $this->load->model(['model_kelas', 'model_ebook', 'model_users']);
        $this->load->helper('assets');
        
        if (!isset($_SESSION['username'])) redirect('auth/login');
    }
    
    public function getAll() {
        try
        {
            $res = $this->model_users->getShopingItemsByUser($_SESSION['userid']);

            header("Content-Type: application/json");
            echo json_encode(['count' => $res->num_rows(), 'data' => $res->result_array()], JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
        }
        catch(Exception $e)
        {
            log_message("error", $e->_toString());
        }
    }

    public function getByPublisher() {
        try
        {
            $res = $this->model_users->getShopingItemsByUser($_SESSION['userid']);
            $data = [];

            $map = array_reduce($res->result_array(), function ($carry, $item) {
               $carry[$item['publisher_id']]['publisher_name'] = $item['publisher_name'];
               unset($item['publisher_name']);
               $carry[$item['publisher_id']]['books'][] = $item;
                
                return $carry;
            }, []);

            header("Content-Type: application/json");
            echo json_encode(['count' => count($map), 'data' => $map], JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
        }
        catch(Exception $e)
        {
            log_message("error", $e->_toString());
        }
    }

    /**
     * GET a Book
     *
     * @return void
     */
	public function get(): void
	{
		try 
        {
            $user = $_SESSION['userid'];
            $book = $this->input->get("ebook_id", TRUE);

            $get = $this->db->get_where("shopping_cart", ['user_id' => $user, "ebook_id" => $book]);

            $isLiked = FALSE;
            if($get->num_rows() > 0)
            {
                $isLiked = TRUE;
            }

            echo json_encode(['isLiked' => $isLiked], JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
        }
        catch(Exception $e)
        {
            log_message("error", $e->_toString());
        }
	}

    /**
     * Insert new data to shopping cart
     *
     * @return void
     */
    public function post(): void {
        $user = $_SESSION['userid'];
        $book = $this->input->post("ebook_id", TRUE);
		$item_type = $this->input->post("item_type", TRUE);

		$item_type = ($item_type == 'bundling') ? 'bundling' : 'ebook';

        header("Content-Type: application/json");

        $get = $this->db->get_where("shopping_cart", ['user_id' => $user, "item_id" => $book]);
        $ret = FALSE;
        if($get->num_rows() > 0)
        {
            $this->db->delete("shopping_cart", ['user_id' => $user, "item_id" => $book]);
            $ret = FALSE;
        }
        else
        {
            $this->db->insert("shopping_cart", ['user_id' => $user, "item_id" => $book, "item_type" => $item_type]);
            $ret = TRUE;
        }

        echo json_encode(['isLiked' => $ret, "csrf_name" => $this->security->get_csrf_token_name(), "csrf_token" => $this->security->get_csrf_hash()], JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
    }

}
