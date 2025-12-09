<div class="container-fluid">

    <form name="frm-book" class="row" novalidate>
        <fieldset class="col-12">
            <div class="card">
                <div class="card-body">
                    <legend class="text-capitalize">detail ebook</legend>
                    <!-- upload product -->
                    <div class="form-group">
                        <label>Upload Ebook <span class="text-danger">*</span></label>
                        <div class="mb-1">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="custom-control-input" type="radio" name="rd-upload-type" id="rd-file" value="file" checked>
                                <label class="custom-control-label" for="rd-file">File</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="custom-control-input" type="radio" name="rd-upload-type" id="rd-link" value="link">
                                <label class="custom-control-label" for="rd-link">Link</label>
                            </div>
                        </div>
                        <div class="custom-file has-validation" id="ebook-file">
                            <input type="file" class="custom-file-input" name="ebook-file" id="upload-file" required>
                            <label class="custom-file-label" for="upload-file"><i>Tidak ada file yang dipilih</i></label>
                        </div>
                        <div class="custom-file d-none" id="ebook-link">
                            <input type="url" class="form-control" id="upload-link" name="ebook-link" placeholder="Masukan tautan dari ebook yang akan anda unggah" required disabled>
                        </div>
                        
                    </div>
                    <!-- Judul -->
                     <div class="form-group">
                        <label for="book-title">Nama/Judul Ebook <span class="text-danger">*</span></label>
                        <input type="text" name="book-title" id="book-title" class="form-control" required>
                        <small></small>
                     </div>
                    <!-- Kode Buku -->
                     <div class="form-group">
                        <label for="book-code">Kode Ebook <span class="text-danger">*</span></label>
                        <input type="text" name="book-code" id="book-code" class="form-control" required>
                        <small></small>
                     </div>
                    <!-- Publisher -->
                     <div class="form-group">
                        <label for="book-publisher">Penerbit <span class="text-danger">*</span></label>
                        <select name="book-publisher" id="book-publisher" class="form-control" required>
                            <option value="">-- Pilih Penerbit --</option>
                            <?php foreach($publishers as $pub): ?>
                            <option value="<?=html_escape($pub["id"])?>"><?=html_escape($pub["publisher_name"])?></option>
                            <?php endforeach; ?>
                        </select>
                     </div>
                      <!-- ISBN -->
                      <div class="form-group">
                        <label for="book-isbn">Nomor ISBN <span class="text-danger">*</span></label>
                        <input type="text" name="book-isbn" id="book-isbn" class="form-control" required>
                     </div>
                      <!-- Halaman -->
                      <div class="form-group">
                        <label for="book-pages">Jumlah Halaman <span class="text-danger">*</span></label>
                        <input type="number" name="book-pages" id="book-pages" class="form-control" min="1" value="1" required>
                     </div>
                      <!-- Pengarang -->
                      <div class="form-group">
                        <label for="book-author">Pengarang <span class="text-danger">*</span></label>
                        <input type="text" name="book-author" id="book-author" class="form-control" required>
                     </div>
                      <!-- Pengarang -->
                      <div class="form-group">
                        <label for="book-year">Tahun Terbit <span class="text-danger">*</span></label>
                        <input type="number" name="book-year" id="book-year" class="form-control" min="1900" max="9999" value="<?=html_escape(date('Y'))?>" required>
                     </div>
                      <!-- deskripsi  -->
                      <div class="form-group">
                        <label for="book-description">Deskripsi <span class="text-danger">*</span></label>
                        <textarea rows="4" name="book-description" id="book-description" class="form-control" placeholder="Ketik deskripsi informasi disini. . ." required></textarea>
                     </div>

                     <div class="form-group">
                        <label>Foto Ebook <i>(Max. 2MB)</i> <span class="text-danger">*</span></label>

                        <div class="d-flex justify-content-start">
                            <div class="mr-3">
                                <label for="file_sampul" class="d-flex flex-column rounded shadow justify-content-center align-items-center lbl-img" style="height: 160px; width: 125px">
                                        <img class="h-100 w-100 d-none">
                                        <i class="mdi mdi-image-area"></i>
                                        <small>Foto Sampul</small>
                                        <a role="button" class="p-0 rounded-circle btn-remove-img d-none">&times;</a>
                                </label>
                                <input type="file" name="book-image" class="ebook-cover d-none" id="file_sampul" required>
                            </div>
                            <div class="mr-3">
                                <label for="file_1" class="d-flex flex-column rounded shadow justify-content-center align-items-center lbl-img" style="height: 160px; width: 125px">
                                        <img class="h-100 w-100 d-none">
                                        <i class="mdi mdi-image-area"></i>
                                        <small>Foto Lainnya</small>
                                        <a role="button" class="p-0 rounded-circle btn-remove-img d-none">&times;</a>

                                </label>
                                <input type="file" name="cover[]" class="ebook-cover d-none" id="file_1">
                            </div>
                            <div class="mr-3">
                                <label for="file_2" class="d-flex flex-column rounded shadow justify-content-center align-items-center lbl-img" style="height: 160px; width: 125px">
                                        <img class="h-100 w-100 d-none">
                                        <i class="mdi mdi-image-area"></i>
                                        <small>Foto Lainnya</small>
                                        <a role="button" class="p-0 rounded-circle btn-remove-img d-none">&times;</a>

                                </label>
                                <input type="file" name="cover[]" class="ebook-cover d-none" id="file_2">
                            </div>
                            <div class="mr-3">
                                <label for="file_3" class="d-flex flex-column rounded shadow justify-content-center align-items-center lbl-img" style="height: 160px; width: 125px">
                                        <img class="h-100 w-100 d-none">
                                        <i class="mdi mdi-image-area"></i>
                                        <small>Foto Lainnya</small>
                                        <a role="button" class="p-0 rounded-circle btn-remove-img d-none">&times;</a>

                                </label>
                                <input type="file" name="cover[]" class="ebook-cover d-none" id="file_3">
                            </div>
                            <div class="mr-3">
                                <label for="file_4" class="d-flex flex-column rounded shadow justify-content-center align-items-center lbl-img" style="height: 160px; width: 125px">
                                        <img class="h-100 w-100 d-none">
                                        <i class="mdi mdi-image-area"></i>
                                        <small>Foto Lainnya</small>
                                        <a role="button" class="p-0 rounded-circle btn-remove-img d-none">&times;</a>

                                </label>
                                <input type="file" name="cover[]" class="ebook-cover d-none" id="file_4">
                            </div>
                            
                        </div>
                        <small class="text-danger d-none"></small>
                     </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="col-12">
            <div class="card">
                <div class="card-body" id="subscription-section">
                    <legend>Pengelola Langganan</legend>
                    <div class="subscription-container border-bottom mb-3">
                        <!-- Subscribe Type  -->
                        <div class="form-group">
                            <label for="subscription-0-type">Jenis Langganan <span class="text-danger">*</span></label>
                            <select name="subscription[0][type]" id="subscription-0-type" class="form-control" required>
                                <option value="">-- Pilih Jenis Langganan --</option>
                                <option value="1_month">1 Bulan</option>
                                <option value="3_month">3 Bulan</option>
                                <option value="6_month">6 Bulan</option>
                                <option value="12_month">12 Bulan</option>
                                <option value="full_access">Selamanya</option>
                            </select>
                        </div>
                        <!-- Subcribe Name  -->
                        <div class="form-group">
                            <label for="subscription-0-name">Nama Langganan <span class="text-danger">*</span></label>
                            <input type="text" name="subscription[0][name]" id="subscription-0-name" class="form-control" required>
                        </div>
                        <!-- Subscribe Benefit  -->
                        <div class="form-group">
                            <label for="subscription-0-benefit">Benefit Aktif <span class="text-danger">*</span></label>
                            <select name="subscription[0][benefit]" id="subscription-0-benefit" class="form-control" placeholder="-- Pilih Benefit Aktif --" multiple required>
                                <option>Akses Tanpa Batas</option>
                                <option disabled>Reserved</option>
                                <option disabled>Reserved</option>
                                <option disabled>Reserved</option>
                                <option>Lainnya</option>
                            </select>
                        </div>
                      
                        <!-- Subscribe Price -->
                        <div class="form-group">
                            <label for="subscription-0-price">Harga <span class="text-danger">*</span></label>
                            <span class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="currency-text-prepend">Rp.</span>
                                </div>
                                <input type="number" name="subscription[0][price]" id="subscription-0-price" class="form-control" min="0" step=".01" placeholder="0.00" required>
                            </span> 
                           
                        </div>
                    </div>
                    <div class="pt-3" id="new-subscribe">
                        <button type="button" class="btn btn-sm btn-primary"><span class="mdi mdi-plus"></span> Langganan Lainnya</button>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="col-12">
            <div class="card">
                <div class="card-body">

                    <legend>Informasi Produk</legend>
                    <div class="form-group">
                        <label for="start-time">Waktu Mulai <var class="text-danger">*</var></label>
                        <input type="date" name="start-time" id="start-time" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="book-category">Kategori <var class="text-danger">*</var></label>
                        <input type="hidden" class="form-control" id="book-category-id" name="book-category-id"/>
                        <input type="text" class="form-control" id="book-category" name="book-category" placeholder="-- Pilih Kategori --"/>
                    </div>
                   
                    <div class="form-group">
                        <label for="product-status">Status Produk <var class="text-danger">*</var></label>
                        <select name="product-status" id="product-status" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="1">Aktif</option>
                            <option value="2">Pending</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="book-qty">Jumlah Stok <var class="text-danger">*</var></label>
                        <input type="number" name="book-qty" id="book-qty" class="form-control" required min="0">
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="col-12 d-flex justify-content-end">
            <button type="reset" class="btn btn-secondary mr-3">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </fieldset>
    </form>
</div>