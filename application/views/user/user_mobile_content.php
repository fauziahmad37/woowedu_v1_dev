<div class="profile-name-section text-center">
	<p style="font-size: 24px; font-weight: 600"><?= $_SESSION['nama'] ?></p>
	<p style="font-size: 16px; line-height: 5px"><?= $noInduk ?></p>
</div>
<div class="clearfix"></div>
<div id="carouselExampleControls" class="carousel slide" data-interval="false">
	<div class="carousel-inner" style="margin-top: -50px">
		<div class="carousel-item text-center active">
			<div class="d-block w-100" style="height: 150px;"></div>
			<button class="btn bg-primary-subtle text-primary fw-bold">Profile</button>

			<div>
				<form class="mt-5" id="frm-student-profile" style="text-align: left;" name="student-profile" action="">
					<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
					<input type="hidden" name="user_id" value="<?= $user_data['userid'] ?>">

					<!-- NAMA LENGKAP - JIKA USER LEVEL MURID -->
					<?php if ($user_data['user_level'] == 4) : ?>
						<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
							<label for="full_name" class="form-label">Nama Lengkap Siswa<span class="text-danger">*</span></label>
							<input type="text" required class="form-control" name="full_name" value="<?= isset($user_data['student_name']) ? $user_data['student_name'] : '' ?>">
						</div>
					<?php endif ?>

					<!-- NAMA LENGKAP - JIKA USER LEVEL GURU -->
					<?php if ($user_data['user_level'] == 3) : ?>
						<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
							<label for="full_name" class="form-label">Nama Lengkap Guru <span class="text-danger">*</span></label>
							<input type="text" required class="form-control" name="full_name" value="<?= isset($user_data['teacher_name']) ? $user_data['teacher_name'] : '' ?>">
						</div>
					<?php endif ?>

					<!-- NAMA LENGKAP - JIKA USER LEVEL ORTU -->
					<?php if ($user_data['user_level'] == 5) : ?>
						<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
							<label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
							<input type="text" required class="form-control" id="full_name" name="full_name" value="<?= isset($user_data['name']) ? $user_data['name'] : '' ?>">
						</div>
					<?php endif ?>


					<!-- NIS - JIKA USER LEVEL MURID -->
					<?php if ($user_data['user_level'] == 4) { ?>
						<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
							<label for="nis" class="form-label">Nomor Induk Siswa</label>
							<input type="text" class="form-control" id="nis" name="nis" value="<?= isset($user_data['nis']) ? $user_data['nis'] : '' ?>" disabled>
						</div>
					<?php } ?>

					<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
						<label for="email" class="form-label">Email</label>
						<input type="email" class="form-control" name="email" value="<?= isset($user_data['email']) ? $user_data['email'] : '' ?>">
						<span style="font-size: 12px;">Ini adalah alamat email utama Anda dan akan digunakan untuk mengirim email pemberitahuan</span>
					</div>

					<!-- ALAMAT - JIKA USER LEVEL MURID -->
					<?php if ($user_data['user_level'] == 3) { ?>
						<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
							<label for="address" class="form-label">Alamat</label>
							<input type="text" class="form-control" name="address" disabled>
						</div>
					<?php } ?>

					<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
						<label for="school" class="form-label">Sekolah</label>
						<input type="text" class="form-control" name="school" disabled value="<?= isset($user_data['sekolah_nama']) ? $user_data['sekolah_nama'] : '' ?>">
					</div>

					<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
						<label for="phone" class="form-label">Nomor Telepon</label>
						<input type="text" class="form-control" name="phone" placeholder="Nomor Telepon" value="<?= isset($user_data['phone']) ? $user_data['phone'] : '' ?>">
					</div>

					<div class="mb-3 text-center mt-4">
						<button class="btn btn-primary text-white" name="save-profile">Simpan Perubahan</button>
					</div>
				</form>
			</div>
		</div>

		<!-- NAMA LENGKAP - JIKA USER LEVEL MURID -->
		<?php if ($user_data['user_level'] == 4) { ?>
			<div class="carousel-item text-center">
				<div class="d-block w-100" style="height: 150px;"></div>
				<button class="btn bg-primary-subtle text-primary fw-bold">Wali Akun Tertaut</button>

				<div>
					<form class="mt-5" name="frm-wali-akun-tertaut" action="" style="text-align: left;">
						<input type="hidden" name="user_id" value="<?= $user_data['userid'] ?>">
						<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

						<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
							<label for="parent_name" class="form-label">Nama Orang Tua</label>
							<input type="text" class="form-control" name="parent_name" value="<?= isset($parent['name']) ? $parent['name'] : '' ?>" disabled>
						</div>

						<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
							<label for="parent_email" class="form-label">Email</label>
							<input type="email" class="form-control" name="parent_email" placeholder="Masukan email orang tua" value="<?= isset($parent['email']) ? $parent['email'] : '' ?>">
						</div>

						<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
							<label for="parent_phone" class="form-label">Nomor Telepon Orang Tua</label>
							<input type="text" class="form-control" name="parent_phone" placeholder="Nomor Telepon Orang Tua" value="<?= isset($parent['phone']) ? $parent['phone'] : '' ?>">
						</div>

						<div class="mb-3 text-center mt-4">
							<button type="submit" class="btn btn-primary text-white" name="save-parent">Simpan Perubahan</button>
						</div>
					</form>
				</div>
			</div>

			<div class="carousel-item text-center">
				<div class="d-block w-100" style="height: 150px;"></div>
				<button class="btn bg-primary-subtle text-primary fw-bold">Guru Pengajar</button>

				<div>
					<table class="table table-rounded w-100 mt-5">
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
							<?php $no++;
							endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php } ?>

		<!-- NAMA LENGKAP - JIKA USER LEVEL ORTU -->
		<?php if ($user_data['user_level'] == 5) { ?>
			<div class="carousel-item text-center">
				<div class="d-block w-100" style="height: 150px;"></div>
				<button class="btn bg-primary-subtle text-primary fw-bold">Tautan Anak</button>

				<div class="mt-5">
					<div class="container p-0" style="text-align: right;">
						<button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#tambahAnakModal">
							Tambahkan Anak
						</button>
					</div>
					<table class="table border">
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
			</div>
		<?php } ?>

		<div class="carousel-item text-center">
			<div class="d-block w-100" style="height: 150px;"></div>
			<button class="btn bg-primary-subtle text-primary fw-bold">Kata Sandi</button>

			<div class="mt-5">
				<form name="form-change-password" style="text-align: left;" action="">
					<div class="mb-3 col-lg-6 col-md-10 col-sm-12 col-xs-12">
						<label for="old_password" class="form-label">Kata Sandi</label>
						<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
						<input type="hidden" name="user_id" value="<?= $user_data['userid'] ?>">
						<input type="password" class="form-control" name="old_password" placeholder="Masukan sandi saat ini">
					</div>

					<div class="mb-3 col-lg-6 col-md-10 col-sm-12 col-xs-12">
						<label for="new_password" class="form-label">Kata Sandi Baru</label>
						<input type="password" class="form-control" name="new_password" placeholder="Masukan sandi baru">
					</div>

					<div class="mb-3 col-lg-6 col-md-10 col-sm-12 col-xs-12">
						<label for="confirm_password" class="form-label">Konfirmasi Kata Sandi</label>
						<input type="password" class="form-control" name="confirm_password" placeholder="Masukan kembali sandi">
					</div>

					<div class="mb-3 text-center mt-4">
						<button class="btn btn-primary text-white" type="submit" name="change_password">Simpan Perubahan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
		<span class="carousel-control-prev-icon position-absolute" aria-hidden="true" style="top: 80px"></span>
		<span class="visually-hidden">Previous</span>
	</button>
	<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
		<span class="carousel-control-next-icon position-absolute" aria-hidden="true" style="top: 80px"></span>
		<span class="visually-hidden">Next</span>
	</button>
</div>
