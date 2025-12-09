<style>
	.star.active {
		color: gold;
	}
</style>

<section class="explore-section section-padding" id="section_2">

	<input type="hidden" id="ebook_id" value="<?= $ebook_id ?>" />

	<script id="ebook-detail" type="application/json">
		<?= json_encode($book) ?>
	</script>


	<?php if (isset($_SESSION['error'])) : ?>
		<div class="alert alert-danger">
			<?php
			echo $_SESSION['error']['message'];
			unset($_SESSION['error']);
			?>

		</div>
	<?php endif; ?>

	<h4>Detail Buku</h4>

	<!-- section search -->
	<div class="row mt-4 h-25">

		<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
			<!-- <img class="img-thumbnail" src="<? //= $book['from_api'] == 1 ? html_escape($book['cover_img']) : base_url('assets/images/ebooks/cover/'.$book['cover_img']) 
													?>"/> -->
			<figure class="d-flex flex-nowrap w-full">
				<div class="overflow-hidden rounded d-inline-block shadow">
					<img id="thumbnail-main" class="img-fluid img-thumbnail" v-bind:src="currentCover" alt="Cover Image" />
				</div>
				<figcaption class="d-flex flex-column justify-content-around ps-4">
					<image-item :image="ebook" :index="1" @select="currentCover = $event"></image-item>
					<image-item :image="ebook" :index="2" @select="currentCover = $event"></image-item>
					<image-item :image="ebook" :index="3" @select="currentCover = $event"></image-item>
					<image-item :image="ebook" :index="4" @select="currentCover = $event"></image-item>
				</figcaption>
			</figure>
		</div>
		<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

			<div class="row" style="color: #74788D; font-size: 12px;">
				<div class="col-4">
					<p>Total Halaman Dibaca</p>
					<p class="text-primary d-inline-block py-1 px-2 rounded-3" style="background: #EBE5FC;">{{ (ebook.total_pages * ebook.reading_progress / 100).toFixed(0) }} Halaman</p>
				</div>
				<div class="col-4">
					<p>Statistik Penyelesaian</p>
					<p class="text-primary d-inline-block py-1 px-2 rounded-3" style="background: #EBE5FC;">{{ ebook.reading_progress }} %</p>
				</div>
				<div class="col-4">
					<p>Masa Aktif Ebook</p>
					<p class="text-primary d-inline-block py-1 px-2 rounded-3" style="background: #EBE5FC;">{{ ebook.subscribe_type }}</p>
				</div>
			</div>

			<p class="fs-6 fw-semibold publisher_name" style="color: #74788D; font-size: 14px;">{{ ebook.publisher_name }}</p>

			<h4 class="mb-4" style="line-height: 30px;" id="ebook_title">{{ ebook.title }}</h4>

			<div class="d-flex mx-0 pt-3 button-group-container">

				<a id="beri-ulasan" href="<?= html_escape(base_url('ebook/open_book?id=' . $ebook_id)) ?>" v-if="ebook.reading_progress > 50" class="btn btn-lg btn-clear border-primary w-100 me-2" data-bs-toggle="modal" data-bs-target="#reviewModal">Beri Ulasan</a>

				<a :href="`ebook/open_book?id=${ebook.ebook_id}&my_ebook_id=${ebook.id}${ebook.read_status == 1 ? '&continue=true' : ''}`" class="btn btn-lg btn-primary w-100">
					Baca Ebook
				</a>

				<div class="dropdown">
					<button class="btn btn-light border-primary rounded-3 ms-2 h-100" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 45px;"><i class="bi bi-three-dots"></i></button>
					<ul class="dropdown-menu">
						<li class="border-bottom"><a class="dropdown-item py-2" data-bs-toggle="modal" data-bs-target="#refundModal"><i class="bi bi-coin text-danger"> </i> Ajukan Refund</a></li>
						<li class=""><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#stopSubscribeModal"><i class="bi bi-dash-circle text-danger"> </i> Hentikan Berlangganan</a></li>
					</ul>
				</div>

			</div>

			<h5 class="mt-5 mb-3">Detail</h5>
			<div class="row row-cols-3">
				<div class="col mb-3">
					<h6 class="text-title mb-1">Penerbit</h6>
					<span class="fs-6 fw-semibold publisher_name">{{ ebook.publisher_name }}</span>
				</div>
				<div class="col mb-2">
					<h6 class="text-title mb-0">ISBN</h6>
					<span class="fs-6 fw-semibold" id="isbn">{{ ebook.isbn }}</span>
				</div>
				<div class="col mb-2">
					<h6 class="text-title mb-0">Halaman</h6>
					<span class="fs-6 fw-semibold" id="total_pages">{{ ebook.total_pages }}</span>
				</div>
				<!-- <div class="col mb-2">
					<h6 class="text-title mb-0">Jenjang Pendidikan</h6>
					<span class="fs-6 fw-semibold"><?= html_escape(rtrim($book["class_level_name"], "-")) ?></span>
				</div> -->
				<div class="col mb-2">
					<h6 class="text-title mb-0">Tahun Terbit</h6>
					<span class="fs-6 fw-semibold" id="publish_year">{{ ebook.publish_year }}</span>
				</div>

			</div>
			<h5 class="mt-5 mb-3">Deskripsi</h5>
			<p>
				<span class="desc-content text-justify" id="description">
					<!-- Deskripsi buku akan dimuat di sini -->
					{{ ebook.description }}
				</span>
				<a role="button" id="open-content" class="d-none"><small>Baca Selengkapnya</small></a>
				<br />
				<a role="button" id="close-content" class="d-none"><small>Tutup Kembali</small></a>
			</p>
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

