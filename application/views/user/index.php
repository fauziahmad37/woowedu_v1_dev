<style>
	.profile-image {
		margin-top: -40px;
		margin-left: 15px;
		object-fit: cover;
	}

	.camera-button {
		position: absolute;
		margin-top: 35px;
		left: 130px;
		background-color: #D4D1E9;
		border-radius: 50%;
		width: 40px;
		height: 40px;
		display: flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		z-index: 10;
		/* Make sure it's above the input */
	}

	.hidden-file-input {
		display: none;
	}

	.carousel-control-next,
	.carousel-control-prev

	/*, .carousel-indicators */
		{
		filter: invert(100%);
	}

	/* pendekin tinggi button carousel supaya ketika form tepi kiri or kanan di klik tidak ikut geser carousel nya */
	button.carousel-control-prev,
	button.carousel-control-next {
		height: 50px;
	}
</style>

<?php
if ($user_data['user_level'] == 4) {
	$noInduk = $user_data['nis'];
} elseif ($user_data['user_level'] == 3 || $user_data['user_level'] == 6) {
	$noInduk = $user_data['nik'];
} else {
	$noInduk = '-';
}
?>

<section class="explore-section section-padding" id="section_2">
	<input type="hidden" name="menu" value="<?= $menu ?>">
	<input type="hidden" name="tab" value="<?= $tab ?>">

	<div class="container mt-5">
		<div class="col-12 text-center">
			<!-- <h4 class="mb-4">Profile Saya</h4> -->
			<img class="w-100 rounded-4" src="assets/images/poster-profile.png" alt="" height="200">
		</div>

		<div class="container position-relative d-inline-block">

			<img class="border border-light border-2 rounded-circle profile-image position-absolute" id="previewImg" src="<?= isset($user_data['photo']) ? base_url('assets/images/users/' . $user_data['photo']) : base_url('assets/images/user.png') ?>" width="140" height="140" alt="Profile Image"><br>
			<div class="camera-button" onclick="document.getElementById('userfile').click();">
				<i class="bi bi-camera" style="font-size:20px;"></i>
			</div>

		</div>

		<div class="profile-mobile-content">
			<?php $this->load->view('user/user_mobile_content', ['noInduk' => $noInduk, 'user_data' => $user_data, 'teachers' => $teachers]); ?>
		</div>

		<div class="container d-flex mt-5 mb-5 profile-desktop-section" style="margin-top: 120px !important;">

			<div class="col-lg-3 col-md-4">
				<div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
					<button class="nav-link active" id="v-pills-info-dasar-tab" data-bs-toggle="pill" data-bs-target="#v-pills-info-dasar" type="button" role="tab" aria-controls="v-pills-info-dasar" aria-selected="true">Informasi Dasar</button>

					<!-- JIKA USER LEVEL MURID -->
					<?php if ($user_data['user_level'] == 4) : ?>
						<button class="nav-link" id="v-pills-wali-akun-tertaut-tab" data-bs-toggle="pill" data-bs-target="#v-pills-wali-akun-tertaut" type="button" role="tab" aria-controls="v-pills-wali-akun-tertaut" aria-selected="false">Wali Akun Tertaut</button>
						<button class="nav-link" id="v-pills-pengajar-tab" data-bs-toggle="pill" data-bs-target="#v-pills-pengajar" type="button" role="tab" aria-controls="v-pills-pengajar" aria-selected="false">Pengajar</button>
					<?php endif ?>

					<!-- JIKA USER LEVEL ORTU -->
					<?php if ($user_data['user_level'] == 5) : ?>
						<button class="nav-link" id="v-pills-tautan-akun-anak-tab" data-bs-toggle="pill" data-bs-target="#v-pills-tautan-akun-anak" type="button" role="tab" aria-controls="v-pills-tautan-akun-anak" aria-selected="false">Tautan Akun Anak</button>
					<?php endif ?>

					<button class="nav-link" id="v-pills-change-password-tab" data-bs-toggle="pill" data-bs-target="#v-pills-change-password" type="button" role="tab" aria-controls="v-pills-change-password" aria-selected="false">Kata Sandi</button>
					<button class="nav-link" id="v-pills-laporan-ebook-tab" data-bs-toggle="pill" data-bs-target="#v-pills-laporan-ebook" type="button" role="tab" aria-controls="v-pills-laporan-ebook" aria-selected="false">Laporan Ebook</button>
				</div>
			</div>

			<div class="col-lg-9 col-md-8 px-2" style="border-left: 2px solid #dee2e6;">
				<div class="tab-content w-100 px-3" id="v-pills-tabContent">
					<div class="tab-pane fade show active" id="v-pills-info-dasar" role="tabpanel" aria-labelledby="v-pills-info-dasar-tab" tabindex="0">
						<form id="student-profile" name="student-profile" action="">

							<input type="hidden" id="user_id" name="user_id" value="<?= $user_data['userid'] ?>">
							<input type="hidden" id="user_lavel" name="user_level" value="<?= $user_data['user_level'] ?>">
							<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

							<div class="user-img mb-5 d-none">
								<div class="custom-file">
									<!-- <img class="rounded" id="previewImg" src="<? //= isset($user_data['photo']) ? base_url('assets/images/users/'.$user_data['photo']) : base_url('assets/images/user.png') 
																					?>" alt="" width="200"><br>	 -->
									<input class="mt-2" type="file" id="userfile" name="userfile">
								</div>
							</div>

							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="username" class="form-label">Username </label>
								<input readonly type="text" class="form-control" name="username" value="<?= isset($user_data['username']) ? $user_data['username'] : '' ?>">
							</div>

							<!-- NAMA LENGKAP - JIKA USER LEVEL MURID -->
							<?php if ($user_data['user_level'] == 4) : ?>
								<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
									<label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
									<input type="text" required readonly class="form-control" id="full_name" name="full_name" value="<?= isset($user_data['student_name']) ? $user_data['student_name'] : '' ?>">
								</div>
							<?php endif ?>

							<!-- NAMA LENGKAP - JIKA USER LEVEL GURU -->
							<?php if ($user_data['user_level'] == 3 || $user_data['user_level'] == 6) : ?>
								<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
									<label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
									<input type="text" required readonly class="form-control" id="full_name" name="full_name" value="<?= isset($user_data['teacher_name']) ? $user_data['teacher_name'] : '' ?>">
								</div>
							<?php endif ?>

							<!-- NAMA LENGKAP - JIKA USER LEVEL ORTU -->
							<?php if ($user_data['user_level'] == 5) : ?>
								<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
									<label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
									<input type="text" required readonly class="form-control" id="full_name" name="full_name" value="<?= isset($user_data['name']) ? $user_data['name'] : '' ?>">
								</div>
							<?php endif ?>


							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
								<input type="text" required readonly class="form-control" id="gender" name="gender" value="<?= isset($user_data['gender']) ? $user_data['gender'] : '' ?>">
							</div>



							<!-- NIS - JIKA USER LEVEL MURID -->
							<?php if ($user_data['user_level'] == 4) { ?>
								<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
									<label for="nis" class="form-label">Nomor Induk Siswa</label>
									<input type="text" class="form-control" id="nis" name="nis" value="<?= isset($user_data['nis']) ? $user_data['nis'] : '' ?>" readonly>
								</div>
							<?php } ?>

							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="email" class="form-label">Email</label>
								<input type="email" class="form-control" id="email" name="email" value="<?= isset($user_data['email']) ? $user_data['email'] : '' ?>">
								<span style="font-size: 12px;">Ini adalah alamat email utama Anda dan akan digunakan untuk mengirim email pemberitahuan</span>
							</div>

							<!-- ALAMAT - JIKA USER LEVEL MURID -->
							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="address" class="form-label">Alamat</label>
								<input type="text" class="form-control" id="address" name="address" value="<?= isset($user_data['address']) ? $user_data['address'] : '' ?>">
							</div>


							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="school" class="form-label">Sekolah</label>
								<input type="text" class="form-control" id="school" name="school" readonly value="<?= isset($user_data['sekolah_nama']) ? $user_data['sekolah_nama'] : '' ?>">
							</div>

							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="phone" class="form-label">Nomor Telepon</label>
								<input type="text" class="form-control" id="phone" name="phone" placeholder="Nomor Telepon" value="<?= isset($user_data['phone']) ? $user_data['phone'] : '' ?>">
							</div>

							<div class="mb-3">
								<button class="btn btn-primary text-white" name="save-profile">Simpan Perubahan</button>
							</div>
						</form>

					</div>

					<div class="tab-pane fade" id="v-pills-wali-akun-tertaut" role="tabpanel" aria-labelledby="v-pills-wali-akun-tertaut-tab" tabindex="0">

						<form id="parent-profile" name="frm-wali-akun-tertaut" action="">

							<input type="hidden" id="user_id" name="user_id" value="<?= $user_data['userid'] ?>">
							<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="parent_name" class="form-label">Nama Orang Tua</label>
								<input type="text" class="form-control" id="parent_name" name="parent_name" value="<?= isset($parent['name']) ? $parent['name'] : '' ?>" readonly>
							</div>

							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="parent_email" class="form-label">Email</label>
								<input type="email" class="form-control" id="parent_email" name="parent_email" placeholder="Masukan email orang tua" value="<?= isset($parent['email']) ? $parent['email'] : '' ?>">
							</div>

							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="parent_phone" class="form-label">Nomor Telepon Orang Tua</label>
								<input type="text" class="form-control" id="parent_phone" name="parent_phone" placeholder="Nomor Telepon Orang Tua" value="<?= isset($parent['phone']) ? $parent['phone'] : '' ?>">
							</div>

							<div class="mb-3">
								<button class="btn btn-primary text-white" type="submit" name="save-parent">Simpan Perubahan</button>
							</div>
						</form>

					</div>

					<div class="tab-pane fade" id="v-pills-pengajar" role="tabpanel" aria-labelledby="v-pills-pengajar" tabindex="0">
						<table class="table table-rounded w-100">
							<thead class="bg-primary text-white">
								<tr>
									<th>No</th>
									<th>Nama Guru</th>
								</tr>
							</thead>
							<tbody>
								<?php $no = 1;
								foreach ($teachers as $teacher) : ?>
									<tr>
										<td><?= $no ?></td>
										<td><?= $teacher['teacher_name'] ?></td>
									</tr>
								<?php
									$no++;
								endforeach ?>
							</tbody>
						</table>
					</div>

					<div class="tab-pane fade" id="v-pills-tautan-akun-anak" role="tabpanel" aria-labelledby="v-pills-tautan-akun-anak-tab" tabindex="0">
						<div class="container p-0" style="text-align: right;">
							<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahAnakModal">
								Tambahkan Anak
							</button>
						</div>
						<table id="table-tautan-anak" class="table border">
							<thead>
								<tr>
									<th>Anak</th>
									<th>Email</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody id="table-body-student-list">
								<?php if (isset($students)) : ?>
									<?php foreach ($students as $key => $value) { ?>
										<tr>
											<td><input type="hidden" value="<?= $value['nis'] ?>"> <?= $value['student_name'] ?></td>
											<td><?= $value['email'] ?></td>
											<td><button class="btn btn-sm btn-clear border" onclick="deleteStudent(this)"><i class="bi bi-trash3-fill"></i></button></td>
										</tr>
									<?php } ?>
								<?php endif ?>
							</tbody>
						</table>
					</div>

					<div class="tab-pane fade" id="v-pills-change-password" role="tabpanel" aria-labelledby="v-pills-change-password-tab" tabindex="0">
						<form name="form-change-password" action="">
							<div class="mb-3 col-lg-6 col-md-10 col-sm-12 col-xs-12">
								<label for="old_password" class="form-label">Kata Sandi</label>
								<input type="hidden" name="user_id" value="<?= $user_data['userid'] ?>">
								<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

								<input type="password" class="form-control" id="old_password" name="old_password" placeholder="Masukan sandi saat ini" required>
							</div>

							<div class="mb-3 col-lg-6 col-md-10 col-sm-12 col-xs-12">
								<label for="new_password" class="form-label">Kata Sandi Baru</label>
								<input type="password" class="form-control" id="new_password" name="new_password" placeholder="Masukan sandi baru" required>
							</div>

							<div class="mb-3 col-lg-6 col-md-10 col-sm-12 col-xs-12">
								<label for="confirm_password" class="form-label">Konfirmasi Kata Sandi</label>
								<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Masukan kembali sandi" required>
							</div>

							<div class="mb-3">
								<button class="btn btn-primary text-white" type="submit" name="change_password">Simpan Perubahan</button>
							</div>
						</form>

					</div>

					<div class="tab-pane fade" id="v-pills-laporan-ebook" role="tabpanel" aria-labelledby="v-pills-laporan-ebook-tab" tabindex="0">
						<?php $this->load->view('user/laporan_ebook'); ?>
					</div>

				</div>
			</div>

		</div>

	</div>

