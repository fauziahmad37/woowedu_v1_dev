<style>
	.star.active {
		color: gold;
	}
</style>

<div class="row mt-4 mb-3">
	<div class="col">
		<h5 class="fw-bold">Riwayat Pembelian</h5>
	</div>
	<div class="col text-end">
		<h6 class="count-riwayat-pembelian"></h6>
	</div>
</div>

<div id="filter-riwayat-pembelian" class="mb-3">
	<div class="row">

		<!-- select status transaksi -->
		<div class="col-12 col-md-4">
			<select class="form-select" id="filter-status-pembelian">
				<option value="">Semua Status</option>
				<option value="pending">Pending</option>
				<option value="settlement">Berhasil</option>
				<option value="failed">Gagal</option>
			</select>
		</div>

		<!-- input pilih tanggal awal dan akhir -->
		<div class="col-12 col-md-8 d-flex">
			<div class="input-group">
				<input type="date" class="form-control" id="filter-tanggal-awal" placeholder="Tanggal Awal">
				<span class="input-group-text">s.d.</span>
				<input type="date" class="form-control" id="filter-tanggal-akhir" placeholder="Tanggal Akhir">
			</div>

			<button class="ms-2 btn btn-primary" id="btn-cari-riwayat-pembelian">Cari</button>
			<button class="ms-2 btn border-primary text-primary btn-light" id="btn-reset-riwayat-pembelian">Reset</button>
		</div>

		<!-- button Cari -->



	</div>
</div>

<div id="list-riwayat-pembelian-container">
</div>

<div class="text-center mt-3">
	<button class="btn btn-primary" id="show-more-riwayat">Muat Lebih Banyak</button>
</div>

<!-- Modal Detail Transaksi -->
<div class="modal fade" id="detailTransaksi" tabindex="-1" aria-labelledby="detailTransaksiLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h1 class="modal-title fs-5" id="detailTransaksiLabel">Detail Transaksi</h1>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<h6 class="mb-3 fw-bold mt-3">Informasi Pembelian</h6>
				<div class="row fs-12 mb-3">
					<div class="col" style="color: #4D505E;">No. Invoice</div>
					<div class="col fw-bold text-end invoice-number"></div>
				</div>
				<div class="row fs-12 mb-3">
					<div class="col" style="color: #4D505E;">Tanggal Pembelian</div>
					<div class="col fw-bold text-end tanggal-pembelian"></div>
				</div>

				<hr>

				<h6 class="mb-3 fw-bold mt-4">Rincian Pembayaran</h6>
				<div class="row fs-12 mb-3">
					<div class="col" style="color: #4D505E;">Total Ebook</div>
					<div class="col fw-bold text-end total-ebook"></div>
				</div>
				<div class="row fs-12 mb-3">
					<div class="col" style="color: #4D505E;">Total Harga</div>
					<div class="col fw-bold text-end total-harga"></div>
				</div>
				<div class="row fs-12 mb-3">
					<div class="col" style="color: #4D505E;">Biaya Admin</div>
					<div class="col fw-bold text-end amount-admin"></div>
				</div>
				<div class="row fs-12 mb-3">
					<div class="col" style="color: #4D505E;">Kupon & Voucher - <span class="voucher-name"></span></div>
					<div class="col fw-bold text-end amount-voucher"></div>
				</div>
				<div class="row fs-12 mb-3">
					<div class="col" style="color: #4D505E;">Promo</div>
					<div class="col fw-bold text-end amount-promo"></div>
				</div>

				<hr>

				<div class="row mb-3 mt-3">
					<div class="col">
						<h5 class="fw-bold">Total Pembelian</h5>
					</div>
					<div class="col text-end total-amount h5 fw-bold"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Beri Ulasan -->
<div class="modal fade" id="reviewModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="reviewModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h1 class="modal-title fs-5" id="reviewModalLabel">Beri Ulasan</h1>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">


				<div class="d-flex my-4">
					<img class="rounded-2 cover-img" v-bind:src="ebook.cover_img" alt="" height="120" width="80">
					<div class="ms-3">
						<p class="mb-2" style="color: #74788D; font-size: 12px;">{{ ebook.publisher_name }}</p>
						<h5 class="mb-2" id="ebook_title">{{ ebook.title }}</h5>

						<div class="give-star">
							<span class="star" style="font-size: 24px;" v-for="star in 5" :class="{'active': star <= ebook.rating}" @click="setRating(star)">&#9733;</span>
							<span class="ms-2" style="color: #74788D;">Bagaimana Kualitas Ebook ini?</span>
						</div>

					</div>
				</div>

				<div class="mb-3">
					<label for="review" class="form-label">Ulasan <span class="text-danger">*</span></label>
					<textarea class="form-control" id="review" rows="3" required></textarea>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" @click="submitReview">Kirim Ulasan</button>
			</div>
		</div>
	</div>
</div>
