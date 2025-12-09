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

		.drag-drop-item {
			display: inline-block;
			padding: 5px 10px;
			background-color: #f0f0f0;
			border-radius: 5px;
			margin-right: 5px;
			margin-top: 10px;
		}


		/* untuk kop surat */
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
		/* end kop surat */
	</style>
</head>

<body>

	<div class="container">
<table width="100%" style="margin-bottom: 10px;">
  <tr>
    <?php if (!empty($logo_base64)) : ?>
    <td width="80" style="text-align: center;">
      <img src="<?= $logo_base64 ?>" alt="Logo Sekolah" style="width: 80px;">
    </td>
    <?php endif; ?>
    <td style="text-align: center;">
      <h2 style="margin: 0;"><?= $sekolah_nama ?></h2>
      <p style="margin: 0; font-size: 12px;">
        <?= $sekolah_alamat ?><br>
        Telp: <?= $sekolah_phone ?>  Email: <?= $sekolah_email ?>
      </p>
    </td>
  </tr>
</table>
<hr style="border: 1px solid #000; margin-top: 10px;">
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
									<?php if ($soal['question_file']) : ?>
										<p style="margin-top: 20px;"><img src="<?= convert_image_to_base64($this->config->item('admin_path'), $soal['question_file']) ?>" width="100"></p>
									<?php endif ?>
									<p><?= $soal['question'] ?></p>

									<?php if ($soal['choice_a'] || $soal['choice_a_file']) : ?>
										<?php if ($soal['choice_a_file']) : ?>
											<p style="margin-top: 20px;"><img src="<?= convert_image_to_base64($this->config->item('admin_path'), $soal['choice_a_file']) ?>" width="100"></p>
										<?php endif ?>
										<p>A. <?= $soal['choice_a'] ?></p>
									<?php endif ?>

									<?php if ($soal['choice_b'] || $soal['choice_b_file']) : ?>
										<?php if ($soal['choice_b_file']) : ?>
											<p style="margin-top: 20px;"><img src="<?= convert_image_to_base64($this->config->item('admin_path'), $soal['choice_b_file']) ?>" width="100"></p>
										<?php endif ?>
										<p>B. <?= $soal['choice_b'] ?></p>
									<?php endif ?>

									<?php if ($soal['choice_c'] || $soal['choice_c_file']) : ?>
										<?php if ($soal['choice_c_file']) : ?>
											<p style="margin-top: 20px;"><img src="<?= convert_image_to_base64($this->config->item('admin_path'), $soal['choice_c_file']) ?>" width="100"></p>
										<?php endif ?>
										<p>C. <?= $soal['choice_c'] ?></p>
									<?php endif ?>

									<?php if ($soal['choice_d'] || $soal['choice_d_file']) : ?>
										<?php if ($soal['choice_d_file']) : ?>
											<p style="margin-top: 20px;"><img src="<?= convert_image_to_base64($this->config->item('admin_path'), $soal['choice_d_file']) ?>" width="100"></p>
										<?php endif ?>
										<p>D. <?= $soal['choice_d'] ?></p>
									<?php endif ?>

									<?php if ($soal['choice_e'] || $soal['choice_e_file']) : ?>
										<?php if ($soal['choice_e_file']) : ?>
											<p style="margin-top: 20px;"><img src="<?= convert_image_to_base64($this->config->item('admin_path'), $soal['choice_e_file']) ?>" width="100"></p>
										<?php endif ?>
										<p>E. <?= $soal['choice_e'] ?></p>
									<?php endif ?>
								<?php endif; ?>

								<!-- KONDISI JIKA SOAL ESSAY -->
								<?php if ($soal['type'] == 2 || $soal['type'] == 3 || $soal['type'] == 4) : ?>
									<p><?= $soal['question'] ?></p>
								<?php endif ?>

								<!-- KONDISI JIKA SOAL PAIRING -->
								<?php if ($soal['type'] == 5) : ?>

									<table>
										<tr>
											<?php
											$soal_pairing = $soal['pairing'];
											shuffle($soal_pairing);
											foreach ($soal_pairing as $key => $val_key) :
											?>
												<td style="padding-right: 20px; text-align: center;">
													<?php if ($val_key['has_image']) : ?>
														<img src="<?= convert_image_to_base64($this->config->item('admin_path'), $val_key['answer_key']) ?>" width="100">
													<?php else : ?>
														<p class="d-inline-block"><?= $val_key['answer_key'] ?></p>
													<?php endif ?>
												</td>
											<?php endforeach ?>
										</tr>


										<tr>
											<?php
											$answer_pairing = $soal['pairing'];
											shuffle($answer_pairing);
											foreach ($answer_pairing as $key => $val_answer) :
											?>
												<td style="padding-right: 20px; text-align: center;">
													<p class="d-inline-block"><?= $val_answer['answer_value'] ?></p>
												</td>
											<?php endforeach ?>
										</tr>
									</table>
								<?php endif ?>

								<!-- KONDISI JIKA SOAL DRAG & DROP -->
								<?php if ($soal['type'] == 6) : ?>
									<?php if ($soal['question_file']) : ?>
										<p style="margin-top: 20px;"><img src="<?= convert_image_to_base64($this->config->item('admin_path'), $soal['question_file']) ?>" width="100"></p>
									<?php endif ?>
									<p><?= $soal['question'] ?></p>
									<?php
									$soal_drag_drop = $soal['drag_drop'];
									shuffle($soal_drag_drop); 
										foreach ($soal_drag_drop as $key => $val) : 
									?>
										<span class="me-3 drag-drop-item"><i class="fa fa-dot-circle-o me-2"></i><?= $val['answer_correct'] ?></span>
									<?php endforeach ?>
								<?php endif ?>

							</div>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<p>No questions available.</p>
			<?php endif; ?>
			<div class="footer">
				<img src="<?= convert_image_to_base64($this->config->item('admin_path'), 'assets/images/logo/logowoowedu.jpg') ?>" style="margin-top:20px;width:80px;margin-right:10px;">Supported by <a href="https://woowedu.com" target="_blank">woowedu.com</a>
			</div>
		</div>
	</div>
	
</body>

</html>
