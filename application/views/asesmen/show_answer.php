<style>
	.btn-light:hover {
		background-color: #f8f9fa;
		border-color: #f8f9fa;
	}


	.container {
		max-width: 100%;
	}

	/* chart section */

	#donut-chart {
		margin: 0 auto;
		height: 180px;
	}


	.highcharts-no-tooltip,
	.highcharts-credits {
		display: none;
	}

	/* end chart section */

	.line {
		width: 100%;
		border-bottom: 1px solid rgba(0, 0, 0, 0.1);
	}

	p {
		font-size: 14px;
	}

	.section-answer {
		background-color: rgba(148, 196, 79, 0.05);
	}

	.youre-answer {
		width: 200px;
	}

	.alphabet {
		margin-left: 35px;
		font-size: 18px;
	}

	.round {
		position: absolute;
	}

	.round label {
		background-color: #fff;
		border: 1px solid #ccc;
		border-radius: 50%;
		cursor: pointer;
		height: 28px;
		left: 0;
		position: absolute;
		top: 0;
		width: 28px;
	}

	.round label:after {
		border: 2px solid #fff;
		border-top: none;
		border-right: none;
		content: "";
		height: 6px;
		left: 7px;
		opacity: 0;
		position: absolute;
		top: 8px;
		transform: rotate(-45deg);
		width: 12px;
	}

	.round input[type="checkbox"] {
		visibility: hidden;
	}

	.round input[type="checkbox"]:checked+label {
		background-color: #66bb6a;
		border-color: #66bb6a;
	}

	.round input[type="checkbox"]:checked+label:after {
		opacity: 1;
	}
</style>

<script>
	let correctPercentage = <?= $correctPercentage ?>;
	let wrongPercentage = <?= $wrongPercentage ?>;
</script>

