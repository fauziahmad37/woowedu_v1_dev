<style>
	.form-check-input,
	.fa-check {
		width: 24px !important;
		height: 24px !important;
	}

	.box-answer-body {
		background-color: var(--bs-primary-400);
	}

	.box-answer-drop {
		border-style: dashed;
		border-width: 1px;
	}
</style>

<div id="modal-preview-question" class="modal fade" tabindex="-1">

	<div class="modal-dialog modal-fullscreen">
		<div class="modal-content">
			<div class="modal-header bg-primary border-0">
				<h5 class="modal-title"></h5>
				<button type="button" class="btn-close btn-close-white me-3" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body bg-primary p-5">

				<section class="soal d-flex border rounded-3 bg-primary-600 position-relative" style="min-height: 220px;">
					<!-- image soal container -->
					<div class="image-soal-container m-3" style="height: 100%;">
						<img class="rounded-3 m-auto" src="<?= base_url('assets/images/icons/images-empty.png') ?>" alt="image-soal" style="max-height: 200px;">

						<!-- button zoom image -->
						<button class="btn btn-zoom border-secondary border-1 rounded-3 bg-white position-absolute" style="bottom: 20px; left: 22px;">
							<i class="fa-solid fa-magnifying-glass-plus"></i>
						</button>
					</div>

					<div class="" style="font-size: 18px;">
						<div id="question" class="soal-content p-4 justify-content-center align-items-center d-flex text-white w-100" style="height: 100%;">
							ini adalah soal...
						</div>
					</div>
				</section>

				<!-- Multiple Choice Answer -->
				<section class="d-flex mt-4 rounded-3 multiple-choice-answer bg-primary-600"></section>

				<!-- Essay Answer -->
				<section class="d-flex mt-4 rounded-3 essay-answer bg-primary-600">
					<div class="m-auto text-center mt-4 bg-primary-600 rounded-3" style="padding: 70px;">
						<div contenteditable="true" id="essay_answer" class="d-inline-block text-start text-white p-4 rounded-top-3 border-bottom border-2" style="height: 160px; background-color:rgba(255, 255, 255, 0.2); width: 800px;"></div>
					</div>
				</section>

				<!-- True or False Answer -->
				<div class="row justify-content-center mt-4 tof-answer" style="height: 296px;">

					<div class="col-6" style="height: 260px;">
						<div class="bg-dark h-100 p-2 rounded-3 btn-choice-container">
							<button class="btn btn-success text-white w-100 h-100 border border-white" style="border-style: dashed !important;">Pilihan Benar</button>
						</div>

						<div class="form-check text-white text-center justify-content-center align-items-center d-flex">
							<input type="checkbox" class="form-check-input mt-3" id="choice_true">
						</div>
					</div>

					<div class="col-6" style="height: 260px;">
						<div class="bg-dark h-100 p-2 rounded-3 btn-choice-container">
							<button class="btn btn-danger text-white w-100 h-100 border border-white" style="border-style: dashed !important;">Pilihan Salah</button>
						</div>

						<div class="form-check text-white text-center justify-content-center align-items-center d-flex">
							<input type="checkbox" class="form-check-input mt-3" id="choice_false">
						</div>
					</div>

				</div>

				<!-- Fill The Blank Answer -->
				<div class="row ftb-answer mt-4 text-white rounded-2 justify-content-center mx-0 bg-primary-600" style="height: 296px;">
					<p class="text-center" style="padding-top: 75px; margin-bottom:-50px;">Ketik jawaban mu didalam kotak</p>

					<div class="answer-container d-block text-center">
					</div>
				</div>

				<!-- Pairing Answer -->
				<section class="d-flex mt-4 rounded-3 pairing-answer bg-primary-600">
					<div class="m-auto text-center mt-4 bg-primary-600 rounded-3 w-100">
						<div class="position-relative list-pairing rounded mx-1 justify-content-center bg-primary-600 p-2 row" style="height: 380px;">
							
						</div>
					</div>
				</section>

				<!-- Drag Drop Answer -->
				<section class="d-flex mt-4 rounded-3 drag-drop-answer bg-primary-600">
					<div class="m-auto text-center mt-4 bg-primary-600 rounded-3 w-100">
						<div class="position-relative list-drag-drop rounded mx-1 justify-content-center bg-primary-600 p-2" style="height: 380px; align-content: center;">
							
						</div>
					</div>
				</section>

				<section class="d-flex soal-footer bg-primary-600 mt-4 p-4 rounded-3">
					<div class="title border-end pe-3 text-white">
						<h5 class="exam-title"><?=$title?></h5>
						<h6 class="subject-name">Ilmu Pengetahuan Alam</h6>
					</div>
					<div class="question-type text-white m-auto ms-3">
						<h5 class="m-0">
							<i class="fas fa-tasks"></i>
							Pilihan Ganda
						</h5>
					</div>
				</section>
			</div>

		</div>

	</div>


</div>

<!-- modal zoom image -->
<div class="modal fade" id="modal-zoom-image" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content shadow">
			<div class="modal-header">
				<h5 class="modal-title">Gambar Soal</h5>
				<button type="button" class="btn-close btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body p-5">

			</div>
		</div>
	</div>
</div>
<!-- modal zoom image -->
