<style>
	.button-change-image {
		position: absolute;
		bottom: 0;
		left: 0;
		right: 0;
	}

	div[contenteditable="true"]:hover,
	div[contenteditable="true"]:focus {
		border: 2px solid white;
	}

	html {
		height: unset;
	}
</style>

<section class="d-flex h-100" style="margin-top: 40px;">

	<!-- Soal Utama -->
	<div class="h-100 main-soal bg-primary rounded-4 mx-3 w-100">
		<input type="hidden" id="essay_id">

		<div class="d-flex justify-content-center align-item-center px-3 py-2">
			<div class="text-white border rounded-pill badge bg-primary text-center" style="margin-top: -15px; font-size: 14px; height: 30px; z-index: 1;">
				<span>Question</span>
				<span class="counter-soal"></span> / <span class="total-soal"></span>
			</div>
		</div>

		<div class="d-flex border rounded-3 bg-primary-600 position-relative" style="margin-top: -20px;">
			<!-- image soal container -->
			<div class="image-soal-container m-3" style="height: 100%;">
				<img class="rounded-3 m-auto" src="<?= base_url('assets/images/icons/images-empty.png') ?>" alt="image-soal">

				<!-- button zoom image -->
				<button class="btn btn-zoom border-1 rounded-3 bg-white position-absolute" style="bottom: 20px; left: 22px;">
					<i class="fa-solid fa-magnifying-glass-plus"></i>
				</button>
			</div>

			<div class="" style="font-size: 18px;">
				<div id="soalEssay" class="soal-content p-4 justify-content-center align-items-center d-flex text-white w-100" style="height: 100%;">
					ini adalah soal...
				</div>
			</div>
		</div>

		<!-- Section Jawaban -->
		<div class="m-auto text-center mt-4 bg-primary-600 rounded-3" style="padding: 70px;">
			<div contenteditable="true" id="essay_answer" class="d-inline-block text-start text-white p-4 rounded-top-3 border-bottom border-2" style="height: 160px; background-color:rgba(255, 255, 255, 0.2); width: 800px;">Ketik jawaban di sini...</div>
		</div>

		<!-- GROUP FOOTER SOAL & BUTTON NEXT / FINISH -->
		<div class="d-flex footer-essay rounded-3 text-white bg-primary-600 m-0 p-3" style="margin-top: 60px !important;">
			<div class="border-end px-3">
				<h5><?= $exam['title'] ?></h5>
				<h6><?= $exam['subject_name'] ?></h6>
			</div>

			<div class="px-3 mt-3">
				<h6><i class="fa-solid fa-align-left me-2"></i></i>Uraian</h6>
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
			<div class="flex-grow-1 text-end mt-2 submit-answer-essay col-2">
				<button class="btn btn-secondary bg-primary-600 fw-bold" onclick="submitEssay()">kirim jawaban
					<i class="fa-solid fa-paper-plane ms-2"></i>
				</button>
			</div>

			<!-- button soal selanjutnya -->
			<div class="flex-grow-1 text-end mt-2 text-end next-question-essay col-2 m-auto d-none">
				<button class="btn btn-secondary bg-primary-600 fw-bold next-soal">Soal Selanjutnya
					<i class="fa-solid fa-arrow-right ms-2"></i>
				</button>
			</div>

			<!-- button akhiri ujian -->
			<div class="flex-grow-1 text-end mt-2 text-end finish-question-essay col-2 m-auto d-none">
				<button class="btn btn-secondary bg-primary-600 fw-bold" onclick="finishExam()">Akhiri Ujian
					<i class="fa-solid fa-check ms-2"></i>
				</button>
			</div>

		</div>

		<!-- Alert benar -->
		<div class="row alert-essay-true rounded-3 text-white bg-success m-0 mt-3 p-3 d-none" style="">
			<p class="text-center h4">
				<i class="fa-solid fa-check-circle me-2"></i>
				Benar
			</p>
		</div>

		<!-- Alert salah -->
		<div class="row alert-essay-false rounded-3 text-white bg-danger m-0 mt-3 p-3 d-none" style="">
			<p class="text-center h4">
				<i class="fa-solid fa-times-circle me-2"></i>
				Salah
			</p>
		</div>


	</div>

	<!-- response answer -->
	<div class="d-flex flex-column main-response-answer border rounded-3 d-none" style="min-width: 300px;">
		<div class="text-white border rounded-pill badge bg-primary text-center position-relative" style="font-size: 14px; top: -15px; align-self: center; width: 200px;">
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
	// essay_answer on click
	$('#essay_answer').on('click', function() {
		if ($(this).text() == 'Ketik jawaban di sini...') {
			$(this).text('');
		}
	});

	// essay_answer on blur
	$('#essay_answer').on('blur', function() {
		if ($(this).text() == '') {
			$(this).text('Ketik jawaban di sini...');
		}
	});

	// essay answer on keypress remove disabled button submit-answer-essay
	$('#essay_answer').on('keypress', function() {
		$('.submit-answer-essay button').removeAttr('disabled');
	});


	// proses kirim jawaban
	function submitEssay() {
		// ambil data soal master dari local storage
		let soalMaster = JSON.parse(localStorage.getItem('soal_master'));
		$.each(soalMaster, function(i, val) {
			if (val.soal_id == soalIdActive) {
				let answerItems = $('#essay_answer').text();
				let resultAnswer = false;

				// proses pengecekan jawaban siswa dengan jawaban benar
				if (answerItems.toLowerCase() == val.answer.toLowerCase()) {
					resultAnswer = true;
				}

				// proses pengecekan jawaban siswa dengan alternatif jawaban
				if (val.alternative_answer) {
					if(answerItems.toLowerCase() == val.alternative_answer.toLowerCase()) {
						resultAnswer = true;
					}
				}

				$('.submit-answer-essay').addClass('d-none'); // ketika submit button di tekan langsung hide button submit nya

				// jika soal adalah soal terakhir maka munculkan button finish exam
				let counter = $('#student-fill-the-blank-container .counter-soal').text();
				let totalSoal = $('.total-soal').text();
				if (counter == totalSoal) {
					$('.next-question-essay').addClass('d-none'); // hide button next soal
					$('.finish-question-essay').removeClass('d-none'); // show button finish exam
				}

				// jika jawaban benar lakukan hide atau show element
				if (resultAnswer) {
					(new Audio(BASE_URL + "assets/audios/success-1-6297.mp3")).play();
					confetti({
						particleCount: 150,
						spread: 70,
						origin: {
							y: 0.6
						},
					});

					$('.circle-correct').removeClass('d-none');

					$('.footer-essay').addClass('d-none');
					$('.alert-essay-true').removeClass('d-none');

					// hide alert benar setelah 3 detik
					setTimeout(() => {
						$('.footer-essay').removeClass('d-none');
						$('.alert-essay-true').addClass('d-none');

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
					// tampilkan element jawaban salah di footer
					$('.circle-incorrect').removeClass('d-none');

					$('.footer-essay').addClass('d-none');
					$('.alert-essay-false').removeClass('d-none');

					// hide alert salah setelah 3 detik
					setTimeout(() => {
						$('.footer-essay').removeClass('d-none');
						$('.alert-essay-false').addClass('d-none');

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
					$('.next-question-essay').addClass('d-none'); // hide button next soal
					$('.finish-question-essay').removeClass('d-none'); // show button finish exam
				} else {
					$('.next-question-essay').removeClass('d-none'); // show button next soal
					$('.finish-question-essay').addClass('d-none'); // hide button finish exam
				}
			}
		});

	}

</script>
