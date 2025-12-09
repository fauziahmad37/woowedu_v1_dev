<!-- Modal add -->
<section class="modal fade" tabindex="-1" id="modal-add">
  <div class="modal-dialog modal-xl">
    <div class="modal-content border-0">
      <div class="modal-header bg-success">
        <h5 class="modal-title text-capitalize text-light text-shadow">Tambah Tugas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form name="form-add" id="form-add" class="d-flex flex-column">
            <div class="row flex-nowrap">
                <div class="col-12 col-lg-4">
                    <h4 class="mb-4 text-underline">KETERANGAN</h4>
                    <div class="d-flex flex-column">
                        <div class="form-group">
                            <label class="m-0">Kode Tugas <span class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control form-control-sm" name="a_tugas_code" required/>
                        </div>
                        
                        <div class="w-100 mt-2">
                            <label class="m-0">Materi <span class="text-danger"><strong>*</strong></span></label>
                            <span class="d-flex flex-nowrap">
                              <select type="text" class="form-control form-control-sm" name="a_tugas_materi" required></select>
                            </span>
                            <input type="hidden" name="a_tugas_materi_text">
                        </div>

                        <div class="w-100 mt-2">
                            <label class="m-0">Kelas <span class="text-danger"><strong>*</strong></span></label>
                            <span class="d-flex flex-nowrap">
                              <select type="text" class="form-control form-control-sm w-100" name="a_tugas_class" required></select>
                            </span>
                            <input type="hidden" name="a_tugas_class_text">
                        </div>

                        <div class="form-group mt-2">
                          <label class="m-0">Periode <span class="text-danger"><strong>*</strong></span></label>
                          <input type="text" class="form-control form-control-sm" name="a_tugas_periode" id="a_tugas_periode">
                          <input type="hidden" name="a_tugas_start">
                          <input type="hidden" name="a_tugas_end">
                        </div>

                        <div class="form-group mt-2">
                          <label class="m-0">File Pendukung</label>
                          <div class="input-group input-group-sm">
                            <input type="file" class="form-control form-control-sm" id="a_tugas_file" name="a_tugas_file" aria-describedby="a_tugas_file">
                          </div>
                        </div>
                    
                    </div>
                    
                </div>
                <div class="col">
                    <h4 class="mb-4 text-underline">TUGAS</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex-column">
                                <label for="">Deskripsi <span class="text-danger"><strong>*</strong></span</label>
                                <textarea name="a_tugas_detail" class="form-control" id="detail-tugas" rows="8" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row d-none" id="mc">
                      <div class="col-12">
                       
                      </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="a_id" />
            <input type="hidden" name="a_tugas_guru" />
            <input type="hidden" name="xsrf" />
        </form>
        <span class="w-100 d-flex flex-nogrow">
            <!-- PRogress bar-->
          <div class="progress mt-2 w-100" id="import-progress-1">
            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <!-- end PRogress bar-->
        </span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-tugas">Simpan</button>
      </div>
    </div>
  </div>
</section>
<!-- end modal add-->

<!-- Modal View -->
<div class="modal fade" tabindex="-1" id="mdl-view-tugas">

    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background-color: var(--bs-purple); color: var(--bs-white)">
                <h5 class="modal-title text-capitalize text-light text-shadow">Tugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 id="title" class="mb-4"></h4>
                <span id="note" class="mb-5 text-justify"></span>
                <div class="d-flex flex-nowrap justify-content-end pt-3">
                    <a id="task_file" href="" class="btn btn-primary text-white" download>
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End Modal View -->