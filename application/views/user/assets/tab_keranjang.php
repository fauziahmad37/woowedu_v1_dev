<form class="m-0 p-0" x-data="{selectall: false}">
	<section class="d-flex mb-3 align-items-center flex-nowrap">
		<h4>Keranjang</h4>
		<span class=" ms-auto text fs-6 cart-count">0</span>
	</section>

	<div class="card w-100 flex-row align-items-center py-3 px-4">
		<span class="form-check form-check-inline">
			<input type="checkbox" id="select-all-cart" x-bind="SelectAll" class="form-check-input" />
			<label for="select-all-cart" class="fs-6 fw-semibold"><small>Pilih Semua Buku</small></label>
		</span>
		<a role="button" class="text-decoration-none text-danger ms-auto">
			<i class="bi bi-trash fs-5 delete-all-cart"></i>
		</a>
	</div>

	<div class="p-0 m-0 mt-4">
		<div id="cart-ebook-container">
			<!-- <div class="card w-100 flex-row align-items-center p-3 mb-3">
            <ul class="list-group list-group-flush w-100">
                <li class="list-group-item d-flex align-items-center">
                    <span class="form-check align-middle">
                        <input type="checkbox" class="form-check-input" x-bind="SelectAllPublisher" :id="'select-all-' + idx">
                        <label :for="'select-all-' + idx" class="form-check-label fw-semibold"><small x-text="ebook.publisher_name"></small></label>
                    </span>

                    <a role="button" class="text-decoration-none text-danger ms-auto">
                        <i class="bi bi-trash fs-5"></i>
                    </a>
                </li>
                <template x-for="(item, idx) in ebook.books">
                <li class="list-group-item d-flex align-items-baseline">

                    <div class="d-flex align-self-start my-2">
                        <span class="form-check align-middle">
                            <input type="checkbox" class="form-check-input" :value="item.id" name="book_id[]" :id="'select-all-' + idx">
                        </span>
                    </div>

					<div id="list-item-shopping-cart-ebook" class="d-flex align-items-center w-100">
						

					</div>

                    <figure class="d-flex ms-1 my-2" x-data="{cover: []}" x-init="cover = item.cover_img.split('.')">
                        <img :src="'<?= base_url() ?>assets/images/ebooks/cover/' + (cover[0]).trim() + '_thumb.' + cover[1]" 
                             class="cart-cover rounded shadow-sm" alt="" >
                        <figcaption class="d-flex flex-column ms-2">
                            <span class="fw-semibold fs-6 lh-base" x-text="item.title"></span>
                            <span class="text-danger d-inline-flex align-items-center mt-2">
                                <span class="px-2 py-1 lh-base rounded-pill bg-danger-subtle">10%</span>
                                <i class="ms-1 text-decoration-line-through">Rp. 150.000</i>
                            </span>
                            <h4 class="mt-auto fw-semibold">Rp. 85.000</h4>
                        </figcaption>
                    </figure>
                    <div class="ms-auto d-flex my-2 align-self-end">
                        <a role="checkbox"  class="mx-2 fs-5" aria-checked="false">
                            <i class="bi bi-heart"></i>
                        </a>
                        <a role="checkbox" class="mx-2 fs-5" aria-checked="false">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </li>
                </template>
            </ul>
       		 </div> -->
		</div>

		<div id="total-cart-container" class="border rounded-3 p-3 mt-5">
			<h5>Ringkasan Belanja</h5>
			<hr>

			<div class="d-flex justify-content-between mb-2">
				<span class="fw-semibold">Total Barang</span>
				<span class="text-end total-barang">0</span>
			</div>

			<div class="d-flex justify-content-between mb-3">
				<span class="fw-semibold">Total Harga</span>
				<span class="text-end total-harga">Rp 0</span>
			</div>

			<button type="button" class="btn btn-primary w-100 checkout">Checkout</button>
		</div>
	</div>
</form>
