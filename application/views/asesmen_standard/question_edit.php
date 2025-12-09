<?php foreach ($questions as $key => $question) : ?>

<?php $cardTitle;
if ($question['type'] == 4) {
	$cardTitle = 'Soal Fiil in the blank';
	$actionEdit = 'editFillTheBlank(this)';
} else if ($question['type'] == 2) {
	$cardTitle = 'Soal Uraian';
	$actionEdit = 'editFillTheBlank(this)';
} else if ($question['type'] == 1) {
	$cardTitle = 'Soal Pilihan Ganda';
	$actionEdit = 'editPilihanGanda(this)';
} else if ($question['type'] == 3) {
	$cardTitle = 'Soal Benar Salah';
	$actionEdit = 'editTrueOrFalse(this)';
} else if ($question['type'] == 5) {
	$cardTitle = 'Soal Menjodohkan';
	$actionEdit = 'editMenjodohkan(this)';
} else if ($question['type'] == 6) {
	$cardTitle = 'Soal Seret Lepas';
	$actionEdit = 'editSeretLepas(this)';
} else {
	$cardTitle = '-';
	$actionEdit = '';
}
?>
<div class="card card-group-custom rounded-4 mb-3" data="<?= $key ?>" jenis-soal="<?= $question['type'] ?>">
	<div class="card-header pt-3  rounded-4 rounded-bottom-0 bg-primary text-white">
		<div class="d-flex justify-content-between">
			<span><?= $cardTitle ?></span>
			<div class="dropdown text-white me-2" onclick="" style="cursor: pointer;">
				<i class="dropdown-toggle fa-solid fa-ellipsis-vertical px-2" data-bs-toggle="dropdown" aria-expanded="false"></i>
				<div class="dropdown-menu container text-center">
					<div class="row row-cols-auto justify-content-center">
						<div class="col pe-0">
							<button class="btn btn-sm btn-danger text-white" onclick="deleteCard(this)">
								<i class="fa fa-trash"></i>
							</button>
						</div>
						<div class="col">
							<button class="btn btn-sm btn-primary text-white" onclick="editCard(this)">
								<i class="fa fa-edit"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="card-body">

		<div class="list-soal-container">

			<?php foreach ($question['detail'] as $key2 => $detail) : ?>
				<div class="card border-start-0 border-top-0 border-end-0 mb-3 rounded-0 mt-5">

					<div class="card-header bg-white border-0 p-0">
						<div class="d-flex justify-content-between">
							<div class="btn btn-light border rounded-3 no-soal" style="width: 100px;">
								<?= $key2 + 1 ?>
							</div>


							<div class="d-flex btn-group-fill-the-blank" onmouseenter="showBtnGroupFillTheBlankHover(this)">
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

							<div class="d-flex btn-group-fill-the-blank-hover d-none" onmouseleave="showBtnGroupFillTheBlank(this)">

								<button class="btn btn-light border rounded-3 me-2" onclick="tinjauSoal(this)">
									<i class="fa-solid fa-eye"></i> Tinjau Soal
								</button>

								<button class="btn btn-light border rounded-3 me-2" onclick="<?= $actionEdit ?>">
									<i class="fa fa-edit me-2"></i> Ubah Soal
								</button>

								<button class="btn btn-light border rounded-3 me-2" onclick="deleteQuestionList(this)">
									<i class="fa fa-trash"></i>
								</button>

							</div>
						</div>
					</div>

					<div class="card-body p-0 pb-3 mt-3">
						<div class="d-flex justify-content-between">
							<div>
								<input type="hidden" name="question_id[]" value="<?= $detail['soal_id'] ?>">

								<?php if ($detail['question_file']) : ?>
									<img src="<?= $this->config->item('admin_url') ?><?= $detail['question_file'] ?>" class="img-fluid img-question" alt="Gambar Soal">
								<?php endif ?>

								<p><?= $detail['question'] ?></p>

								<!-- KONDISI JIKA SOAL PILIHAN GANDA -->
								<?php if ($question['type'] == 1) : ?>
									<div class="row mt-3" style="line-height: 2;">
										<div class="col-6">
											<p class="">A. <?= $detail['choice_a'] ?></p>
											<?php if ($detail['choice_a_file']) : ?>
												<img src="<?= $this->config->item('admin_url') ?><?= $detail['choice_a_file'] ?>" class="img-fluid img-question-choice" alt="Gambar A">
											<?php endif ?>

										</div>

										<div class="col-6">
											<p class=""> B. <?= $detail['choice_b'] ?></p>
											<?php if ($detail['choice_b_file']) : ?>
												<img src="<?= $this->config->item('admin_url') ?><?= $detail['choice_b_file'] ?>" class="img-fluid img-question-choice" alt="Gambar B">
											<?php endif ?>
										</div>

										<div class="col-6">
											<p class="">C. <?= $detail['choice_c'] ?></p>
											<?php if ($detail['choice_c_file']) : ?>
												<img src="<?= $this->config->item('admin_url') ?><?= $detail['choice_c_file'] ?>" class="img-fluid img-question-choice" alt="Gambar C">
											<?php endif ?>
										</div>

										<div class="col-6">
											<p class="">D. <?= $detail['choice_d'] ?></p>
											<?php if ($detail['choice_d_file']) : ?>
												<img src="<?= $this->config->item('admin_url') ?><?= $detail['choice_d_file'] ?>" class="img-fluid img-question-choice" alt="Gambar D">
											<?php endif ?>
										</div>
									</div>

								<?php endif ?>

							</div>
						</div>
					</div>

				</div>

			<?php endforeach ?>
		</div>

		<div class="text-center pb-5 pt-3 add-soal-container">
			<button onClick="addSoal()" class="btn btn-lg add-soal" style="background-color: #D4D1E9; color: #281B93; border-color:#281B93; border-width: 1px; font-size: 12px;" data-bs-toggle="modal" data-bs-target="#optionAddQuestionModal">
				+ Tambahkan Soal (
				<span id="count-soal<?= $key ?>">
					<?= count($question['detail']) ?>
				</span> /
				<span id="total-soal<?= $key ?>">
					<?= count($question['detail']) ?>
				</span>)
			</button>
		</div>

	</div>
</div>

<?php endforeach ?>
