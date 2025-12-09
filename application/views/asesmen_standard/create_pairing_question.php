<div id="mdl-add-pairing" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">

            <div class="modal-header px-3">
                <div class="d-flex">
                    <button type="button" class="btn btn-back active me-3" data-bs-dismiss="modal" data-bs-target="#mdl-add-pairing">
                        <span class="fs-3">&#x2039;</span>
                    </button>
                    <span class="d-flex align-items-center rounded bg-body-secondary px-3">
                        <img src="<?=html_escape(base_url('assets/images/icons/list-radio.svg'))?>">
                        <span class="ms-2 text-body-secondary">Menjodohkan</span>
                    </span>
                </div>
                <div class="d-flex ms-auto">
                    <button type="button" class="btn active" id="btn-pairing-config"><i class="bi bi-gear me-2"></i>Pengaturan Jawaban</button>
                    <button type="button" class="btn btn-primary ms-3" id="btn-submit-pairing-question"><i class="bi bi-floppy-fill me-2" ></i>Simpan Data</button>
                </div>
            </div>
            <div class="modal-body bg-custom-assesment">
                <div class="row justify-content-center mt-4">
                    <div class="col-12 col-lg-9">
                        <div class="card shadow bg-primary">
                            <div class="card-body">
                                <form name="frm-pairing-question" onsubmit="return false">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="d-block rounded border border-white overflow-hidden" for="pairing-file-add" style="height: 150px">
                                                <div class="d-flex flex-column justify-content-center align-items-center bg-box-container img-file-add-container 
                                                            overflow-hidden h-100">
                                                    <img id="img-addFile-pairing-placeholder" src="<?=base_url('assets/images/icons/image-add-fill.svg')?>" alt="Tambahkan File Pendukung" width="40" height="40">
                                                    <span class="text-white mt-1">Tambahkan gambar pendukung</span>
                                                </div>
                                                <div id="img-pairing-preview" class="d-none position-relative h-100 w-100 img-preview">
                                                    <img src="" class="img-fluid h-100 w-100">
                                                    <div class="preview-overlay bg-dark bg-opacity-25 d-flex position-absolute top-0 left-0 h-100 w-100 justify-content-center align-items-center">
                                                        <div class="d-flex flex-nowrap">
                                                            <button type="button" class="btn btn-active bg-white"><i class="bi bi-trash"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="file" class="d-none" id="pairing-file-add" name="file-add">
                                            </label>
                                        </div>
                                        <div class="col-8">
                                            <div class="d-flex h-100 w-100 border border-white justify-content-center align-items-center bg-box-container rounded">
                                                <input type="text" class="form-control border-none outline-none text-white input-question h-100 w-100" 
                                                       
                                                       placeholder="Ketik pertanyaan atau panduan soal di sini..." name="question-text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3 flex-nowrap">
                                        <div class="col flex-grow-1 d-flex flex-nowrap">
                                            <!-- COLUMN QUESTION -->
                                            <div class="row w-100" id="row-pairing-question">
                                                <div class="col d-flex flex-column col-pairing-question" data-index='0'>
                                                    <!-- BOX QUESTION  -->
                                                    <div class="box-question bg-box-container">
                                                        <div class="box-question-header">
                                                            <button type="button" class="btn btn-sm delete-question pt-1"><i class="bi bi-trash text-white"></i></button>
                                                            <span class="d-inline-block ms-2">
                                                                <input type="checkbox" class="btn-check" id="q-image-0" name="is-image-match[]" />
                                                                <label class="btn btn-sm" for="q-image-0"><image src="<?=base_url('assets/images/icons/image-add-fill.svg')?>" width="16" height="16"></label>
                                                            </span>                                                            
                                                        </div>
                                                        <div class="box-question-body overflow-hidden d-flex flex-wrap justify-content-center align-items-center">

                                                            <label class="d-block d-none border-none position-relative h-100 w-100" for="arahan-image-0">
                                                                <div class="mx-auto mt-3">
                                                                    <h3 class="text-center fs-3">&#128071;</h3>
                                                                    <h6 class="text-center text-white">Seret atau cari gambar di sini ...</h6>
                                                                </div>
                                                                <img id="q-image-preview-0" class="bg-transparent position-absolute top-0 left-0 h-100 w-100"/>
                                                            </label>
                                                            <input type="file" name="q-image[]" class="d-none" id="arahan-image-0"/>
                                                            <input type="text" class="form-control border-none text-white h-100 w-100 input-question" name="input-key[]" placeholder="Ketik Arahan di sini...">
                                                        </div>
                                                    </div>
                                                    <!-- BOX ANSEWR -->
                                                    <div class="box-answer d-flex justify-content-center align-items-center bg-box-container mt-3">

                                                        <input type="text" class="form-control text-white input-answer h-100 w-100" placeholder="Ketik jawaban anda di sini ..." name="input-value[]"/>
                                                    </div>
                                                </div>
                                                <div class="col d-flex flex-column col-pairing-question" data-index="1">
                                                    <!-- BOX QUESTION  -->
                                                    <div class="box-question bg-box-container rounded">
                                                        <div class="box-question-header">
                                                            <button type="button" class="btn btn-sm delete-question pt-1"><i class="bi bi-trash text-white"></i></button>
                                                            <span class="d-inline-block ms-2">
                                                                <input type="checkbox" class="btn-check" id="q-image-1" name="is-image-match[]" autocomplete="off"/>
                                                                <label class="btn btn-sm" for="q-image-1"><image src="<?=base_url('assets/images/icons/image-add-fill.svg')?>" width="16" height="16"></label>
                                                            </span>
                                                        </div>
                                                        <div class="box-question-body overflow-hidden d-flex flex-wrap justify-content-center align-items-center">
                                                            <label class="d-block d-none position-relative h-100 w-100" for="arahan-image-1">
                                                                <div class="mx-auto mt-3">
                                                                    <h3 class="text-center">&#128071;</h3>
                                                                    <h6 class="text-center text-white">Seret atau cari gambar di sini ...</h6>
                                                                </div>
                                                                <img id="q-image-preview-1" class="bg-transparent position-absolute top-0 left-0 h-100 w-100"/>
                                                            </label>
                                                            <input type="file" name="q-image[]" class="d-none" id="arahan-image-1"/>
                                                            <input type="text" class="form-control border-none text-white h-100 w-100 input-question" name="input-key[]" placeholder="Ketik Arahan di sini...">
                                                        </div>
                                                    </div>
                                                    <!-- BOX ANSEWR -->
                                                    <div class="box-answer d-flex justify-content-center align-items-center bg-box-container mt-3">
                                                        <input type="text" class="form-control text-white input-answer h-100 w-100" name="input-value[]" placeholder="Ketik jawaban anda di sini ..."/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1 d-flex flex-nowrap justify-content-center align-items-center">
                                            <button type="button" class="btn btn-lg border border-white text-white py-1" id="add-pairing-questions">
                                                <span class="text-white fs-3">&#43;</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="mdl-pairing-config" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-centered">

        <div class="modal-content">
            <div class="modal-header bg-primary" data-bs-theme="dark">
                <h5 class="modal-title text-white">Pengaturan Jawaban</h5>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal" data-bs-target="#mdl-pairing-config"></button>
            </div>
            <div class="modal-body px-1">
                <div class="d-flex flex-nowrap p-3 w-100 align-items-center overflow-auto">
                    <span class="fs-6 d-inline-block">Nilai Jawaban&nbsp;<i class="bi bi-info-circle-fill"></i></span>
                    
                    <select class="form-select form-select-sm ms-auto w-25 py-2" id="pairing-config-score">
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
                    
            </div>
           

        </div>
    </div>
</div>

