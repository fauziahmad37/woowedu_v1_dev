<style>
	.list-ebook {
		display: none;
	}

	.group-ebook-saya .card>img {
		width: 211px;
		height: 300px;
	}

	.cover-kadaluarsa {
		border-radius: 7px;
		background-color: rgba(0, 0, 0, 0.7);
		position: absolute;
		top: 7px;
		height: 300px;
		width: 211px;
		text-align: center;
		left: 10px;
	}

	.cover-kadaluarsa button {
		margin-top: 125px;
	}
</style>

<div class="row mt-5">
	<div class="col-6">
		<h5 class="fw-bold">Ebook Saya</h5>
	</div>
	<div class="col-6 text-end">
		(<?= count($ebook_members) ?> Ebook)
	</div>
</div>

<div class="row mt-4">
	<div class="col" id="btn-status-buku">
		<button class="btn btn-lg btn-light border-1 border-dark-subtle rounded-pill mt-2 me-2 fs-14 active" id="btn-belum-dibaca" onclick="btnStatusBuku(this)">Belum Dibaca</button>
		<button class="btn btn-lg btn-light border-1 border-dark-subtle rounded-pill mt-2 me-2 fs-14" id="btn-sedang-dibaca" onclick="btnStatusBuku(this)">Sedang Dibaca</button>
		<button class="btn btn-lg btn-light border-1 border-dark-subtle rounded-pill mt-2 me-2 fs-14" id="btn-selesai-dibaca" onclick="btnStatusBuku(this)">Selesai Dibaca</button>
	</div>
</div>

<div class="group-ebook-saya" style="">
	<div id="group-belum-dibaca" class="row flex-nowrap overflow-auto mt-5 pb-5 mx-1 list-ebook">

		<?php foreach ($ebook_members as $ebook_member) : ?>
			<?php if ($ebook_member['read_status'] == 0) : ?>
				<div class="card rounded-4 me-4 pb-3" style="width: 233px; display:inline-block; float:none;">
					<img class="img-fluid mt-2 rounded-3" src="assets/images/ebooks/cover/<?= $ebook_member['cover_img'] ?>" alt="">

					<div class="cover-kadaluarsa <?= (strtotime($ebook_member['end_activation']) < time()) ? '' : 'd-none' ?>">
						<button class="btn btn-lg fs-12 rounded-pill text-white" style="background: #74788D;">KADALUARSA</button>
					</div>

					<p class="publisher-name fs-12 mt-3 text-body-secondary">Penerbit: <?= $ebook_member['publisher_name'] ?></p>
					<p class="book-title-card mt-2 fs-16 fw-bold"><?= $ebook_member['title'] ?></p>

					<div class="row text-body-secondary">
						<div class="col-8 fs-12">Progress Membaca</div>
						<div class="col-4 fs-12 text-end">0/100%</div>
					</div>

					<div class="progress mt-2" style="margin-bottom: 60px;" role="progressbar" aria-label="Example 5px high" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
						<div class="progress-bar" style="width: 0%"></div>
					</div>
					<?php if (strtotime($ebook_member['end_activation']) < time()) : ?>
						<a href="ebook/detail/<?= $ebook_member['ebook_id'] ?>" class="btn btn-lg position-absolute mb-2 fs-12" style="bottom:5px; width: 90%; background:#D1D2D9;">Aktifkan Langganan</a>
					<?php else : ?>
						<a href="ebook/detail/<?= $ebook_member['ebook_id'] ?>" class="btn btn-primary btn-lg position-absolute mb-2 fs-12" style="bottom:5px; width: 90%;"><img src="assets/images/icons/book-open.png" width="16" alt="" class="me-2">Mulai Baca</a>
					<?php endif ?>
				</div>
			<?php endif ?>
		<?php endforeach ?>
	</div>

	<div id="group-sedang-dibaca" class="row flex-nowrap overflow-auto mt-5 pb-5 mx-1 list-ebook">
		<?php foreach ($ebook_members as $ebook_member) : ?>
			<?php if ($ebook_member['read_status'] == 1) : ?>
				<div class="card rounded-4 me-4 pb-3" style="width: 233px; display:inline-block; float:none;">
					<img class="img-fluid mt-2 rounded-3" src="assets/images/ebooks/cover/<?= $ebook_member['cover_img'] ?>" alt="">
					<p class="publisher-name fs-12 mt-3 text-body-secondary">Penerbit: <?= $ebook_member['publisher_name'] ?></p>
					<p class="book-title-card mt-2 fs-16 fw-bold"><?= $ebook_member['title'] ?></p>

					<div class="row text-body-secondary">
						<div class="col-8 fs-12">Progress Membaca</div>
						<div class="col-4 fs-12 text-end">0/100%</div>
					</div>

					<div class="progress mt-2" style="margin-bottom: 60px;" role="progressbar" aria-label="Example 5px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
						<div class="progress-bar" style="width: 25%"></div>
					</div>

					<?php if (strtotime($ebook_member['end_activation']) < time()) : ?>
						<a href="ebook/detail/<?= $ebook_member['ebook_id'] ?>" class="btn btn-lg position-absolute mb-2 fs-12" style="bottom:5px; width: 90%; background:#D1D2D9;">Aktifkan Langganan</a>
					<?php else : ?>
						<a href="ebook/detail/<?= $ebook_member['ebook_id'] ?>" class="btn btn-primary btn-lg position-absolute mb-2 fs-12" style="bottom:5px; width: 90%;"><img src="assets/images/icons/book-open.png" width="16" alt="" class="me-2">Lanjutkan Membaca</a>
					<?php endif ?>
				</div>
			<?php endif ?>
		<?php endforeach ?>
	</div>

	<div id="group-selesai-dibaca" class="row flex-nowrap overflow-auto mt-5 pb-5 mx-1 list-ebook">
		<?php foreach ($ebook_members as $ebook_member) : ?>
			<?php if ($ebook_member['read_status'] == 2) : ?>
				<div class="card rounded-4 me-4 pb-3" style="width: 233px; display:inline-block; float:none;">
					<img class="img-fluid mt-2 rounded-3" src="assets/images/ebooks/cover/<?= $ebook_member['cover_img'] ?>" alt="">
					<p class="publisher-name fs-12 mt-3 text-body-secondary">Penerbit: <?= $ebook_member['publisher_name'] ?></p>
					<p class="book-title-card mt-2 fs-16 fw-bold"><?= $ebook_member['title'] ?></p>

					<div class="row text-body-secondary">
						<div class="col-8 fs-12">Progress Membaca</div>
						<div class="col-4 fs-12 text-end">0/100%</div>
					</div>

					<div class="progress mt-2" style="margin-bottom: 60px;" role="progressbar" aria-label="Example 5px high" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
						<div class="progress-bar" style="width: 100%"></div>
					</div>
					<?php if (strtotime($ebook_member['end_activation']) < time()) : ?>
						<a href="ebook/detail/<?= $ebook_member['ebook_id'] ?>" class="btn btn-lg position-absolute mb-2 fs-12" style="bottom:5px; width: 90%; background:#D1D2D9;">Aktifkan Langganan</a>
					<?php else : ?>
						<a href="ebook/detail/<?= $ebook_member['ebook_id'] ?>" class="btn btn-primary btn-lg position-absolute mb-2 fs-12" style="bottom:5px; width: 90%;"><img src="assets/images/icons/book-open.png" width="16" alt="" class="me-2">Baca Ulang</a href="ebook/detail/<?= $ebook_member['ebook_id'] ?>">
					<?php endif ?>
				</div>
			<?php endif ?>
		<?php endforeach ?>
	</div>
