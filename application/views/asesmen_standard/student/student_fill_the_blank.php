<style>
	.button-change-image {
		position: absolute;
		bottom: 0;
		left: 0;
		right: 0;
	}
</style>

<!-- main soal -->
<section class="d-flex h-100" style="margin-top:40px;">
	<div class="h-100 main-soal bg-primary rounded-4 mx-3 w-100">
		<input type="hidden" id="fiil_the_blank_id" />

		<div class="text-white border rounded-pill badge position-relative bg-primary text-center" style="top: -15px; left: 50%; font-size: 14px; height: 30px; z-index: 1;">
			<span>Question</span>
			<span class="counter-soal"></span> / <span class="total-soal"></span>
		</div>

		<div class="d-flex border rounded-3 bg-primary-600 position-relative" style="top: -30px;">
			<!-- image soal container -->
			<div class="image-soal-container m-3" style="height: 100%;">
				<img class="rounded-3 m-auto" src="<?= base_url('assets/images/icons/image-empty.png') ?>" alt="image" style="">

				<!-- button zoom image -->
				<button class="btn btn-zoom border-1 rounded-3 bg-white position-absolute" style="bottom: 20px; left: 22px;">
					<i class="fa-solid fa-magnifying-glass-plus"></i>
				</button>
			</div>


			<div class="" style="font-size: 18px;">
				<div id="soalFillTheBlank" class="soal-content p-4 justify-content-center align-items-center d-flex text-white w-100" style="height: 100%;">
					ini adalah soal...
				</div>
			</div>

		</div>


		<div class="row text-white rounded-2 justify-content-center mx-0 bg-primary-600" style="height: 296px;">
			<p class="text-center" style="padding-top: 75px; margin-bottom:-50px;">Ketik jawaban mu didalam kotak</p>

			<div class="answer-container d-block text-center">
				<?php for ($i = 0; $i < 10; $i++) : ?>
					<span contenteditable="true" class="h3 answer-item bg-primary-200 p-2 rounded-3 text-white m-1 d-inline-block" style="cursor: pointer; width: 50px; height:50px;">

					</span>
				<?php endfor ?>
			</div>
		</div>

		<!-- GROUP FOOTER SOAL & BUTTON NEXT / FINISH -->
		<div class="d-flex footer-fill-the-blank rounded-3 text-white bg-primary-600 m-0 mt-3 p-3">
			<div class="border-end px-3">
				<h5><?= $exam['title'] ?></h5>
				<h6><?= $exam['subject_name'] ?></h6>
			</div>

			<div class="px-3 mt-3">
				<h6><i class="fa-regular fa-square me-2"></i>Isi Yang Kosong</h6>
			</div>

			<div class="my-0 py-3 px-3 text-success h5 border-start d-none circle-correct">
				<i class="fa-solid fa-check-circle me-2"></i>
				Benar
			</div>

			<div class="my-0 py-3 px-3 text-danger h5 border-start d-none circle-incorrect">
				<i class="fa-solid fa-times-circle me-2"></i>
				Salah
			</div>

			<!-- button kirim jawaban -->
			<div class="flex-grow-1 text-end mt-2 submit-answer-fill-the-blank col-2">
				<button class="btn btn-secondary bg-primary-600 fw-bold" onclick="submitFillTheBlank()">kirim jawaban
					<i class="fa-solid fa-paper-plane ms-2"></i>
				</button>
			</div>

			<!-- button soal selanjutnya -->
			<div class="flex-grow-1 text-end mt-2 text-end next-question-fill-the-blank col-2 m-auto d-none">
				<button class="btn btn-secondary bg-primary-600 fw-bold next-soal">Soal Selanjutnya
					<i class="fa-solid fa-arrow-right ms-2"></i>
				</button>
			</div>

			<!-- button akhiri ujian -->
			<div class="flex-grow-1 text-end mt-2 text-end finish-question-fill-the-blank col-2 m-auto d-none">
				<button class="btn btn-secondary bg-primary-600 fw-bold" onclick="finishExam()">Akhiri Ujian
					<i class="fa-solid fa-check ms-2"></i>
				</button>
			</div>

		</div>

		<!-- Alert benar -->
		<div class="row alert-fill-the-blank-true rounded-3 text-white bg-success m-0 mt-3 p-3 d-none" style="">
			<p class="text-center h4">
				<i class="fa-solid fa-check-circle me-2"></i>
				Benar
			</p>
		</div>

		<!-- Alert salah -->
		<div class="row alert-fill-the-blank-false rounded-3 text-white bg-danger m-0 mt-3 p-3 d-none" style="">
			<p class="text-center h4">
				<i class="fa-solid fa-times-circle me-2"></i>
				Salah
			</p>
		</div>
	</div>

	<!-- response answer -->
	<div class="mb-5 main-response-answer border rounded-3 d-none" style="min-width: 300px;">
		<div class="text-white border rounded-pill badge bg-primary text-center position-relative" style="font-size: 14px; top: -15px; left: 20%;">
			<span>Tanggapan Jawaban</span>
		</div>

		<div class="image-response-answer-container m-3" style="height: 200px;">
			<img class="rounded-3 m-auto w-100 h-100" src="<?= base_url('assets/images/icons/image-empty.png') ?>" alt="image">
		</div>

		<div class="response-answer-content p-4 text-white" style="">
			ini adalah jawaban...

		</div>
	</div>
</section>