<section class="explore-section section-padding" id="section_2">

	<button class="btn btn-light text-primary p-0 mb-3" onclick="history.back()">
		<h6>
			< kembali ke lembar asesmen</h6>
	</button>

	<h5 class="fw-bold">Laporan Hasil Asesmen</h5>

	<!-- Section Dashboard -->
	<div class="row header-laporan border rounded-4 mt-3 p-4">

		<!-- Kolom Kiri - Foto Profil Siswa & Chart -->
		<div class="col-xl-5 co-lg-5 col-md-5 col-sm-12 col-12">

			<div class="row">
				<div class="col-6 text-center" style="border-right: solid; border-right-width: 1px; border-color: #ccc;">
					<img class="rounded-pill" width="100" src="assets/images/users/<?= $user_image ?>" alt="">

					<div class="mt-3">
						<h6 class="fw-bold"><?= $student['student_name'] ?></h6>
						<p><?= $student['class_name'] ?></p>
					</div>
				</div>
				<div class="col-6">
					<figure class="highcharts-figure">
						<div id="donut-chart"></div>
					</figure>
				</div>
			</div>

		</div>

		<!-- Kolom Tengah - Card Score -->
		<div class="col-xl-5 co-lg-5 col-md-5 col-sm-12 col-12" style="color: #74788D;">
			<div class="row pe-3" style="border-right: solid; border-right-width: 1px; border-color: #ccc;">
				<?php
					// hitung jumlah type soal essay
					$total_soal_essay = 0;
					foreach ($soal_exam as $soal) {
						if ($soal['type'] == 2) {
							$total_soal_essay++;
						}
					}

					$onlyEssay = ($total_soal_essay == count($soal_exam)) ? true : false; // Cek apakah semua soal adalah essay

					// jika only essay maka persentase benar pada donut chart menggunakan total point
					if ($onlyEssay) {
						echo "<script>correctPercentage = $total_point;</script>";
					}
				?>

				<div class="<?=($onlyEssay) ? 'col-12 mb-3' : 'col-6'?>">
					<div class="border rounded-3 p-3 d-flex">

						<span class="rounded-3" style="height: 48px; width: 48px; background-color: #E3E4E8; display: flex; justify-content: center; align-items: center;">
							<i class="fa-solid fa-medal text-dark" style="font-size:21px;"></i>
						</span>

						<div class="ms-3">
							<h6 class="">Skor Total</h6>
						
							<!-- <p class="m-0"><span class="h4 fw-bold text-dark"><?//= $correctPercentage ?></span> / 100</p> -->
							<p class="m-0"><span class="h4 fw-bold text-dark skor-total"><?= $total_point ?></span> / 100</p>
						</div>

					</div>
				</div>

				<div class="col-6 <?=($onlyEssay) ? 'd-none' : ''?>">
					<div class="border rounded-3 p-3 d-flex">

						<span class="rounded-3" style="height: 48px; width: 48px; background-color: #E3E4E8; display: flex; justify-content: center; align-items: center;">
							<i class="bi bi-file-earmark-check-fill text-dark" style="font-size:21px;"></i>
						</span>

						<div class="ms-3">
							<h6 class="">Jawaban Benar</h6>
							<p class="m-0"><span class="h4 fw-bold text-dark"><?= $jawaban_benar ?></span></p>
						</div>

					</div>
				</div>

				<div class="<?=($onlyEssay) ? 'col-12' : 'col-6 mt-3'?>">
					<div class="border rounded-3 p-3 d-flex">

						<span class="rounded-3" style="height: 48px; width: 48px; background-color: #E3E4E8; display: flex; justify-content: center; align-items: center;">
							<i class="fa-solid fa-clipboard-list text-dark" style="font-size:21px;"></i>
						</span>

						<div class="ms-3">
							<h6 class="">Jumlah Soal</h6>
							<p class="m-0"><span class="h4 fw-bold text-dark"><?= $total_soal ?></span></p>
						</div>

					</div>
				</div>

				<div class="col-6 mt-3 <?=($onlyEssay) ? 'd-none' : ''?>">
					<div class="border rounded-3 p-3 d-flex">

						<span class="rounded-3" style="height: 48px; width: 48px; background-color: #E3E4E8; display: flex; justify-content: center; align-items: center;">
							<i class="bi bi-file-earmark-x-fill text-dark" style="font-size:21px;"></i>
						</span>

						<div class="ms-3">
							<h6 class="">Jawaban Salah</h6>
							<p class="m-0"><span class="h4 fw-bold text-dark"><?= $jawaban_salah ?></span></p>
						</div>

					</div>
				</div>


			</div>
		</div>

		<!-- Kolom Kanan - Title & Pemberian Nilai -->
		<div class="col-xl-2 co-lg-2 col-md-2 col-sm-12 col-12">

			<h4><?= $exam['title'] ?></h4>
			<p><?= $exam['subject_name'] ?> - <?= $exam['class_name'] ?></p>

			<div class="">

				<?php if (isset($_SESSION['teacher_id'])) : ?>
					<button class="btn btn-primary text-white rounded-3 mt-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
						<i class="bi bi-pencil"></i> Pemberian Nilai
					</button>
				<?php endif; ?>

				<a href="asesmen_standard/download_report_student?exam_id=<?= $exam['exam_id'] ?>&student_id=<?= $student['student_id'] ?>" target="_blank" class="btn btn-light text-primary border-primary rounded-3 mt-2">
					<i class="fa fa-download"></i> Unduh Laporan
				</a>
			</div>
		</div>
	</div>


	<!-- Section Lembar Soal -->
	<div class="testpaper-sectionContainer mt-4">
		<div class="card">

			<!-- Header Soal -->
			<div class="card-header bg-primary text-white">
				Soal Asesmen (<?= count($soal_exam) ?> Soal)
			</div>

			<div class="row px-3 pt-3">
				<div class="col">
					<p>Waktu Mulai: <span class="fw-bold"><?= date('d M Y, H:i', strtotime($exam['start_date'])) ?></span></p>
				</div>
				<div class="col text-end">
					<p>Waktu Berakhir: <span class="badge text-red bg-danger fs-6"><?= date('d M Y, H:i', strtotime($exam['end_date'])) ?></span></p>
				</div>
			</div>

			<hr>

			<!-- List Soal -->
			<div class="card-body">

				<div class="row mt-4">
					<!-- <div class="content-<? //= $key 
												?>"> -->
					<div class="content-1">
						<!-- <div class="content"> -->
						<?php foreach ($soal_exam as $key2 => $detail) : ?>
							<?php
							$jenisSoal = '';

							// Icon Kondisi Jenis Soal
							if ($detail['type'] == 1) {
								$jenisSoal = '<i class="fa-solid fa-list"></i> Pilihan Ganda';
							} elseif ($detail['type'] == 2) {
								$jenisSoal = '<i class="fa-solid fa-align-left"></i> Esai';
							} elseif ($detail['type'] == 3) {
								$jenisSoal = '<i class="fa-solid fa-align-left"></i> Benar Atau Salah';
							} elseif ($detail['type'] == 4) {
								$jenisSoal = '<i class="fa-regular fa-square"></i> Isi Yang Kosong';
							} elseif ($detail['type'] == 5) {
								$jenisSoal = '<i class="fa-solid fa-align-left"></i> Menjodohkan';
							} elseif ($detail['type'] == 6) {
								$jenisSoal = '<i class="fa-solid fa-align-left"></i> Seret Lepas';
							}

							?>

							<div class="card border-start-0 border-top-0 border-end-0 mb-3 rounded-0 mt-5">

								<div class="card-header bg-white border-0 p-0">
									<div class="d-flex justify-content-between">
										<div class="soal-number-group-left">
											<div class="btn btn-light border rounded-3 no-soal">
												Soal <?= $key2 + 1 . '. ' . $jenisSoal ?>
											</div>

											<!-- JIKA JENIS SOAL SELAIN ESSAY -->
											<?php if($detail['type'] != 2) : ?>

												<?php if ($detail['status'] == 'benar') : ?>
													<span class="btn bg-success text-white ms-2">
														<i class="fa-solid fa-check"></i>
														Benar
													</span>
												<?php endif; ?>

												<?php if ($detail['status'] == 'salah') : ?>
													<span class="btn bg-danger text-white ms-2">
														<i class="fa-solid fa-times"></i>
														Salah
													</span>
												<?php endif; ?>

											<?php endif ?>

											<!-- JIKA JENIS SOAL ESSAY -->
											<?php if($detail['type'] == 2) : ?>
												<button class="btn btn-light border rounded-3 me-2" style="cursor: default; background-color: #D1D2D9;">
													<i class="fa-solid fa-ribbon me-2"></i><span class="bobot-point"><?= $detail['point'] ?></span> Bobot Poin
												</button>
											<?php endif ?>

										</div>

										<div class="d-flex btn-group-fill-the-blank">

											<!-- JIKA JENIS SOAL ESSAY -->
											<?php if ($detail['type'] == 2) : ?>
												<button class="btn btn-light border rounded-3 me-2" style="cursor: default;">
													<i class="fa-solid fa-ribbon me-2"></i><span class="result-point">
														<?= ($detail['exam_answer'][0]['result_point']) ?  $detail['exam_answer'][0]['result_point'] : '0' ?>
													</span> Poin
												</button>
											<?php else : ?>
												<button class="btn btn-light border rounded-3 me-2" style="cursor: default;">
													<i class="fa-solid fa-ribbon me-2"></i>
														<span class="result-point">
															<?= ($detail['status'] == 'benar') ? $detail['point'] : 0 ?>
														</span> Poin
												</button>
											<?php endif ?>

											<!-- JIKA JENIS SOAL ESSAY -->
											<?php if ($detail['type'] == 2 && $_SESSION['user_level'] == 3) : ?>
												<button class="btn btn-primary text-white border rounded-3 me-2" onclick="showInputPointSoal(<?= $detail['soal_id'] ?>, <?= $detail['exam_answer'][0]['ea_id'] ?>)">
													<i class="fa-solid fa-edit me-2 give-point-soal"></i>Beri Poin
												</button>
											<?php endif ?>

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

											<p class="d-inline-block question-text"><?= $detail['question'] ?></p>

											<!-- KONDISI JIKA SOAL PILIHAN GANDA -->
											<?php if ($detail['type'] == 1) : ?>
												<div class="mt-3" style="line-height: 2;">
													<?php if (strtolower($detail['exam_answer'][0]['exam_answer']) == strtolower($detail['exam_answer'][0]['correct_answer'])) : ?>
														<i class="fa-solid fa-check-circle text-success me-2"></i>

														<?php $correct_answer = strtolower($detail['exam_answer'][0]['correct_answer']) ?>
														<span class="d-inline"> <?= $correct_answer . '. ' . $detail['choice_' . $correct_answer] ?> </span>

														<?php if ($detail['choice_' . $correct_answer . '_file']) : ?>
															<img src="<?= $this->config->item('admin_url') ?><?= $detail['choice_' . $correct_answer . '_file'] ?>" class="img-fluid img-question" width="100" alt="Gambar Soal">
														<?php endif ?>
													<?php else : ?>
														<i class="fa-solid fa-times-circle text-danger me-2"></i>

														<?php if ($detail['exam_answer'][0]['exam_answer']) : ?>

															<?php $exam_answer = strtolower($detail['exam_answer'][0]['exam_answer']) ?>
															<span class="d-inline"> <?= $exam_answer . '. ' . $detail['choice_' . $exam_answer] ?> </span>

															<?php if ($detail['choice_' . $exam_answer . '_file']) : ?>
																<img src="<?= $this->config->item('admin_url') ?><?= $detail['choice_' . $exam_answer . '_file'] ?>" class="img-fluid img-question" width="100" alt="Gambar Soal">
															<?php endif ?>
														<?php endif ?>


													<?php endif ?>
												</div>

											<?php endif ?>

											<!-- KONDISI JIKA SOAL ESSAY -->
											<?php if ($detail['type'] == 2) : ?>
												<div class="mt-3" style="line-height: 2;">
													<p>Jawaban Siswa:</p>

													<?php foreach ($detail['exam_answer'] as $key => $valEssay) : ?>

														<?php if (
															(strtolower($valEssay['exam_answer']) == strtolower($valEssay['correct_answer']))
															|| (strtolower($valEssay['exam_answer']) == strtolower($detail['alternative_answer']))
														) : ?>
															<p>
																
																<span class="d-inline student-answer-text fw-bold"> <?= strtolower($valEssay['exam_answer']) ?> </span>
															</p>
														<?php else : ?>

															<p>
																
																<span class="d-inline student-answer-text fw-bold"> <?= strtolower($valEssay['exam_answer']) ?> </span>
															</p>

														<?php endif ?>

													<?php endforeach ?>



												</div>
											<?php endif ?>

											<!-- KONDISI JIKA SOAL BENAR SALAH -->
											<?php if ($detail['type'] == 3) : ?>
												<div class="mt-3" style="line-height: 2;">
													<?php if ($detail['exam_answer'][0]['exam_answer'] == $detail['exam_answer'][0]['correct_answer']) : ?>
														<p>Jawaban Siswa:</p>
														<p>
															<i class="fa-solid fa-check-circle text-success me-2"></i>
															<span class="d-inline"> <?= $detail['exam_answer'][0]['exam_answer'] ?> </span>
														</p>
													<?php endif ?>

													<?php if ($detail['exam_answer'][0]['exam_answer'] != $detail['exam_answer'][0]['correct_answer']) : ?>
														<p>Jawaban Siswa:</p>
														<p>
															<i class="fa-solid fa-times-circle text-danger me-2"></i>
															<span class="d-inline"> <?= $detail['exam_answer'][0]['exam_answer'] ?> </span>
														</p>
													<?php endif ?>
												</div>
											<?php endif ?>

											<!-- KONDISI JIKA SOAL FILL THE BLANK -->
											<?php if ($detail['type'] == 4) : ?>
												<div class="mt-3" style="line-height: 2;">
													<p>Jawaban Siswa:</p>
													<div class="">
														<?php foreach ($detail['exam_answer'] as $key => $val) : ?>

															<?php if ($val['exam_answer'] == $val['correct_answer']) : ?>
																<i class="fa-solid fa-check-circle text-success me-2"></i>
																<span class="d-inline me-3"> <?= $val['exam_answer'] ?> </span>

															<?php endif ?>

															<?php if ($val['exam_answer'] != $val['correct_answer']) : ?>

																<i class="fa-solid fa-times-circle text-danger me-2"></i>
																<span class="d-inline me-3"> <?= $val['exam_answer'] ?> </span>
															<?php endif ?>

														<?php endforeach ?>
													</div>

												</div>
											<?php endif ?>

											<!-- KONDISI JIKA SOAL MENJODOHKAN -->
											<?php if ($detail['type'] == 5) : ?>
												<div class="mt-3" style="line-height: 2;">
													<p>Jawaban Siswa:</p>
													<div class="">
														<?php foreach ($detail['exam_answer'] as $key => $val) : ?>

															<?php foreach (json_decode($val['exam_answer'], true) as $answer) : ?>
																<div class="mt-2 d-flex align-items-center">
																	<img src="<?= $this->config->item('admin_url') . $answer['answer_key'] ?>" alt="" width="100" height="100" class="rounded me-2">
																	<span class="fs-3 fw-bold d-block mx-3">→</span>
																	<h6 class="d-inline m-0"> <?= $answer['answer_value'] ?> </h6>

																	<?php foreach (json_decode($val['correct_answer'], true) as $ca) : ?>
																		<?php if ($answer['urutan'] == $ca['urutan']) : ?>
																			<?php if ($answer['answer_value'] == $ca['answer_value'] && $answer['answer_key'] == $ca['answer_key']) : ?>
																				<i class="fa-solid fa-check-circle text-success ms-2"></i>
																			<?php else : ?>
																				<i class="fa-solid fa-times-circle text-danger ms-2"></i>
																			<?php endif ?>
																		<?php endif ?>
																	<?php endforeach ?>

																</div>
															<?php endforeach ?>

														<?php endforeach ?>
													</div>

												</div>
											<?php endif ?>

											<!-- KONDISI JIKA SOAL DRAG & DROP -->
											<?php if ($detail['type'] == 6) : ?>

												<?php if (isset($detail['drag_drop_answer'])) : ?>
													<?php foreach ($detail['drag_drop_answer'] as $key => $val) : ?>

														<!-- looping jawaban siswa -->
														<p style="margin-bottom: 5px;"><i class="fa fa-dot-circle-o me-2"></i><?= $val['answer'] ?></p>
													<?php endforeach ?>
												<?php endif ?>

											<?php endif ?>
										</div>
									</div>
								</div>

							</div>

						<?php endforeach ?>

					</div>
				</div>

			</div>
		</div>

	</div>

