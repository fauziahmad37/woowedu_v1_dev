<section class="row row-cols-1 w-100 justify-content-center pt-3 pb-5">

	<input type="hidden" id="ebook_id" value="<?= $ebook_id; ?>">
	<!-- <input type="hidden" name="publisher_id" value="<?= $data['publisher_id']; ?>"> -->
	<input type="hidden" name="publisher_id">

    <div class="col-8 text-center mt-1">
        <h4 class="mt-4 fw-semibold">Pilihan Paket Langganan</h4>
        <span>Kami menawarkan beragam paket langganan yang fleksibel dan sesuai dengan berbagai tingkat pengguna.</span>
    </div>

    <div class="col d-flex justify-content-center pt-4">
       <ul class="nav nav-pills border border-1 rounded p-2" role="tablist">
            <li class="nav-item" role="presentation">
                <a role="tab" class="nav-link active" id="buy-tab" 
                    data-bs-toggle="tab" data-bs-target="#buy-pane" aria-controls="buy-pane" aria-selected="true">Akses Selamanya</a>
            </li>
            <li class="nav-item" role="presentation">
            <a role="tab" class="nav-link" id="subscribe-tab" 
                data-bs-toggle="tab" data-bs-target="#subscribe-pane" aria-controls="buy-pane" aria-selected="false">Langganan Bulanan</a>
            </li>
       </ul>
    </div>

    <div class="col">
        <div class="tab-content">
            <!-- tab beli langsung -->
            <div class="tab-pane fade show active" id="buy-pane">
                <div class="row justify-content-center" id="list-akses-selamanya">
                    
                </div>
            </div>
            <!-- tab subscribe -->
            <div class="tab-pane fade" id="subscribe-pane">
                <div class="row justify-content-center row-cols-1 row-cols-md-3 g-4" id="list-akses-bulanan">
                    <!-- ROW 1 -->
                    
                </div>
            </div>
        </div>
    </div>
    
</section>