</section>

<!-- MODAL TAMBAH ANAK -->
<div class="modal fade" id="tambahAnakModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Hubungkan dengan akun anak</h1>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body text-center">
				<p>Masukkan Nomor Induk Siswa agar Bapak/Ibu dapat terhubung dengan akun anak.</p>
				<input type="text" class="form-control" id="nis" name="nis">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-bs-dismiss="modal" name="simpan-siswa">Simpan</button>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	$(document).ready(function() {
		// CEK MENU DAN TAB
		let menu = $('input[name="menu"]').val();
		let tab = $('input[name="tab"]').val();

		if (menu == 'laporan_ebook') {
			// JIKA MENU LAPORAN EBOOK MAKA TAMPILKAN TAB LAPORAN EBOOK
			$('#v-pills-laporan-ebook-tab').click();
		} else if (menu == 'riwayat_pembelian') {
			// JIKA MENU RIWAYAT PEMBELIAN MAKA TAMPILKAN TAB RIWAYAT PEMBELIAN
			$('#v-pills-laporan-ebook-tab').click();
			$('#nav-riwayat-pembelian-tab').click();
		} else if (menu == 'wishlist') {
			// JIKA MENU WISHLIST MAKA TAMPILKAN TAB WISHLIST
			$('#v-pills-laporan-ebook-tab').click();
			$('#nav-wishlist-tab').click();
		} else if (menu == "cart") {
			// JIKA MENU CART MAKA TAMPILKAN TAB CART
			$('#v-pills-laporan-ebook-tab').click();
			$('#nav-keranjang-tab').click();
		}
	});


	$('#userfile').bind('change', function() {
		// JIKA FILE MELEBIHI 1 MB MAKA FILE TIDAK AKAN DI UPLOAD

		if (this.files != undefined) {
			if (this.files[0].size > 1000000) {
				alert('Ukuran file melebihi 1MB, silahkan pilih file yang lebih kecil');
				this.value = '';
				$('#previewImg').attr('src', '<?= base_url() ?>/assets/images/transparent.jpg');
			} else {
				var file = $("#userfile").get(0).files[0];
				if (file) {
					var reader = new FileReader();
					reader.onload = function() {
						$("#previewImg").attr("src", reader.result);
					}
					reader.readAsDataURL(file);
				}
			}
		}
	});

	// BUTTON SIMPAN PROFILE
	$('form[name="student-profile"]').on('submit', function(e) {
		e.preventDefault();
		let form = new FormData(e.target);
		$.ajax({
			type: "POST",
			url: BASE_URL + 'user/store',
			data: form,
			contentType: false,
			processData: false,
			success: function(response) {
				let res = JSON.parse(response);
				if (res.success) {
					Swal.fire({
						icon: 'success',
						title: '<h4 class="text-success"></h4>',
						html: `<span class="text-success">${res.message}</span>`,
						timer: 5000
					});

					setTimeout(() => {
						window.location.href = BASE_URL + 'user';
					}, 1500);
				}
			}
		});
	});

	// BUTTON UBAH PASSWORD
	$('form[name="form-change-password"]').on('submit', function(e) {
		e.preventDefault();
		// let formData = $('form[name="form-change-password"]');
		let form = new FormData(e.target);
		// tambahkan token csrf
		form.append('csrf_token_name', $('input[name="csrf_token_name"]').val());
		$.ajax({
			type: "POST",
			url: BASE_URL + 'user/change_password',
			data: form,
			contentType: false,
			processData: false,
			success: function(res) {
				if (res.success == true) {
					Swal.fire({
						icon: 'success',
						title: '<h4 class="text-success"></h4>',
						html: `<span class="text-success">${res.message}</span>`,
						timer: 5000
					});
				} else {
					let message;
					if (res.message.old_password) message = res.message.old_password
					if (res.message.new_password) message = res.message.new_password
					if (res.message.confirm_password) message = res.message.confirm_password

					Swal.fire({
						icon: 'error',
						title: '<h4 class="text-warning"></h4>',
						html: `<span class="text-warning">${message}</span>`,
						timer: 5000
					});
				}

				// set token
				$('input[name="csrf_token_name"]').val(res.token);
			}
		});
	});

	// BUTTON SIMPAN DATA ORANG TUA
	$('form[name="frm-wali-akun-tertaut"]').on('submit', function(e) {
		e.preventDefault();
		let form = new FormData(e.target);
		// tambahkan token csrf
		form.append('csrf_token_name', $('input[name="csrf_token_name"]').val());

		$.ajax({
			type: "POST",
			url: BASE_URL + 'user/store_parent',
			data: form,
			contentType: false,
			processData: false,
			success: function(res) {
				if (res.success == true) {
					Swal.fire({
						icon: 'success',
						title: '<h4 class="text-success"></h4>',
						html: `<span class="text-success">${res.message}</span>`,
						timer: 5000
					});
				} else {
					Swal.fire({
						icon: 'error',
						title: '<h4 class="text-danger"></h4>',
						html: `<span class="text-danger">${JSON.stringify(res.message)}</span>`,
						timer: 5000
					});
				}

				// set token
				$('input[name="csrf_token_name"]').val(res.token);
			},
			error(err) {
				let response = JSON.parse(err.responseText);

				Swal.fire({
					type: response.err_status,
					title: '<h5 class="text-danger text-uppercase">' + response.err_status + '</h5>',
					html: response.message
				});
			}
		});
	});

	// BUTTON SIMPAN TAUTAN SISWA
	$('button[name="simpan-siswa"]').on('click', function() {
		let nis = $('#tambahAnakModal #nis').val();
		let userId = $('#student-profile #user_id').val();

		$.ajax({
			type: "POST",
			url: BASE_URL + "user/store_tautan_anak",
			data: {
				nis: nis,
				userId: userId,
				csrf_token_name: $('input[name="csrf_token_name"]').val()
			},
			dataType: "JSON",
			success: function(res) {
				if (res.success == true) {
					Swal.fire({
						icon: 'success',
						title: '<h4 class="text-success"></h4>',
						html: `<span class="text-success">${res.message}</span>`,
						timer: 5000
					});

					// reload page after 2 seconds
					setTimeout(() => {
						location.reload();
					}, 2000);

				} else {
					Swal.fire({
						icon: 'error',
						title: '<h4 class="text-warning"></h4>',
						html: `<span class="text-warning">${res.message}</span>`,
						timer: 5000
					});
				}

				// set token
				$('input[name="csrf_token_name"]').val(res.token);
			}
		});
	});

	// BUTTON DELETE STUDENT DI KLIK
	const deleteStudent = function(e) {
		let nis = e.parentElement.parentElement.children[0].children[0].value;

		$.ajax({
			type: "POST",
			url: BASE_URL + "user/delete_tautan_anak",
			data: {
				nis: nis,
				csrf_token_name: $('input[name="csrf_token_name"]').val()
			},
			dataType: "JSON",
			success: function(response) {
				if (response.success == true) {
					Swal.fire({
						icon: 'success',
						title: '<h4 class="text-success"></h4>',
						html: `<span class="text-success">${response.message}</span>`,
						timer: 5000
					});

					// remove row table
					e.parentElement.parentElement.remove();

					// set token
					$('input[name="csrf_token_name"]').val(response.token);
				} else {
					Swal.fire({
						icon: 'error',
						title: '<h4 class="text-warning"></h4>',
						html: `<span class="text-warning">${JSON.stringify(response.message)}</span>`,
						timer: 5000
					});
				}
			}
		});
	};
</script>