</section>


<!-- Modal Input Nilai Siswa -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Masukan Nilai</h1>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="row">
					<div class="form-group">
						<label for="score">Masukan Nilai Siswa</label>
						<input type="hidden" name="exam_student_id" value="<?= (isset($exam_student)) ? $exam_student['es_id'] : '' ?>">
						<!-- <input type="text" name="score" class="form-control" value="<?= (isset($exam_student)) ? $exam_student['exam_total_nilai'] : '' ?>" placeholder="Masukan Nilai Siswa" required > -->
					<input type="text" name="score" id="input-score" class="form-control"
       value="<?= (isset($exam_student)) ? $exam_student['exam_total_nilai'] : '' ?>" 
       placeholder="Masukan Nilai Siswa" required disabled>

					</div>

					<div class="form-group mt-3">
						<label for="notes">Komentar</label>
						<textarea name="notes" class="form-control" rows="5"><?= (isset($exam_student)) ? $exam_student['notes'] : '' ?></textarea>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary text-white save-score-siswa">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- End Modal -->

<!-- Modal Input Poin Soal Essay -->
<div class="modal fade" id="inputPointSoalModal" tabindex="-1" aria-labelledby="inputPointSoalModal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h1 class="modal-title fs-5">Poin Nilai Essay</h1>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">

				<div class="soal-modal-number-badge mb-4"></div>
				<h5 class="question-text-modal-body mb-4"></h5>

				<p class="mb-3">Jawaban siswa:</p>
				<h5 class="student-answer-text-modal-body mb-4"></h5>

				<div class="row">
					<div class="form-group">
						<label for="score">Poin Nilai <span class="text-danger">*</span></label>
						<input type="hidden" name="ea_id">
						<input type="number" name="score_soal" class="form-control mt-2" min="0" max="100" required>
					</div>

					<!-- <div class="form-group mt-3">
						<label for="notes">Komentar</label>
						<textarea name="notes_soal" class="form-control" rows="5"></textarea>
					</div> -->

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary text-white" onclick="submitPointSoal()">Save</button>
			</div>
		</div>
	</div>
