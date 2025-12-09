<!DOCTYPE html>
<html lang="en">

<head>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Download data ujian siswa</title>

	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f8f9fa;
		}
		.table {
			border: 1px solid #dee2e6;
			border-radius: 0.25rem;
		}

		.table th,
		.table td {
			padding: 0.75rem;
			vertical-align: top;
			border-top: 1px solid #dee2e6;
			font-size: 12px;
		}
.kop-surat img {
  width: 80px;
  height: auto;
}
.kop-text {
  text-align: center;
}
.footer {
  position: fixed;
  bottom: -30px;
  left: 0;
  right: 0;
  text-align: center;
  font-size: 10pt;
}

	</style>
</head>

<body>
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/fontawesome.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
	<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/sweetalert2.all.min.js') ?>"></script>

	<?php
		function convert_image_to_base64($admin_path, $a)
		{
			$data = @file_get_contents($admin_path . $a); // tambahkan @ sebelum file_get_contents untuk menghilangkan error warning
			$base64 = 'data:image/;base64,' . base64_encode($data);

			return $base64;
		}
	?>

	<div class="container">
<table width="100%" style="margin-bottom: 10px;">
  <tr>
       <?php if ($logo_base64): ?>
    <td width="80" style="text-align: center;">
       <img src="<?= $logo_base64 ?>" style="width:100px;" alt="Logo Sekolah" />
    </td>
    <?php endif; ?>
    <td style="text-align: center;">
      <h2 style="margin: 0;"><?= $sekolah['sekolah_nama'] ?></h2>
      <p style="margin: 0; font-size: 12px;">
       <?= $sekolah['sekolah_alamat'] ?><br>
        Telp:  <?= $sekolah['sekolah_phone'] ?>  Email: <?= $sekolah['sekolah_email'] ?>
      </p>
    </td>
  </tr>
</table>
<hr style="border: 1px solid #000; margin-top: 10px;">

		<div class="row">
			<div class="col-12">
				<h4 style="text-align: center;">Data ujian siswa</h4>
			</div>
		</div>

		<br>
		<table class="table" style="width: 100%;">
			<thead>
				<tr>
					<th>No</th>
					<th>NIS</th>
					<th>Nama Siswa</th>
					<th>Tanggal Ujian</th>
					<th>Status</th>
					<th>Nilai</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $key => $student) : ?>
					<tr>
						<td><?= $key + 1 ?></td>
						<td><?= $student['nis'] ?></td>
						<td><?= $student['student_name'] ?></td>
						<td><?= $student['exam_submit'] ?></td>
						<td><?= $student['status'] ?></td>
						<td><?= $student['exam_score'] ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="footer">
		<img src="<?= convert_image_to_base64($this->config->item('admin_path'), 'assets/images/logo/logowoowedu.jpg') ?>" style="margin-top:20px;width:80px;margin-right:10px;">Supported by <a href="https://woowedu.com" target="_blank">woowedu.com</a>
	</div>
</body>

</html>
