<?php

class Rating extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('model_rating');

        if (!isset($_SESSION['username'])) redirect('auth/login');
    }

    public function insertRating() {
        
    }

    /**
     * List data ratings
     *
     * @return void
     */
    public function list(): void {
        try 
        {
            $bookCode = $this->input->get('book');
            
            header('Content-Type: application/json');

            if(empty($bookCode))
            {
                http_response_code(422);
                echo json_encode(['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')]);
                return;
            }

            $book = $this->db->get_where('ebooks', ['book_code' => $bookCode]);
            if($book->num_rows() == 0)
            {
                http_response_code(422);
                echo json_encode(['err_status' => 'error', 'message' => 'Buku dengan kode '.$bookCode.' tidak tersedia']);
                return;
            }
            $buku = $book->row_array();

            $books = $this->model_rating->getByEbook($buku['id']);

            $resp = ['count' => $books->num_rows(), 'data' => $books->result_array()];
            echo json_encode($resp, JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
        }
        catch(Exception $e)
        {
            log_message("error", $e->getMessage());
        }
    }
}