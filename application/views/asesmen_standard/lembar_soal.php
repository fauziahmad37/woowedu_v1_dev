<div class="row mt-3">
	<div class="col"><img width="150" src="<?= base_url() ?>assets/themes/<?= isset($_SESSION['themes']) ? $_SESSION['themes'] : 'space' ?>/images/logowowedu.png" alt=""></div>
	<div class="col text-end">
		<span style="font-size: 16px; font-weight: 600; display: block;"><?= $exam_header['title'] ?></span>
		<span style="font-size: 12px; display: block;"><?= $exam_header['subject_name'] ?> - <?= $exam_header['class_name'] ?></span>
	</div>
</div>

<div class="row my-5">
	<div class="col-12 d-flex flex-row-reverse">
		<!-- <button class="btn btn-lg bg-primary-subtle text-primary border-primary" style="font-size: 14px;">
			<i class="fas fa-eye"></i>
			Tinjau Soal
		</button> -->
		<button class="btn btn-lg bg-primary-subtle text-primary border-primary me-3" id="download-asesmen-standard" style="font-size: 14px;">
			<i class="fas fa-download"></i>
			Unduh Soal
		</button>
	</div>
</div>

<input type="hidden" name="exam_id" value="<?= isset($exam_id) ? $exam_id : '' ?>">

<div class="testpaper-sectionContainer mt-4">
	<div class="card">
		<div class="card-header bg-primary text-white">
			Soal Asesmen (<?= count($soal_exam) ?> Soal)
		</div>

		<div class="row px-3 pt-3">
			<div class="col">
				<p>Waktu Mulai: <span class="fw-bold"><?= date('d M Y, H:i', strtotime($exam_header['start_date'])) ?></span></p>
			</div>
			<div class="col text-end">
				<p>Waktu Berakhir: <span class="badge text-red bg-danger fs-6"><?= date('d M Y, H:i', strtotime($exam_header['end_date'])) ?></span></p>
			</div>
		</div>

		<hr>

		<div class="card-body">

			<div class="row mt-4">
				<!-- <div class="content-<? //= $key 
											?>"> -->
				<div class="content-1">
					<!-- <div class="content"> -->
					<?php foreach ($soal_exam as $key2 => $detail) : ?>
						<?php
						$jenisSoal = '';

						if ($detail['type'] == 1) {
							$jenisSoal = '<i class="fa-solid fa-list"></i> Pilihan Ganda';
						} elseif ($detail['type'] == 2) {
							$jenisSoal = '<i class="fa-solid fa-align-left"></i> Esai';
						} elseif ($detail['type'] == 3) {
							$jenisSoal = '<i class="bi bi-card-checklist"></i> Benar atau Salah';
						} elseif ($detail['type'] == 4) {
							$jenisSoal = '<i class="fa-regular fa-square"></i> Isi Yang Kosong';
						} elseif ($detail['type'] == 5) {
							$jenisSoal = '<i class="fa-regular fa-square"></i> Menjodohkan';
						} elseif ($detail['type'] == 6) {
							$jenisSoal = '<i class="fa-regular fa-square"></i> Seret Lepas';
						}

						?>

						<div class="card border-start-0 border-top-0 border-end-0 mb-3 rounded-0 mt-5">

							<div class="card-header bg-white border-0 p-0">
								<div class="d-flex justify-content-between">
									<div class="btn btn-light border rounded-3 no-soal">
										Soal <?= $key2 + 1 . '. ' . $jenisSoal ?>
									</div>


									<div class="d-flex btn-group-fill-the-blank">

										<button class="btn btn-light border rounded-3 me-2" onclick="tinjauSoal(this)">
											<i class="fa-solid fa-eye" aria-hidden="true"></i> Tinjau Soal
										</button>

										<?php if ($detail['response_correct_answer']) : ?>
											<button class="btn btn-light border rounded-3 me-2">
												<i class="far fa-lightbulb me-2"></i>Respon
											</button>
										<?php endif ?>

										<?php if ($detail['variation_answer']) : ?>
											<button class="btn btn-light border rounded-3 me-2">
												<i class="fa-solid fa-layer-group me-2"></i>Variasi
											</button>
										<?php endif ?>

										<?php if ($detail['alternative_answer']) : ?>
											<button class="btn btn-light border rounded-3 me-2">
												<i class="fa-solid fa-layer-group me-2"></i>Alternatif
											</button>
										<?php endif ?>

										<button class="btn btn-light border rounded-3 me-2">
											<i class="fa-solid fa-ribbon me-2"></i><?= $detail['point'] ?> Poin
										</button>

									</div>

								</div>
							</div>

							<div class="card-body p-0 pb-3 mt-3">
								<div class="d-flex justify-content-between">
									<div>
										<input type="hidden" name="question_id[]" value="<?= $detail['soal_id'] ?>">

										<?php if ($detail['question_file']) : ?>
											<img src="<?= $this->config->item('admin_url') ?><?= $detail['question_file'] ?>" class="img-fluid img-question mb-2" width="100" alt="Gambar Soal">
										<?php endif ?>

										<p class="d-inline-block"><?= $detail['question'] ?></p>

									</div>
								</div>


								<!-- KONDISI JIKA SOAL PILIHAN GANDA -->
								<?php if ($detail['type'] == 1) : ?>
									<div class="row mt-3" style="line-height: 2;">

										<?php $choices = ['A', 'B', 'C', 'D', 'E']; ?>
										<?php foreach ($choices as $key => $choice) : ?>
											<?php if ($detail['choice_' . strtolower($choice)] || $detail['choice_' . strtolower($choice) . '_file']) : ?>
												<div class="col-6">
													<p class="">
														<?= $choice ?>. <?= $detail['choice_' . strtolower($choice)] ?>
													</p>
													<?php if ($detail['choice_' . strtolower($choice) . '_file']) : ?>
														<img src="<?= $this->config->item('admin_url') ?><?= $detail['choice_' . strtolower($choice) . '_file'] ?>" width="100" class="img-fluid img-question-choice" alt="Gambar <?= $key ?>">
													<?php endif ?>

												</div>
											<?php endif ?>
										<?php endforeach ?>
									</div>

								<?php endif ?>

								<!-- KONDISI JIKA SOAL TRUE OR FALSE -->
								<?php if ($detail['type'] == 3) : ?>
									<div class="row mt-3" style="line-height: 2;">
										<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
											<p class="">
												<i class="bi bi-record-circle"></i> Benar
											</p>
										</div>
										<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
											<p class="">
												<i class="bi bi-record-circle"></i> Salah
											</p>
										</div>
									</div>
								<?php endif ?>

								<!-- KONDISI JIKA SOAL DRAG & DROP -->
								<?php if ($detail['type'] == 6) : ?>
									<?php foreach($detail['drag_drop'] as $key => $val): ?>
										<span class="me-3"><i class="fa fa-dot-circle-o me-2"></i><?= $val['answer_correct'] ?></span>
									<?php endforeach ?>
								<?php endif ?>

							</div>

						</div>

					<?php endforeach ?>

				</div>
			</div>

		</div>
	</div>

</div>
