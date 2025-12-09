<style>
	#v-pills-tab button.nav-link.active {
		background-color: #D4D1E9;
		color: var(--bs-primary);
		font-weight: 600;
	}

	#v-pills-tab button.nav-link {
		height: 44px;
		margin-top: 10px;
		text-align: left;
	}

	button.nav-link.active#nav-ebook-saya-tab,
	button.nav-link.active#nav-riwayat-pembelian-tab,
	button.nav-link.active#nav-wishlist-tab,
	button.nav-link.active#nav-keranjang-tab {
		border-bottom-width: 2px !important;
		border-left-width: 0px;
		border-top-width: 0px;
		border-right-width: 0px;
		border-color: var(--bs-primary);
	}

	.nav-tabs button.nav-link:hover {
		border-color: #fff;
	}

	.badge-notif-count {
		font-size: 14px;
		background-color: #D4D1E9;
		border-radius: 25px;
		padding: 3px;
		font-weight: 600;
		margin-left: 5px;
	}

	button.nav-link.active .badge-notif-count {
		background-color: var(--bs-primary);
		color: white;
	}

	#btn-status-buku button.active {
		background-color: var(--bs-primary);
		color: white;
		border: 0px;
	}

	::-webkit-scrollbar {
		width: 5px;
		height: 5px;
	}

	::-webkit-scrollbar-track {
		background-color: #e3e3e3;
	}

	::-webkit-scrollbar-thumb {
		box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
	}

	.book-title-card, .publisher-name {
		line-height: 18px;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		overflow: hidden;
	}
</style>

<div class="content-laporan-ebook">
	<nav class="d-flex justify-content-center p-0">
		<div class="nav nav-tabs w-100" id="nav-tab" role="tablist">
			<button class="fw-bold nav-link w-25 active" id="nav-ebook-saya-tab" data-bs-toggle="tab" data-bs-target="#nav-ebook-saya" type="button" role="tab" aria-controls="nav-ebook-saya" aria-selected="true"><i class="fa-regular fa-calendar-days h6"></i> Ebook Saya <span class="badge-notif-count total-buku-saya-count"></span></button>
			<button class="fw-bold nav-link w-25" id="nav-riwayat-pembelian-tab" data-bs-toggle="tab" data-bs-target="#nav-riwayat-pembelian" type="button" role="tab" aria-controls="nav-riwayat-pembelian" aria-selected="false"><i class="fa-solid fa-chalkboard-user h6"></i> Riwayat Pembelian <span class="badge-notif-count total-riwayat-pembelian-count"><?=$totalPembelian?></span></button>
			<button class="fw-bold nav-link w-25" id="nav-wishlist-tab" data-bs-toggle="tab" data-bs-target="#nav-wishlist" type="button" role="tab" aria-controls="nav-wishlist" aria-selected="false"><i class="fa-solid fa-chalkboard-user h6"></i> Wishlist <span class="badge-notif-count wishlist-count"></span></button>
			<button class="fw-bold nav-link w-25" id="nav-keranjang-tab" data-bs-toggle="tab" data-bs-target="#nav-keranjang" type="button" role="tab" aria-controls="nav-keranjang" aria-selected="false"><i class="fa-solid fa-chalkboard-user h6"></i> Keranjang <span class="badge-notif-count cart-count">0</span></button>
		</div>

	</nav>
	<div class="tab-content mb-4" id="nav-tabContent">

		<div class="tab-pane fade show active" id="nav-ebook-saya" role="tabpanel" aria-labelledby="nav-ebook-saya-tab" tabindex="0">
			<?=$this->load->view('user/assets/tab_ebook_saya', ['ebook_members' => $ebook_members], TRUE)?>
		</div>

		<div class="tab-pane fade p-3" id="nav-riwayat-pembelian" role="tabpanel" aria-labelledby="nav-riwayat-pembelian-tab" tabindex="1">
			<?=$this->load->view('user/assets/tab_riwayat_pembelian', [], TRUE)?>
		</div>

		<div class="tab-pane fade p-3" id="nav-wishlist" role="tabpanel" aria-labelledby="nav-wishlist-tab" tabindex="2">
			<?=$this->load->view('user/assets/tab_wishlist', [], TRUE)?>
		</div>
		<div class="tab-pane fade p-3" id="nav-keranjang" role="tabpanel" aria-labelledby="nav-keranjang-tab" tabindex="3">
			<?=$this->load->view('user/assets/tab_keranjang', [], TRUE)?>
		</div>

	</div>
</div>