</div>

<script>
	$(document).ready(function() {
		document.getElementById('group-belum-dibaca').style.display = 'flex';
	});
</script>

<script>
	function btnStatusBuku(e) {
		let buttons = document.querySelectorAll('#btn-status-buku button'); // ambil semua button status buku
		buttons.forEach(function(item, index, arr) { // lakukan looping untuk hapus class active
			item.classList.remove("active");
		});

		e.classList.add("active"); // tambahkan class active pada button yg di klik

		let listBookGroups = document.querySelectorAll('.list-ebook'); // ambil semua group list book
		listBookGroups.forEach(function(item, index, arr) {
			item.style.display = '';
		});

		if (e.id == 'btn-belum-dibaca') {
			document.getElementById('group-belum-dibaca').style.display = 'flex';
		}

		if (e.id == 'btn-sedang-dibaca') {
			document.getElementById('group-sedang-dibaca').style.display = 'flex';
		}

		if (e.id == 'btn-selesai-dibaca') {
			document.getElementById('group-selesai-dibaca').style.display = 'flex';
		}


	}

	contentSlider('#group-belum-dibaca');
	contentSlider('#group-sedang-dibaca');
	contentSlider('#group-selesai-dibaca');

	function contentSlider(element) {
		const slider = document.querySelector(element);
		let isDown = false;
		let startX;
		let scrollLeft;

		slider.addEventListener('mousedown', (e) => {
			isDown = true;
			slider.classList.add('active');
			startX = e.pageX - slider.offsetLeft;
			scrollLeft = slider.scrollLeft;
		});
		slider.addEventListener('mouseleave', () => {
			isDown = false;
			slider.classList.remove('active');
		});
		slider.addEventListener('mouseup', () => {
			isDown = false;
			slider.classList.remove('active');
		});
		slider.addEventListener('mousemove', (e) => {
			if (!isDown) return;
			e.preventDefault();
			const x = e.pageX - slider.offsetLeft;
			const walk = (x - startX) * 3; //scroll-fast
			slider.scrollLeft = scrollLeft - walk;
		});

	}

	// image drag nya di matikan untuk mengoptimalkan slider nya
	let imgFluid = document.getElementsByClassName('img-fluid');
	for (let i = 0; i < imgFluid.length; i++) {
		imgFluid[i].setAttribute('draggable', false);
	}
</script>
