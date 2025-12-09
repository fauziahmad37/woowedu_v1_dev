<div id="modal_stock" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
            <div class="modal-header bg-orange">
                <h5 class="modal-title text-white mb-0">DETAIL BUKU</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="save-stock" id="save-stock" class="modal-body" action="<?=$this->e(base_url('stock/store'))?>" method="POST">
                <div class="form-group">
                    <label class="form-label mb-0">Buku <span class="text-danger">*</span></label>
                    <select class="form-control <?php if(!empty($_SESSION['error']['errors']['book'])): ?> is-invalid <?php endif ?>" name="book" value="<?=$this->e($book_id)?>" <?php if($is_readonly):?> readonly <?php endif ?>></select>
                    <?php if(!empty($_SESSION['error']['errors']['book'])): ?>
                        <small class="text-danger"><?=$_SESSION['error']['errors']['book']?></small>
                    <?php endif ?>
                </div>
                <div class="row m-0 p-0 mt-3 mb-1 justify-content-end w-100">
                    <button type="button" id="btn-add-stock" class="btn btn-sm bg-orange text-white-40"><i class="fas fa-plus"></i> Tambah Stock</button>
                </div>
                <div class="table-reponsive">
                    <table id="table-add-stock" class="table table-sm">
                        <thead class="bg-orange text-white">
                            <tr>
                                <th>No</th>
                                <th>Kode Stok</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            
                                if(isset($_SESSION['error']['old']['stock_codes']) && count($_SESSION['error']['old']['stock_codes']) > 0):
                                    $i=0;
                                    foreach($_SESSION['error']['old']['stock_codes'] as $stock): 
                            ?>
                                <tr>
                                    <td><?=$this->e($i + 1)?></td>
                                    <td>
                                        <input type="text" class="form-control <?php if(!empty($_SESSION['error']['errors']['stock_codes['.$i.']'])): ?> is-invalid <?php endif ?>" name="stock_codes[<?=$i?>]" value="<?=$stock ?? NULL ?>">
                                        <?php if(!empty($_SESSION['error']['errors']['stock_codes['.$i.']'])): ?>
                                        <small class="text-danger"><?=$_SESSION['error']['errors']['stock_codes['.$i.']']?></small>
                                        <?php endif ?>
                                    </td>
                                    <td><button type="button" class="btn btn-circle btn-danger" onclick="deleteStockField(event)"><i class="fas fa-trash"></i></button></td>
                                </tr>
                            <?php 
                                    $i++;
                                    endforeach; 
                                else:
                            ?>
                                <?php if(!empty($_SESSION['error']['errors']['stock_codes'])): ?>
                                        <small class="text-danger"><?=$_SESSION['error']['errors']['stock_codes']?></small>
                                <?php endif ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-end mt-4 border-top pt-3 px-2">
                    <button type="reset" class="btn btn-secondary"><i class="fas fa-sync"></i> Ulangi</button>
                    <button type="submit" class="btn btn-primary ml-2"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
       </div>
    </div>
</div>