</div>
<!-- End Modal -->

<script>
	// create swall alert
	$(document).ready(function() {
		<?php if (!empty($_SESSION['success']) && $_SESSION['success']['success'] == true) : ?>
			Swal.fire({
				icon: 'success',
				title: '<h4 class="text-success"></h4>',
				html: '<span class="text-success"><?= $_SESSION['success']['message'] ?></span>',
				timer: 5000
			});

		<?php endif; ?>

		<?php if (!empty($_SESSION['success']) && $_SESSION['success']['success'] == false) : ?>
			Swal.fire({
				icon: 'error',
				title: '<h4 class="text-danger"></h4>',
				html: '<span class="text-danger"><?= $_SESSION['success']['message'] ?></span>',
				timer: 5000
			});
		<?php endif; ?>
	});

	let resultPoint = '';
	let soalNumberGroupLeft = '';
	let questionText = '';
	let studentAnswerText = '';

	function showInputPointSoal(soalId, eaId) {
		
		// set result point
		resultPoint = $(event.target).closest('.card-header').find('.result-point')[0];
		// set soal number group left
		soalNumberGroupLeft = $(event.target).closest('.card-header').find('.soal-number-group-left')[0];
		// set question text
		questionText = $(event.target).closest('.card').find('.question-text')[0];
		// set student answer text
		studentAnswerText = $(event.target).closest('.card').find('.student-answer-text')[0];

		// set question text modal body
		$('.question-text-modal-body').html(questionText.innerHTML);

		// set soal number badge
		$('.soal-modal-number-badge').html(soalNumberGroupLeft.innerHTML);

		// set student answer text
		let studentAnswer = '';
		if (studentAnswerText) {
			studentAnswer = studentAnswerText.innerHTML;
		} else {
			studentAnswer = 'Tidak ada jawaban';
		}
		$('.student-answer-text-modal-body').html(studentAnswer);

		
		// set ea_id pada modal input nilai
		$('input[name="ea_id"]').val(eaId);

		// show modal input poin soal
		$('#inputPointSoalModal').modal('show');

		// reset input score and notes_soal
		$('input[name="score_soal"]').val('');
		// $('textarea[name="notes_soal"]').val('');
	}

	function submitPointSoal() {
		let eaId = $('input[name="ea_id"]').val(); // ambil ea_id dari input hidden di modal input poin soal
		let score = $('input[name="score_soal"]').val();
		let bobotPoint = $(event.target).closest('.modal-content').find('.bobot-point')[0].innerHTML;

		// hitung total result point.
		let totalResultPoint = 0;
		document.querySelectorAll('.result-point').forEach(function(item) {
			totalResultPoint += parseFloat(item.innerHTML);
		});
		totalResultPoint += parseFloat(score);

		// JIKA SCORE MELEBIHI 100
		if (totalResultPoint > 100) {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Total poin tidak boleh melebihi 100!'
			});
			return;
		}

		// let notes = $('textarea[name="notes_soal"]').val();
	
		if (score < 0 || score > 100) {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Poin harus antara 0 dan 100!'
			});
			return;
		}

		$.ajax({
			url: '<?= base_url('asesmen_standard/save_point_essay') ?>',
			type: 'POST',
			data: {
				ea_id: eaId,
				score: score,
				// notes: notes
			},
			success: function(response) {
				let res = JSON.parse(response);
				if (res.success) {
					Swal.fire({
						icon: 'success',
						title: 'Berhasil',
						text: res.message
					});

					// Update result point result point adalah point yang ditampilkan di card soal sebelah kanan sebelah tombol beri poin
					if (resultPoint) {
						resultPoint.innerHTML = score;
					}

					// HITUNG TOTAL POINT UNTUK DASHBOARD START
					let totalPoint = 0;
					$('.result-point').each(function() {
						let point = parseFloat($(this).text());
						if (!isNaN(point)) {
							totalPoint += point;
						}
						console.log(totalPoint);
					});
					// Update skor total di dashboard
					$('.skor-total').text(totalPoint);

					// update donnut chart
					correctPercentage = totalPoint;
					wrongPercentage = 100 - correctPercentage;
					donnutChart();

					// HITUNG TOTAL POINT UNTUK DASHBOARD END

					// Tutup modal
					$('#inputPointSoalModal').modal('hide');
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: res.message
					});
				}
			}
		});
	}



	// untuk konfirmasi simpan data

  $('.save-score-siswa').on('click', function () {
    // Ambil data dari form modal
    const exam_student_id = $('input[name="exam_student_id"]').val();
    const score = $('input[name="score"]').val();
    const notes = $('textarea[name="notes"]').val();

    // Tampilkan konfirmasi SweetAlert
    Swal.fire({
      title: 'Apakah Anda yakin?',
      text: "Data akan disimpan!",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Ya, simpan',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        // Kirim data via AJAX
        $.ajax({
          url: '<?= base_url("Asesmen/save_score") ?>', // Ganti 'controller' sesuai controller kamu
          method: 'POST',
          data: {
            exam_student_id: exam_student_id,
            score: score,
            notes: notes
          },
          dataType: 'json',
          success: function (res) {
            if (res.success) {
              Swal.fire('Berhasil!', res.message, 'success');
              $('#exampleModal').modal('hide'); // Tutup modal setelah sukses
              // Bisa tambahkan reload data/table jika perlu
            } else {
              Swal.fire('Gagal!', res.message, 'error');
            }
          },
          error: function () {
            Swal.fire('Error!', 'Terjadi kesalahan saat mengirim data.', 'error');
          }
        });
      }
    });
  });

	// end konfirmasi simpan data


	// untuk mengambil total_score

$(document).ready(function () {
  $('#exampleModal').on('shown.bs.modal', function () {
    const totalPoint = $('.skor-total').text().trim(); 
    $('#input-score').val(totalPoint); 
  });
});

//end  stotal_score

</script>
