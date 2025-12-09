<!-- QUILLJS CSS -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<style>
	p { color: black;}
</style>

<div class="row">

    <div class="col-12">

        <div class="card">

            <div class="card-body">

            <form name="form-add" id="form-add" class="d-flex flex-column">
            <div class="row">
                <div class="col-12 col-lg-4">
                    <h4 class="mb-4 text-underline">KETERANGAN</h4>
                    <div class="row align-items-top mb-3">
						<input type="hidden" name="sekolah_id" value="<?=$_SESSION['sekolah_id']?>">

                        <div class="col-4">
                            <label class="m-0">Kode Soal <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <input type="text" class="form-control form-control-sm" name="a_soal_code" required/>
                        </div>
                        <!-- <div class="col-4">
                            <label class="m-0">No. Urut <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <input type="number" class="form-control form-control-sm" name="a_soal_no" min="1" required/>
                        </div> -->
                        <div class="col-4 pr-0">
                            <label class="m-0">Mata Pelajaran <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <select type="text" class="form-control form-control-sm col-10" name="a_soal_subject" data-live-search="true" required></select>
                            <button type="button" id="reset-subject" class="btn btn-sm btn-primary"><i class="fas fa-undo"></i></button>
                            <input type="hidden" name="a_soal_subject_text">
                        </div>
 
                        <div class="col-4">
                            <label class="m-0">Jenis Soal <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <select class="form-control form-control-sm col-10 selectpicker" name="a_soal_type" data-live-search="true" required>
                              <option value="1">Pilihan Ganda</option>
                              <option value="2">Essay</option>
                              <option value="3">BENAR/SALAH</option>
                            </select>
                            <button type="button" id="reset-type" class="btn btn-sm btn-primary"><i class="fa fa-undo"></i></button>
                            <input type="hidden" name="a_soal_type_text">
                        </div> 
                        <div class="col-4">
                            <label class="m-0">Jawaban <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <textarea class="form-control form-control-sm" name="a_soal_answer" rows="8" required></textarea>
                            <small>Untuk jawaban pilihan ganda gunakan kunci (contoh: a, b)</small>
                        </div>
                        <div class="col-4">
                            <label class="m-0">File pendukung</label>
                        </div>
                        <div class="col-8 mb-3">
                            <div class="custom-file mt-2">
                                <input type="file" class="custom-file-input" id="a_soal_file" name="a_soal_file">
                                <label class="custom-file-label overflow-hidden" id="file-label" for="a_soal_file" data-browse="Unggah File">Pilih File</label>
                            </div>
                            <small>Upload file hanya support extensi video/mp4, image/png, image/jpeg, image/jpg, image/webp</small>
                        </div>
                    </div>
                    
                </div>
                <div class="col">
                    <h4 class="mb-4 text-underline">SOAL</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex-column">
                                <label for="">Deskripsi Soal <span class="text-danger"><strong>*</strong></span</label>
                                <textarea name="a_soal_detail" class="form-control d-none" id="detail-soal"></textarea>
								<div id="editor" class="" style="height: 200px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row d-none" id="mc">
                      <div class="col-12">
                        <h4>Jawaban Pilihan Ganda</h4>
                        <table id="table-choices" class="table table-sm w-100">
                          <thead>
                            <tr>
                              <th>Pilihan</th>
                              <th>Teks</th>
                              <th>File</th>
                            </tr>
                          </thead>
                          <tbody>

                          </tbody>
                        </table>
                      </div>
                    </div>
                </div>
            </div>
            <span class="w-100 d-flex flex-nogrow">
                <!-- PRogress bar-->
                <div class="progress w-100 mt-2" id="import-progress-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <!-- end PRogress bar-->
            </span>
                <input type="hidden" name="a_id" />
                <input type="hidden" name="xsrf" />
                <span class="mt-3 w-100 d-flex flex-nogrow flex-nowrap justify-content-end">
                    <input type="reset" class="btn btn-secondary" value="Ulangi">
                    <input type="submit" class="btn btn-primary ml-3" value="Simpan">
                </span>
            </form>

            </div>

        </div>

    </div>

</div>

<?php if(isset($_GET['edit']) && $_GET['edit'] == 1):?>
    <script id="data-soal" type="application/json">
       <?=json_encode($soal, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT)?>
    </script>
<?php endif; ?>

<!-- <script src="https://cdn.tiny.cloud/1/yj3hulphodusa2w55igxgbtlhapz0w10lja2gm88r8z0w195/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script> -->
<!-- Include the Quill library -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<script>
	var quill = new Quill('#editor', {
		theme: 'snow',
		placeholder: 'Tuliskan detail pertanyaan disini...'
	});

	$('#form-add').submit(e => {
		$('#detail-soal').val(quill.container.firstChild.innerHTML);
	});

	let deskripsi = '<?=isset($soal['question']) ? $soal['question'] : ''?>';
	if(deskripsi != '')
		quill.container.firstChild.innerHTML = deskripsi;

</script>
