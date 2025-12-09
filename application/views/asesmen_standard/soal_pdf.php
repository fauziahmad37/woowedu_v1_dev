<!DOCTYPE html>
<html>

<head>
	<title>Soal PDF</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

	<script src="https://kit.fontawesome.com/b377b34fd7.js" crossorigin="anonymous"></script>
	<style>
		body {
			font-family: Arial, sans-serif;
		}

		.container {
			width: 80%;
			margin: 0 auto;
		}

		.header {
			text-align: center;
			margin-bottom: 20px;
		}

		.question {
			margin-bottom: 15px;
		}

		.question h3 {
			margin: 0;
		}

		.question p {
			margin: 5px 0;
		}

	</style>
</head>

<body>

	<div class="container">
		<div class="header">
			<h3>Soal PDF</h3>
		</div>
		<div class="content">
			<?php
			function convert_image_to_base64($admin_path, $a)
			{
				$data = @file_get_contents($admin_path . $a); // tambahkan @ sebelum file_get_contents untuk menghilangkan error warning
				$base64 = 'data:image/;base64,' . base64_encode($data);

				return $base64;
			}
			?>

			<?php if (!empty($group_soal)) : ?>
				<?php foreach ($group_soal as $key => $question) : ?>
					<div class="group-soal">
						<h4> <?= $question['jenis_soal'] ?></h4>

						<?php foreach ($question['soal'] as $key => $soal) : ?>
							<div class="question" style="font-size: 12px;">
								<h5>Soal <?= $key + 1 ?></h5>

								<!-- KONDISI JIKA SOAL PILIHAN GANDA -->
								<?php if ($soal['type'] == 1) : ?>
									<p><?= $soal['question'] ?></p>

									<?php if ($soal['choice_a'] || $soal['choice_a_file']) : ?>
										<p style="margin-top: 20px;"><img src="<?= convert_image_to_base64($this->config->item('admin_path'), $soal['choice_a_file']) ?>" width="100"></p>
										<p>A. <?= $soal['choice_a'] ?></p>
									<?php endif ?>

									<?php if ($soal['choice_b'] || $soal['choice_b_file']) : ?>
										<p style="margin-top: 20px;"><img src="<?= convert_image_to_base64($this->config->item('admin_path'), $soal['choice_b_file']) ?>" width="100"></p>
										<p>B. <?= $soal['choice_b'] ?></p>
									<?php endif ?>

									<?php if ($soal['choice_c'] || $soal['choice_c_file']) : ?>
										<p style="margin-top: 20px;"><img src="<?= convert_image_to_base64($this->config->item('admin_path'), $soal['choice_c_file']) ?>" width="100"></p>
										<p>C. <?= $soal['choice_c'] ?></p>
									<?php endif ?>

									<?php if ($soal['choice_d'] || $soal['choice_d_file']) : ?>
										<p style="margin-top: 20px;"><img src="<?= convert_image_to_base64($this->config->item('admin_path'), $soal['choice_d_file']) ?>" width="100"></p>
										<p>D. <?= $soal['choice_d'] ?></p>
									<?php endif ?>

									<?php if ($soal['choice_e'] || $soal['choice_e_file']) : ?>
										<p style="margin-top: 20px;"><img src="<?= convert_image_to_base64($this->config->item('admin_path'), $soal['choice_e_file']) ?>" width="100"></p>
										<p>E. <?= $soal['choice_e'] ?></p>
									<?php endif ?>
								<?php endif; ?>

								<!-- KONDISI JIKA SOAL ESSAY -->
								<?php if ($soal['type'] == 2 || $soal['type'] == 3 || $soal['type'] == 4 ) : ?>
									<p><?= $soal['question'] ?></p>
								<?php endif ?>

							</div>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<p>No questions available.</p>
			<?php endif; ?>
		</div>

	</div>

</body>

</html>
