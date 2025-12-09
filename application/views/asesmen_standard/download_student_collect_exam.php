<!DOCTYPE html>
<html lang="en">

<head>
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
	</style>
</head>

<body>
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/fontawesome.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
	<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/sweetalert2.all.min.js') ?>"></script>

	<div class="container">
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

</body>

</html>