</section>

<!-- Modal Stop Langganan -->
<!-- Modal -->
<div class="modal fade" id="stopSubscribeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="stopSubscribeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content rounded-4 p-3" style="width: 400px;">

			<div class="modal-body text-start">
				<div class="my-3 d-flex">
					<div class="img-fluid img-fluid rounded-circle text-center" style="width: 48px; height: 48px; background-color: #F8D7DA; align-content: center;">
						<i class="bi bi-dash-circle-fill text-danger" style="font-size: 24px;"></i>
					</div>
				</div>
				<h5 style="font-size: 18px; margin-bottom: 10px;">Berhenti Berlangganan?</h5>
				<p style="font-size: 14px; color: #74788D;">Kamu akan kehilangan akses ebook ini jika memberhentikan langgananmu</p>
			</div>

			<div class="row p-4">
				<div class="col-6">
					<button type="button" class="btn btn-primary rounded-3 text-primary bg-light w-100" data-bs-dismiss="modal">Batalkan</button>
				</div>
				<div class="col-6">
					<button type="button" class="btn btn-danger text-white rounded-3 w-100">Ya, Hentikan</button>
				</div>
			</div>

		</div>
	</div>
</div>

<!-- Modal Ajukan Refund -->
<div class="modal fade" id="refundModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="refundModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h1 class="modal-title fs-5" id="refundModalLabel">Pengajuan Refund</h1>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="">

					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label">Pilih Alasan Refund <span class="text-danger">*</span></label>
						<select class="form-select" id="refund_reason" name="refund_reason" required>
							<option value="" selected disabled>Pilih Alasan Refund</option>
							<option value="1">Ebook tidak sesuai dengan deskripsi</option>
							<option value="2">Ebook tidak dapat diakses</option>
							<option value="3">Lainnya</option>
						</select>
					</div>

					<div class="mb-3">
						<label for="description" class="form-label">Deskripsi Alasan Refund <span class="text-danger">*</span></label>
						<textarea class="form-control" id="description" rows="3" required></textarea>
					</div>

					<div class="mb-3">
						<label for="file" class="form-label">Bukti Refund (Opsional)</label>
						<input class="form-control" type="file" id="file">
					</div>

				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary">Ajukan Refund</button>
			</div>
		</div>
	</div>
</div>
