<?php
    //$lang['woow_license_max_door'] = 'Maximum Doors';
    //$lang['woow_license_id'] = 'PC ID';
    //$lang['woow_license_key'] = 'Activation Key';
    //$lang['woow_license_max_trans'] = 'Maximum Transactions';
    
?>

<div class="row">
    <div class="col-12">

        <div class="card" style="width: 26rem">
            <div class="card-header"></div>
            <div class="card-body">
                <form name="frm-license" method="post" action="api/lliv/post">
                    <div class="form-group">
                        <label class="mb-0"><?=$this->lang->line('woow_license_max_door')?></label>
                        <input type="number" name="max-door" class="form-control form-control-sm" disabled/>
                    </div>
                    <div class="form-group">
                        <label class="mb-0"><?=$this->lang->line('woow_license_id')?></label>
                        <input type="text" name="pc-id" class="form-control form-control-sm" readonly/>
                    </div>
                    <div class="form-group">
                        <label class="mb-0"><?=$this->lang->line('woow_license_key')?></label>
                        <input type="text" name="activation-key" class="form-control form-control-sm" />
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-end mt-4">
                        <input type="submit" class="btn btn-sm btn-success" Value="Simpan"/>
                    </div>
                    
                </form>
            </div>
        </div>

    </div>
</div>