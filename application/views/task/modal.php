<!-- Modal add -->
<section class="modal fade" tabindex="-1" id="modal-add">
  <div class="modal-dialog modal-xl">
    <div class="modal-content border-0">
      <div class="modal-header bg-success">
        <h5 class="modal-title text-capitalize text-light text-shadow">Tambah Tugas</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="false">&times;</span>
        </button>
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
                        
                        <div class="form-group">
                            <label class="m-0">Materi <span class="text-danger"><strong>*</strong></span></label>
                            <span class="d-flex flex-nowrap">
                              <select type="text" class="form-control form-control-sm col" name="a_tugas_materi" data-live-search="true" required></select>
                              <button type="button" id="reset-materi" class="btn btn-sm btn-primary"><i class="fas fa-undo"></i></button>
                            </span>
                            <input type="hidden" name="a_tugas_materi_text">
                        </div>
                        <div class="form-group">
                            <label class="m-0">Guru <span class="text-danger"><strong>*</strong></span></label>
                            <span class="d-flex flex-nowrap">
                              <select type="text" class="form-control form-control-sm col" name="a_tugas_guru" data-live-search="true" required></select>
                              <button type="button" id="reset-guru" class="btn btn-sm btn-primary"><i class="fa fa-undo"></i></button>
                            </span>
                            <input type="hidden" name="a_tugas_guru_text">
                        </div>

                        <div class="form-group">
                            <label class="m-0">Kelas <span class="text-danger"><strong>*</strong></span></label>
                            <span class="d-flex flex-nowrap">
                              <select type="text" class="form-control form-control-sm col" name="a_tugas_class" data-live-search="true" required></select>
                              <button type="button" id="reset-class" class="btn btn-sm btn-primary"><i class="fa fa-undo"></i></button>
                            </span>
                            <input type="hidden" name="a_tugas_class_text">
                        </div>

                        <div class="form-group">
                          <label class="m-0">Periode <span class="text-danger"><strong>*</strong></span></label>
                          <input type="text" class="form-control form-control-sm" name="a_tugas_periode" id="a_tugas_periode">
                          <input type="hidden" name="a_tugas_start">
                          <input type="hidden" name="a_tugas_end">
                        </div>

                        <div class="form-group">
                          <label class="m-0">File Pendukung</label>
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="a_tugas_file" name="a_tugas_file" aria-describedby="a_tugas_file">
                            <label class="custom-file-label overflow-hidden" for="a_tugas_file" data-browse="Pilih File"></label>
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