<script>
	function submitFillTheBlank() {
		// ambil data soal master dari local storage
		let soalMaster = JSON.parse(localStorage.getItem('soal_master'));
		$.each(soalMaster, function(idx, val) {

			if (val.soal_id == soalIdActive) {

				// proses ambil jawaban siswa dari element
				let answerItems = $('.answer-item').text();
				let resultAnswer = false;

				// proses pengecekan jawaban siswa dengan jawaban benar
				// jika menggunakan variasi jawaban
				if (val.variation_answer) {
					if (answerItems.replaceAll(' ', '') == val.answer.replaceAll(' ', '')) {
						resultAnswer = true;
					}
				} else {
					if (answerItems.replaceAll(' ', '').toLowerCase() == val.answer.replaceAll(' ', '').toLowerCase()) {
						resultAnswer = true;
					}
				}

				// jika soal memiliki jawaban alternatif
				if (val.alternative_answer) {
					// jika menggunakan variasi jawaban
					if (val.variation_answer) {
						if (answerItems.replaceAll(' ', '') == val.alternative_answer.replaceAll(' ', '')) {
							resultAnswer = true;
						}
					} else {
						if (answerItems.replaceAll(' ', '').toLowerCase() == val.alternative_answer.replaceAll(' ', '').toLowerCase()) {
							resultAnswer = true;
						}
					}
				}

				$('.submit-answer-fill-the-blank').addClass('d-none'); // langsung hide button submit

				// jika soal adalah soal terakhir maka munculkan button finish exam
				let counter = $('#student-fill-the-blank-container .counter-soal').text();
				let totalSoal = $('.total-soal').text();
				if (counter == totalSoal) {
					$('.next-question-fill-the-blank').addClass('d-none'); // hide button next soal
					$('.finish-question-fill-the-blank').removeClass('d-none'); // show button finish exam
				}

				// jika jawaban benar lakukan hide atau show element
				if (resultAnswer) {
					(new Audio(BASE_URL + "assets/audios/success-1-6297.mp3")).play(); // play audio

					// confetti animation
					confetti({
						particleCount: 150,
						spread: 70,
						origin: {
							y: 0.6
						},
					});

					$('.answer-item').addClass('bg-success');
					$('.circle-correct').removeClass('d-none');
					$('.circle-incorrect').addClass('d-none');

					$('.footer-fill-the-blank').addClass('d-none');
					$('.alert-fill-the-blank-true').removeClass('d-none');

					setTimeout(() => {
						$('.footer-fill-the-blank').removeClass('d-none');
						$('.alert-fill-the-blank-true').addClass('d-none');

						// jika soal memiliki response jawaban benar
						// jika response jawaban berupa gambar dan text
						if (val.response_correct_answer && val.response_correct_answer_file) {
							$('.main-response-answer').removeClass('d-none');
							$('.image-response-answer-container').removeClass('d-none');
							$('.image-response-answer-container img').attr('src', ADMIN_URL + val.response_correct_answer_file);
							$('.response-answer-content').html(val.response_correct_answer);
						}

						// jika response jawaban berupa text
						if (val.response_correct_answer && !val.response_correct_answer_file) {
							$('.main-response-answer').removeClass('d-none');
							$('.image-response-answer-container').addClass('d-none');
							$('.response-answer-content').html(val.response_correct_answer);
						}

					}, 3000);


				} else {
					(new Audio(BASE_URL + "assets/audios/trumpet-fail-242645.mp3")).play();

					// ubah warna jawaban siswa menjadi merah
					$('.answer-item').addClass('bg-danger');

					// tampilkan element jawaban salah di footer
					$('.circle-incorrect').removeClass('d-none');
					$('.circle-correct').addClass('d-none');

					$('.footer-fill-the-blank').addClass('d-none');
					$('.alert-fill-the-blank-false').removeClass('d-none');

					setTimeout(() => {
						$('.footer-fill-the-blank').removeClass('d-none');
						$('.alert-fill-the-blank-false').addClass('d-none');

						// jika soal memiliki response jawaban salah
						// jika response jawaban berupa gambar dan text
						if (val.response_wrong_answer && val.response_wrong_answer_file) {
							$('.main-response-answer').removeClass('d-none');
							$('.image-response-answer-container').removeClass('d-none');
							$('.image-response-answer-container img').attr('src', ADMIN_URL + val.response_wrong_answer_file);
							$('.response-answer-content').html(val.response_wrong_answer);
						}

						// jika response jawaban berupa text
						if (val.response_wrong_answer && !val.response_wrong_answer_file) {
							$('.main-response-answer').removeClass('d-none');
							$('.image-response-answer-container').addClass('d-none');
							$('.response-answer-content').html(val.response_wrong_answer);
						}
					}, 3000);

				}

				// update local storage soal dengan jawaban siswa
				let soal = JSON.parse(localStorage.getItem('soal'));
				soal.forEach((item, idx) => {
					if (item.soal_id == soalIdActive) {
						soal[idx]['exam_answer'] = answerItems;
						soal[idx]['result_answer'] = (resultAnswer) ? 1 : 0;
					}
				});

				// update kembali local storage soal dengan soal yang sudah terisi jawaban
				localStorage.setItem('soal', JSON.stringify(soal));

				// lakukan penghitungan kembali jumlah soal yang belum di jawab
				let soalNotAnswered = soal.filter((item) => !item.exam_answer);

				if (soalNotAnswered.length == 0) {
					$('.next-question-fill-the-blank').addClass('d-none'); // hide button next soal
					$('.finish-question-fill-the-blank').removeClass('d-none'); // show button finish exam
				} else {
					$('.next-question-fill-the-blank').removeClass('d-none'); // show button next soal
					$('.finish-question-fill-the-blank').addClass('d-none'); // hide button finish exam
				}
			}
		});
	}

	function kembaliKeAsesmen() {
		window.location.href = BASE_URL + "asesmen";
	}
</script>
