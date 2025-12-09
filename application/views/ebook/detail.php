<section class="explore-section section-padding" id="section_2">
	
    <script id="ebook-detail" type="application/json">
        <?=json_encode($book) ?>
    </script>


	<?php if(isset($_SESSION['error'])): ?>
	<div class="alert alert-danger">
		<?php 
			echo $_SESSION['error']['message'];
			unset($_SESSION['error']);
		?>
		
	</div>
	<?php endif; ?>

	<!-- section search -->
	<div class="row mt-4 h-25">
		
        <div class="col-12 col-lg-6">
            <!-- <img class="img-thumbnail" src="<?//= $book['from_api'] == 1 ? html_escape($book['cover_img']) : base_url('assets/images/ebooks/cover/'.$book['cover_img']) ?>"/> -->
            <figure class="d-flex flex-nowrap w-full">
                <div class="overflow-hidden rounded d-inline-block shadow">
                    <img id="thumbnail-main" src="<?=base_url('assets/images/ebooks/cover/'.$book['cover_img']) ?>" />
                </div>
                <figcaption class="d-flex flex-column justify-content-around ps-4">
                    <label for="thumbnail-select-1" class="lbl-thumbnail-select overflow-hidden d-inline-block rounded shadow">
                        <img src="<?=base_url('assets/images/ebooks/cover/'.$book['cover_img']) ?>" class="thumbnail-select"/>
                        <input type="radio" class="d-none position-absolute input-thumbnail-select" id="thumbnail-select-1"/>
                    </label>
                    <label for="thumbnail-select-2" class="lbl-thumbnail-select overflow-hidden d-inline-block rounded shadow">
                        <img src="<?=base_url('assets/images/ebooks/cover/'.$book['cover_img']) ?>" class="thumbnail-select"/>
                        <input type="radio" class="d-none position-absolute input-thumbnail-select" id="thumbnail-select-2"/>
                    </label>
                    <label for="thumbnail-select-3" class="lbl-thumbnail-select overflow-hidden d-inline-block rounded shadow">
                        <img src="<?=base_url('assets/images/ebooks/cover/'.$book['cover_img']) ?>" class="thumbnail-select"/>
                        <input type="radio" class="d-none position-absolute input-thumbnail-select" id="thumbnail-select-3"/>
                    </label>
                    <label for="thumbnail-select-4" class="lbl-thumbnail-select overflow-hidden d-inline-block rounded shadow">
                        <img src="<?=base_url('assets/images/ebooks/cover/'.$book['cover_img']) ?>" class="thumbnail-select"/>
                        <input type="radio" class="d-none position-absolute input-thumbnail-select" id="thumbnail-select-4"/>
                    </label>
                    
                </figcaption>
            </figure>
        </div>
        <div class="col-12 col-lg-6">
            
            <h6 class="text-title"><?=$book['publisher_name']?></h6>
            <h4 class="mb-4"><?=html_escape($book['title'])?></h4>
            <span class="text-danger">
                <span class="badge badge-discount rounded-pill lh-base">10%</span>
                <i class="text-decoration-line-through">Rp. <?=html_escape("150.000")?></i>
            </span>
            <h4><strong>Rp. 85.000</strong></h4>
            <div class="d-flex mx-0 pt-3">
                <div class="col-xs-12 col-md-8 col-lg-9 pe-3">
					<?php if($ebook_member):?>
						<a href="<?=html_escape(base_url('ebook/open_book?id='.$book['id']))?>" class="btn btn-lg btn-primary w-100">Baca Ebook</a>
					<?php else: ?>
						<a href="<?=html_escape(base_url('ebook/paket?book_no='.$book['id']))?>" class="btn btn-lg btn-primary w-100">Beli Sekarang</a>
					<?php endif ?>
                </div>
                <div class="col-xs-12 col-md-4 col-lg-3 mt-sm-0 mt-xs-2">
                    <button class="btn btn-lg btn-outline-primary lh-base" id="btn-wishlist" role="checkbox" aria-checked="false"><i class="bi bi-heart"></i></button>
                    <button class="btn btn-lg btn-outline-primary lh-base" id="btn-cart" role="checkbox" aria-checked="false"><i class="bi bi-basket2"></i></button>
                </div>
            </div>
            <h5 class="mt-5 mb-3">Detail</h5>
            <div class="row row-cols-3">
                <div class="col mb-3">
                    <h6 class="text-title mb-1">Penerbit</h6>
                    <span class="fs-6 fw-semibold"><?=html_escape($book["publisher_name"])?></span>
                </div>
                <div class="col mb-2">
                    <h6 class="text-title mb-0">ISBN</h6>
                    <span class="fs-6 fw-semibold"><?=html_escape($book["isbn"])?></span>
                </div>
                <div class="col mb-2">
                    <h6 class="text-title mb-0">Halaman</h6>
                    <span class="fs-6 fw-semibold"><?=html_escape($book["total_pages"])?></span>
                </div>
                <div class="col mb-2">
                    <h6 class="text-title mb-0">Jenjang Pendidikan</h6>
                    <span class="fs-6 fw-semibold"><?=html_escape(rtrim($book["class_level_name"], "-"))?></span>
                </div>
                <div class="col mb-2">
                    <h6 class="text-title mb-0">Tahun Terbit</h6>
                    <span class="fs-6 fw-semibold"><?=html_escape($book["publish_year"])?></span>
                </div>

            </div>
            <h5 class="mt-5 mb-3">Deskripsi</h5>
            <p>
                <span class="desc-content text-justify">
                <?=html_escape(trim($book['description']))?>
                </span>
                <a role="button" id="open-content" class="d-none"><small>Baca Selengkapnya</small></a>
                <br />
                <a role="button" id="close-content" class="d-none"><small>Tutup Kembali</small></a>
            </p>
        </div>

	</div>
    
    <!-- Rating Summary -->
    <div class="row mt-4">
        <h4>Rating Buku</h4>
        <div class="col-lg-2 col-md-4">
            <div class="card">
                <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 130px">
                    <h1 class="fs-1" id="value-avg">0.0</h1>
                    <div id="gpa-container" class="d-flex position-relative">
                        <div id="empty-star" class="d-flex align-middle position-relative">
                            <span class="d-inline-flex">
                                <i class="bi bi-star text-warning"></i>
                                <i class="bi bi-star text-warning"></i>
                                <i class="bi bi-star text-warning"></i>
                                <i class="bi bi-star text-warning"></i>
                                <i class="bi bi-star text-warning"></i>
                            </span>
                            
                        </div>
                        <div id="filled-star" class="d-flex position-absolute top-0 overflow-hidden" style="width: 0%">
                            <span class="m-0 p-0 d-inline-flex">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </span>
                            
                        </div>
                    </div>
                   
                    <h6>Ebook Rating</h6>
                </div>
            </div>
        </div>
        <div class="col-10 d-flex flex-nowrap">
            <table class="w-100">
                    <tbody>
                        <tr>
                            <td style="width: 12%">
                                <span class="d-inline-block">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                </span>
                            </td>
                            <td style="width: 80%">
                                <div class="progress bg-warning bg-opacity-25" role="progressbar" aria-label="rate-5" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
                                    <div class="progress-bar bg-warning" id="rate-5-prog" style="width: 0%"></div>
                                </div>
                            </td>
                            <td id="rate-5-percent" class="text-end"></td>
                        </tr>
                        <tr>
                            <td>
                                <span class="d-inline-block">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                </span>
                            </td>
                            <td>
                                <div class="progress bg-warning bg-opacity-25" role="progressbar" aria-label="rate-4" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
                                    <div class="progress-bar bg-warning" id="rate-4-prog" style="width: 0%"></div>
                                </div>
                            </td>
                            <td id="rate-4-percent" class="text-end"></td>
                        </tr>
                        <tr>
                            <td>
                                <span class="d-inline-block">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                </span>
                            </td>
                            <td>
                                <div class="progress bg-warning bg-opacity-25" role="progressbar" aria-label="rate-3" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
                                    <div class="progress-bar bg-warning" id="rate-3-prog" style="width: 0%"></div>
                                </div>
                            </td>
                            <td id="rate-3-percent" class="text-end"></td>
                        </tr>
                        <tr>
                            <td>
                                <span class="d-inline-block">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                </span>
                            </td>
                            <td>
                                <div class="progress bg-warning bg-opacity-25" role="progressbar" aria-label="rate-3" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
                                    <div class="progress-bar bg-warning" id="rate-2-prog" style="width: 0%"></div>
                                </div>
                            </td>
                            <td id="rate-2-percent" class="text-end"></td>
                        </tr>
                        <tr>
                            <td>
                                <span class="d-inline-block">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                </span>
                            </td>
                            <td>
                                <div class="progress bg-warning bg-opacity-25" role="progressbar" aria-label="rate-3" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
                                    <div class="progress-bar bg-warning" id="rate-1-prog" style="width: 0%"></div>
                                </div>
                            </td>
                            <td id="rate-1-percent" class="text-end"></td>
                        </tr>
                    </tbody>
                </table>
            
        </div>
    </div>

    <!-- Ulasan Pembeli -->
    <div class="d-flex flex-nowrap align-items-center">
        <h4 class="mt-5 mb-3">Ulasan Pembeli</h4>
        <div class="ms-auto">
            <div class="dropdown" id="rating-dropdown">
                <button type="button" class="btn bg-white dropdown-toggle form-select border ps-2" data-bs-toggle="dropdown" aria-expanded="false" style="--bs-btn-padding-x: 2rem">
                    Pilih Rating
                </button>
                <ul role="listbox" id="list-rating" class="dropdown-menu">
                    <li role="option" data-value="" aria-selected="true"><a role="button" class="dropdown-item">Pilih Rating</a></li>
                    <li role="option" data-value="1" aria-selected="false">
                        <a role="button" class="dropdown-item"><i class="bi bi-star-fill text-warning"></i><span class="ms-2">Rating 1</span></a>
                    </li>
                    <li role="option" data-value="2" aria-selected="false">
                        <a role="button" class="dropdown-item"><i class="bi bi-star-fill text-warning"></i><span class="ms-2">Rating 2</span></a>
                    </li>
                    <li role="option" data-value="3" aria-selected="false">
                        <a role="button" class="dropdown-item"><i class="bi bi-star-fill text-warning"></i><span class="ms-2">Rating 3</span></a>
                    </li>
                    <li role="option" data-value="4" aria-selected="false">
                        <a role="button" class="dropdown-item"><i class="bi bi-star-fill text-warning"></i><span class="ms-2">Rating 4</span></a>
                    </li>
                    <li role="option" data-value="5" aria-selected="false">
                        <a role="button" class="dropdown-item"><i class="bi bi-star-fill text-warning"></i><span class="ms-2">Rating 5</span></a>
                    </li>

                    
                </ul>
            </div>
        </div>
    </div>

    <ul id="daftar-ulasan" class="list-group list-group-flush">
      
    </ul>
  

    
    <div class="w-100 d-flex flex-nowrap align-items-center mt-5 mb-3">
        <h4 class="mt-4">Buku Serupa</h4>
        <a class="text-link-primary ms-auto">Lihat Semua</a>
    </div>

    <div class="row mx-0 px-0 py-2 mb-5">
        <?php foreach($similars as $sim): 
            $coverinfo = pathinfo($sim['cover_img']);
        ?>
        <div class="col-lg-2 px-0">

            <a class="overflow-hidden ebook-card shadow-sm rounded p-0 d-inline-block mb-1" href="<?=base_url('ebook/detail/'.$sim['id'])?>">
                <img height="120" src="<?=base_url('assets/images/ebooks/cover/'.basename($coverinfo['filename']).'_thumb.'.$coverinfo['extension'])?>" 
                onerror="this.src = 'assets/images/ebooks/cover/default.png';">
            </a>

            <!--<a class="card ebook-card flex-row flex-nowrap justify-content-around shadow-sm" href="<?=base_url('ebook/detail/'.$hist['book_id'])?>" 
                onmouseover="this.classList.remove('shadow-sm'); this.classList.add('shadow')" 
                onmouseout="this.classList.remove('shadow'); this.classList.add('shadow-sm')" style="height: 130px">
                <img height="90" src="<?=base_url('assets/images/ebooks/cover/'.basename($coverinfo['filename']).'_thumb.'.$coverinfo['extension'])?>" class="ms-2 my-2" 
                    onerror="this.src = 'assets/images/ebooks/cover/default.png';">
                <div class="card-body flex-grow-1">
                    <h6 class="p-0 mt-0"><?=$hist['title']?></h6>
                    <!-- <p class="fs-14 py-2">Teks Non Ekonomi</p> 
                </div>
            </a>-->
                
        </div>
        <?php endforeach; unset($history) ?>
    </div>
	
</section>

