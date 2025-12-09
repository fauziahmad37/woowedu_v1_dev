<?php 
ini_set('memory_limit', '4000M');
ini_set('max_execution_time', '-1');

class IndoPustaka extends MY_Controller {

    private $username;
    private $password;

    public function __construct() {
        parent::__construct();

        $this->load->library(['curl', 'image_lib']);
        $this->load->model('publisher_model');

        $this->username = "OGE3ZWVlZTgtNTVhNy0xMWVkLTk0ZWUtOGZlY2YzZmEwYjQ2";
        $this->password = "indopustakatesting";
        $this->url = "http://api.indopustakaplus.com/api.php";
    }

    /**
     * Do request to api.indopustaka.com
     *
     * @param string $action
     * @param array $param
     * @return array
     */
    private function request(string $action = NULL, array $param = NULL): array {
        $params = !empty($param) ? array_merge(['action' => $action], $params) : ['action' => $action];
        $query = http_build_query($params);
        $url =  $this->url.'?'.$query;

        $options = [
            'encoding'        => 'UTF-8',
            'max_redirs'      => 10,
            'follow_location' => true
        ];
        
        $headers = [
            'Authorization: Basic '.base64_encode($this->username.':'.$this->password)
        ];

        $curl = $this->curl->setOption($options)->setHeader($headers)->request($url);
        return json_decode($curl, TRUE);
    }

    /**
     * Donwload and export cover into files image
     *
     * @param string $uri
     * @param string $title
     * @return string
     */
    private function coverImage(string $uri, string $title): array {
        $file = file_get_contents($uri);
        $filename = md5($title);
        $path = 'assets/images/ebooks/cover';
        $source = FCPATH.'../'.$path.'/'.$filename.'.jpg';

        if(file_exists($source))
            unlink($source);

        file_put_contents($source, $file);
        // resize
        $config['image_library'] = 'gd2';
        $config['source_image'] = $source;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width']         = 128 - 50;
        $config['height']       = 165 - 50;

        $this->image_lib->initialize($config);
        if(!$this->image_lib->resize())
        {
            $resp = ['success' => false, 'message' => $this->image_lib->display_errors(), 'uri' => $file];
            http_response_code(422);
            echo json_encode($resp);
            return [];
        }

        $this->image_lib->clear();

        return ['uri' => $file, 'name' => $filename.'.jpg'];
    }

    /**
     * Get All Categories
     *
     * @return void
     */
    public function get_categories(): void {
        $request = $this->request('kategori');


        if($request['message'][0] != 'Success')
        {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Gagal mengambil data dari API !!!']);
            return;
        }
        
        $this->db->trans_start();
        foreach($request['data_kategori'] as $req)
        {
            $data = [
                'category_code' => $req['id_kategori'],
                'category_name' => $req['nama_kategori'],
                'total_books'   => $req['jumlah_buku_kategori']
            ];

            if($this->db->get_where('categories', ['category_code' => $req['id_kategori']])->num_rows() > 0)
                $this->db->update('categories', $data, ['category_code' => $req['id_kategori']]);
            else
                $this->db->insert('categories', $data);
        }
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Data gagal di simpan !!!']);
            return;
        }
        $this->db->trans_commit();

        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Data berhasil di simpan !!!']);
    }

    public function test() {
        $request = $this->request('ebook');

        foreach($request['data_ebook'] as $req)
        {
            // $data[] = [
            //     'book_code'     => $req['id_buku'],
            //     'title'         => $req['judul'],
            //     'description'   => $req['sinopsis'],
            //     'isbn'          => str_replace(' ', '' , $req['ISBN']),
            //     'author'        => $req['penulis'],
            //     'publish_year'  => $req['tahun_terbit'],
            //     'category_id'   => $req['id_kategori'],
            //     'total_pages'   => preg_replace('/[^\d]/i', NULL, $req['halaman']),
            //     'qty'           => $req['stok_buku'],
            //     'file_1'        => $req['pdf_url'],
            //     'cover_img'     => base64_encode(file_get_contents($req['cover'])),
            //     'publisher_id'  => $publisher[array_search(trim($req['penerbit']), array_column($publisher, 'publisher_name'))]['id']
            // ];
            
            echo base64_encode(file_get_contents($req['cover']))."<br />";
            @flush();
            @ob_end_flush();
        }

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
    }

    /**
     * Get All ebooks
     *
     * @return void
     */
    public function get_books(): void {
        $request = $this->request('ebook');


        if($request['message'][0] != 'Success')
        {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Gagal mengambil data dari API !!!']);
            return;
        }
        /*
         "id_buku": "B0000000000000007245",
            "judul": "BUKU TEKS PENDAMPING Tematik 5 Tema 2: Udara Bersih Bagi Kesehatan-SD MI KelasV",
            "sinopsis": "BUKU TEKS PENDAMPING Tematik 5 Tema 2: Udara Bersih Bagi Kesehatan-SD MI KelasV",
            "penulis": " Drs. Samidi, M.Pd.",
            "penerbit": "Penerbit ANDI",
            "id_kategori": "91",
            "nama_kategori": "Pelajaran",
            "ISBN": "978-979-29-9945-7",
            "halaman": "vi+170",
            "edisi": "i",
            "tahun_terbit": "2019",
            "cover": "https://api.indopustakaplus.com/url/cover/BUKU TEKS PENDAMPING Tematik 5 Tema 2 Udara Bersih Bagi Kesehatan - SDMI Kelas V.jpg",
            "pdf_url": "https://api.indopustakaplus.com/url/pdf/BUKU TEKS PENDAMPING Tematik 5 Tema 2 Udara Bersih Bagi Kesehatan - SDMI Kelas V.pdf",
            "status_het": "N",
            "stok_buku": "10",
            "stok_current": "10"
        */

        // input publisher
        $newPublishers = array_unique(array_map(fn ($p) => html_escape(trim($p['penerbit'])), $request['data_ebook']));
        
        // start transaction for all
        $this->db->trans_start();
        foreach($newPublishers as $publisher)
        {
            if($this->db->get_where('publishers', ['publisher_name' => $publisher])->num_rows() > 0) continue;
            $this->db->insert('publishers', ['publisher_name' => $publisher]);
        }

        $publisher = $this->publisher_model->get_all();
        // input ebook
        foreach($request['data_ebook'] as $req)
        {
            $data = [
                'from_api'      => 1,
                'book_code'     => $req['id_buku'],
                'title'         => $req['judul'],
                'description'   => $req['sinopsis'],
                'isbn'          => str_replace(' ', '' , $req['ISBN']),
                'author'        => $req['penulis'],
                'publish_year'  => $req['tahun_terbit'],
                'category_id'   => $req['id_kategori'],
                'total_pages'   => preg_replace('/[^\d]/i', NULL, $req['halaman']),
                'qty'           => $req['stok_buku'],
                'file_1'        => $req['pdf_url'],
                'cover_img'     => $req['cover'],
                'publisher_id'  => $publisher[array_search(trim($req['penerbit']), array_column($publisher, 'publisher_name'))]['id']
            ];

            if($this->db->get_where('ebooks', ['book_code' => $req['id_buku']])->num_rows() > 0)
                $this->db->update('ebooks', $data, ['book_code' => $req['id_buku']]);
            else
                $this->db->insert('ebooks', $data);
                
        }
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Data gagal di simpan !!!']);
            return;
        }
        $this->db->trans_commit();

        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Data berhasil di simpan !!!']);
    }
}