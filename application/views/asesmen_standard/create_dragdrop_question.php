<div class="modal fade" tabindex="-1" id="mdl-add-dragdrop">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header px-3">
                <div class="d-flex">
                    <button type="button" class="btn btn-back active me-3" id="dragdrop-close-modal">
                        <span class="fs-3">&#x2039;</span>
                    </button>
                    <span class="d-flex align-items-center rounded bg-body-secondary px-3">
                        <img src="<?=html_escape(base_url('assets/images/icons/list-radio.svg'))?>">
                        <span class="ms-2 text-body-secondary">Seret & Lepas</span>
                    </span>

					<span class="ms-3 btn" style="background-color: #E3E4E8; cursor: default;">
						Jumlah Soal (<span class="jumlah-soal-current">0</span>/<span class="jumlah-soal-active-max">10</span>)
					</span>
                </div>
                <div class="d-flex ms-auto">
                    <button type="button" class="btn d-none active" id="btn-dragdrop-response-answer" role="checkbox" aria-checked="false">
                        <img src="<?=base_url('assets/images/icons/light-bulb.svg')?>" height="16" width="16">&nbsp;Tanggapan Jawaban
                    </button>
                    <button type="button" class="btn active mx-3" id="btn-dragdrop-config">
                        <i class="bi bi-gear me-2"></i>Pengaturan Jawaban
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-submit-dragdrop-question"><i class="bi bi-floppy-fill me-2" ></i>Simpan Data</button>
                </div>
            </div>
            <!-- Modal Body -->
             <div class="modal-body bg-custom-assesment">
                <div class="row justify-content-center mt-4">
                    <div class="col-12 col-lg-9">
                        <!-- start card -->
                         <div class="card shadow bg-primary">
                            <form name="frm-add-dragdrop" class="card-body">
                                <input type="hidden" name="item-dragdrop-id" />
                                <div class="row">
                                    <div class="col-4">
                                        <label class="d-block rounded border border-white overflow-hidden" for="dragdrop-file-add" style="height: 180px">
                                            <div class="d-flex flex-column justify-content-center align-items-center bg-box-container img-file-add-container 
                                                        overflow-hidden h-100">
                                                <img id="img-addFile-dragdrop-placeholder" src="<?=base_url('assets/images/icons/image-add-fill.svg')?>" alt="Tambahkan File Pendukung" width="40" height="40">
                                                <span class="text-white mt-1">Tambahkan gambar pendukung</span>
                                            </div>
                                            <div id="img-dragdrop-preview" class="d-none position-relative h-100 w-100 img-preview">
                                                <img src="" class="img-fluid h-100 w-100">
                                                <div class="preview-overlay bg-dark bg-opacity-25 d-flex position-absolute top-0 left-0 h-100 w-100 justify-content-center align-items-center">
                                                    <div class="d-flex flex-nowrap">
                                                        <button type="button" class="btn btn-active bg-white"><i class="bi bi-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="file" class="d-none" id="dragdrop-file-add" name="file-add">
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <div id="dragdrop-question-container" class="d-flex flex-column h-100 w-100 pe-2 border border-white justify-content-center align-items-center bg-box-container rounded">
                                            <div id="dragdrop-question-inner-container" class="d-inline-block ">
                                                <p  id="dragdrop-question-text" 
                                                    class="ms-3 my-0 px-3 py-2 text-white d-inline-block" 
                                                    placeholder="Tulis soal di sini !!!" 
                                                    contenteditable="true" role="textbox"></p>
                                                <span class="d-inline-block border ms-3 border-white text-white" id="dragdrop-question-tag">
                                                    <span class="d-flex flex-nowrap h-100 w-100 d-none">
                                                        <input id="dragdrop-input-question-tag" type="text" class="border border-0 w-100 bg-transparent text-white ps-1" disabled>
                                                        <button type="button" id="ok-question-tag" class="bg-transparent border border-0 text-white" title="OK">&#x21b5;</button>
                                                        <button type="button" id="cancel-question-tag" class="bg-transparent border border-0 text-white" title="Cancel">&times;</button>
                                                    </span>
                                                    <button type="button" class="bg-transparent" id="add-question-tag">
                                                        &plus;&nbsp;
                                                        Opsi Jawaban (F2)
                                                    </button>
                                                </span>
                                            </div>
                                            <input name="dragdrop-question-text" type="hidden" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3 flex-nowrap">
                                    <div class="col">
                                        <div class="bg-box-container dragdrop-answer border d-flex flex-column align-items-center border-white rounded w-100 text-white" id="dragdrop-answer-correct">
                                            <div class="d-inline-block content-info" style="margin-top: 6.5rem">Pilihan opsi jawaban akan ada disini...</div>
                                            <div class="d-flex tag-container"></div>
                                        </div>
                                    </div>
                                    <div class="col d-none">
                                        <div class="bg-box-container dragdrop-answer border d-flex flex-column align-items-center border-white rounded w-100 text-white" id="dragdrop-answer-false">
                                            <div class="d-inline-block content-info" style="margin-top: 6.5rem">Opsi Jawaban Salah</div>
                                            <div class="d-inline-block">
                                                <div class="d-inline-block align-middle" id="dragdrop-false-answer-container">

                                                </div>
                                                <span class="d-inline-block border ms-3 border-white text-white" id="dragdrop-input-false-answer-container">
                                                    <span class="d-flex flex-nowrap h-100 w-100 d-none">
                                                        <input id="dragdrop-input-false-answer" type="text" class="border border-0 w-100 bg-transparent text-white ps-1" disabled>
                                                        <button type="button" id="dragdrop-ok-false-answer" class="bg-transparent border border-0 text-white" title="OK">&#x21b5;</button>
                                                        <button type="button" id="dragdrop-cancel-false-answer" class="bg-transparent border border-0 text-white" title="Cancel">&times;</button>
                                                    </span>
                                                    <button type="button" class="bg-transparent" id="dragdrop-btn-false-answer">
                                                        &plus;&nbsp;
                                                        Opsi Jawaban
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div id="dragdrop-response-answer" class="h-100 w-100 position-absolute top-0 left-0 p-3 bg-primary">
                                <div class="row align-items-center mb-3">
                                    <div class="col-4">
                                        <button type="button" class="btn bg-primary-300 py-0 px-3 d-inline-flex align-items-center text-white" id="dragdrop-response-back" >
                                            <span style="font-size: 3rem;">&#x2039;</span>&nbsp;<span class="ms-1">Kembali ke soal</span>
                                        </button>
                                    </div>
                                    <div class="col-8 d-flex justify-content-end">
                                        <button type="button" class="btn btn-danger p-0 text-white d-flex align-items-center justify-content-center" id="dragdrop-clear-response-answer"><span class="fs-3">&#x27F2;</span></button>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <label class="d-block rounded border border-white overflow-hidden" for="dragdrop-img-response-correct" style="height: 180px">
                                            <div class="d-flex flex-column justify-content-center align-items-center bg-box-container img-file-add-container 
                                                        overflow-hidden h-100">
                                                <img id="img-responseCorrect-placeholder" src="<?=base_url('assets/images/icons/image-add-fill.svg')?>" alt="Tambahkan File Pendukung" width="40" height="40">
                                                <span class="text-white mt-1">Tambahkan gambar pendukung</span>
                                            </div>
                                            <div id="img-dragdrop-responseCorrect-preview" class="d-none position-relative h-100 w-100 img-preview">
                                                <img src="" class="img-fluid h-100 w-100">
                                                <div class="preview-overlay bg-dark bg-opacity-25 d-flex position-absolute top-0 left-0 h-100 w-100 justify-content-center align-items-center">
                                                    <div class="d-flex flex-nowrap">
                                                        <button type="button" class="btn btn-active bg-white"><i class="bi bi-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="file" class="d-none" id="dragdrop-img-response-correct" name="img-response-correct">
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <div id="dragdrop-correct-answer-container" class="d-flex flex-column h-100 w-100 pe-2 border border-white justify-content-center align-items-center bg-box-container rounded">
                                        <input type="text" class="form-control border-none outline-none text-white input-question h-100 w-100" placeholder="Tanggapan jika jawaban benar" name="dd-answer-correct-text">
                                           
                                        </div>
                                    </div>
                                </div>
                                <hr class="border border-white"/>
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <label class="d-block rounded border border-white overflow-hidden" for="dragdrop-img-response-false" style="height: 180px">
                                            <div class="d-flex flex-column justify-content-center align-items-center bg-box-container img-file-add-container 
                                                        overflow-hidden h-100">
                                                <img id="img-responseFalse-placeholder" src="<?=base_url('assets/images/icons/image-add-fill.svg')?>" alt="Tambahkan File Pendukung" width="40" height="40">
                                                <span class="text-white mt-1">Tambahkan gambar pendukung</span>
                                            </div>
                                            <div id="img-dragdrop-responseFalse-preview" class="d-none position-relative h-100 w-100 img-preview">
                                                <img src="" class="img-fluid h-100 w-100">
                                                <div class="preview-overlay bg-dark bg-opacity-25 d-flex position-absolute top-0 left-0 h-100 w-100 justify-content-center align-items-center">
                                                    <div class="d-flex flex-nowrap">
                                                        <button type="button" class="btn btn-active bg-white"><i class="bi bi-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="file" class="d-none" id="dragdrop-img-response-false" name="img-response-false">
                                        </label>
                                    </div>
                                    <div class="col-8">
                                    <div id="dragdrop-correct-answer-container" class="d-flex flex-column h-100 w-100 pe-2 border border-white justify-content-center align-items-center bg-box-container rounded">
                                        <input type="text" class="form-control border-none outline-none text-white input-question h-100 w-100" placeholder="Tanggapan jika jawaban salah" name="dd-answer-false-text">
                                           
                                        </div>
                                    </div>
                                </div>
                                
                               
                            </div>
                         </div>
                        <!-- end card -->
                    </div>
                </div>
             </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="mdl-dragdrop-config">
    <div class="modal-dialog modal-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary" data-bs-theme="dark">
                <h5 class="modal-title text-white">Pengaturan Jawaban</h5>
                <button type="button" class="btn-close" aria-label="Close" id="dragdrop-close-config" ></button>
            </div>
            <div class="modal-body p-0">
                <ul class="list-group list-group-flush" id="dragdrop-config-ul">
                    <li class="list-group-item d-flex flex-nowrap align-items-center">
                        <span class="fs-6 d-inline-block">Nilai Jawaban&nbsp;<i class="bi bi-info-circle-fill"></i></span>
    
                        <select class="form-select form-select-sm ms-auto w-25 py-2" id="dragdrop-config-score">
                            <option value="10">10 Points</option>
                            <option value="20">20 Points</option>
                            <option value="30">30 Points</option>
                            <option value="40">40 Points</option>
                            <option value="50">50 Points</option>
                            <option value="60">60 Points</option>
                            <option value="70">70 Points</option>
                            <option value="80">80 Points</option>
                            <option value="90">90 Points</option>
                            <option value="100">100 Points</option>
                        </select>
                    </li>
                    <li class="list-group-item d-flex flex-nowrap align-items-center">
                        <span class="fs-6 d-inline-block">Respon Jawaban&nbsp;<i class="bi bi-info-circle-fill"></i></span>
    
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input" type="checkbox" role="switch" name="config[funfact]" id="dragdrop-respon-jawaban">
                            <label class="form-check-label" for="dragdrop-respon-jawaban"></label>
                        </div>
                    </li>
                    <li class="list-group-item d-flex flex-nowrap align-items-center">
                        <span class="fs-6 d-inline-block">Opsi Jawaban Salah&nbsp;<i class="bi bi-info-circle-fill"></i></span>
    
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input" type="checkbox" role="switch" name="config[falseAnswer]" id="dragdrop-false-answer">
                            <label class="form-check-label" for="dragdrop-false-answer"></label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>


