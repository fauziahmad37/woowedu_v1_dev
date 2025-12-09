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
			<h3>Kunci Jawaban</h3>
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
								<h6>Soal <?= $key + 1 ?></h6>

								<!-- KONDISI JIKA SOAL PILIHAN GANDA -->
								<?php if ($soal['type'] == 1) : ?>
									<?php if ($soal['question_file']) : ?>
										<img class="rounded mt-2" src="<?= convert_image_to_base64($this->config->item('admin_path'), $soal['question_file']) ?>" width="100">
									<?php endif; ?>

									<p><?= $soal['question'] ?></p>

									<?php
									$answer = strtolower($soal['answer']);
									echo $answer . '. ' .  $soal["choice_{$answer}"];
									if ($soal["choice_{$answer}_file"]) {
										echo '<br><img class="rounded mt-2" src="' . convert_image_to_base64($this->config->item('admin_path'), $soal["choice_{$answer}_file"]) . '" width="100">';
									}
									?>

								<?php endif; ?>

								<!-- KONDISI JIKA SOAL ESSAY, TOF, FTB -->
								<?php if ($soal['type'] == 2 || $soal['type'] == 3 || $soal['type'] == 4) : ?>
									<p><?= $soal['question'] ?></p>

									<?php if ($soal['question_file']) : ?>
										<img class="rounded mt-2" src="<?= convert_image_to_base64($this->config->item('admin_path'), $soal['question_file']) ?>" width="100">
									<?php endif; ?>

									<div class="d-flex mt-3">
										<div class="flex-item">
											<p class="me-3 fw-bold">
												<i class="fa-solid fa-check bg-success rounded-pill text-white p-1"></i>
												<?php if ($soal['answer'] == 'true') : ?>
													Benar
												<?php elseif ($soal['answer'] == 'false') : ?>
													Salah
												<?php else : ?>
													<?= $soal['answer'] ?>
												<?php endif; ?>
											</p>
										</div>
										<div class="flex-item">
											<p class="">
												<?= ($soal['alternative_answer']) ? '<i class="fa-solid fa-check bg-success rounded-pill text-white p-1"></i> ' . $soal['alternative_answer'] : ''; ?>
											</p>
										</div>
									</div>
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