<script>
// untuk foto max 2 mb
document.getElementById('dragdrop-file-add').addEventListener('change', function () {
	const file = this.files[0];
	const allowedTypes = [
		'image/jpeg', 'image/png',
		'application/pdf',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'application/vnd.ms-powerpoint',
		'audio/mpeg',
		'video/mp4'
	];

	if (file) {
		// Cek tipe file
		if (!allowedTypes.includes(file.type)) {
			Swal.fire({
				html: `
					<div style="text-align: center;">
						<div class="icon-faild" style="font-size: 40px; color: red;">&#10060;</div>
						<h2 style="margin:0; font-size:1.4em;">Tipe File Tidak Didukung</h2>
						<p style="margin-top:8px;">Hanya file JPG, JPEG, PNG, DOCX, XLSX, PPT, PDF, MP3, dan MP4 yang diperbolehkan.</p>
					</div>
				`,
				showCloseButton: false,
				showConfirmButton: true,
				confirmButtonText: 'Upload Ulang',
				customClass: {
					confirmButton: 'swal-wide-button'
				}
			});
			this.value = "";
			return;
		}

		// Cek ukuran file
		const maxSize = 2 * 1024 * 1024; // 2MB
		if (file.size > maxSize) {
			Swal.fire({
				html: `
					<div style="text-align: center;">
						<div class="icon-faild" style="font-size: 40px; color: red;">&#10060;</div>
						<h2 style="margin:0; font-size:1.4em;">Ukuran File Terlalu Besar</h2>
						<p style="margin-top:8px;">Ukuran file melebihi 2MB. Silakan pilih file yang lebih kecil atau gunakan tautan.</p>
					</div>
				`,
				showCloseButton: false,
				showConfirmButton: true,
				confirmButtonText: 'Upload Ulang',
				customClass: {
					confirmButton: 'swal-wide-button'
				}
			});
			this.value = "";
		}
	}
});
	// end max 2 mb
</script>

<!-- 

<div class="d-flex flex-nowrap p-3 w-100 align-items-center overflow-auto">
    <span class="fs-6 d-inline-block">Nilai Jawaban&nbsp;<i class="bi bi-info-circle-fill"></i></span>
    
    <select class="form-select form-select-sm ms-auto w-25 py-2" id="dragdrop-config-score">
        <option value="10">10 Points</option>
        <option value="20">20 Points</option>
        <option value="30">30 Points</option>
        <option value="40">40 Points</option>
        <option value="50">50 Points</option>
        <option value="60">60 Points</option>
        <option value="70">70 Points</option>
        <option value="80">80 Points</option>
        <option value="90">90 Points</option>
        <option value="100">100 Points</option>
    </select>
</div>
<div class="d-flex flex-nowrap p-3 w-100 align-items-center overflow-auto">
    <span class="fs-6 d-inline-block">Nilai Jawaban&nbsp;<i class="bi bi-info-circle-fill"></i></span>
    
    
</div>

-->
