<div class="row">
    <div class="col-12">

        <!-- START CARD -->
        <div class="card">
            <div class="card-body">
                 <!-- SEARCH ROW -->
                <form id="form-search">
                    <div class="row">
                        <div class="col-12 col-md-2">
                            <div class="form-group">
                                <label for="username" class="mb-1">Level Name</label>
                                <input type="search" class="form-control form-control-sm" id="s_level_name" name="s_level_name" placeholder="Level Name"/>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 align-self-center">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-primary" id="btn-search"><i class="fa fa-search"></i></button>
                                <button type="button" class="btn btn-danger" id="reset-search"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END SEARCH ROW-->
                <!-- TOOLBAR ROW -->
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning waves-light waves-effect" id="btn-refresh"><i class="fas fa-sync"></i> Refresh</button>
                            <button type="button" class="btn btn-success waves-light waves-effect" id="btn-new" data-toggle="modal" data-target="#modal-add">
                                <i class="fas fa-plus-circle"></i> New
                            </button>
                            <button type="button" class="btn bg-pink text-white waves-light waves-effect" id="btn-deleteAll">
                                <i class="fas fa-trash"></i> Delete All
                            </button>
                        </div>
                    </div>
                </div>
                <!-- END TOOLBAR ROW-->
                <!-- TABLE ROW -->
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="tbl-menuLevel" class="table table-sm table-striped w-100">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th width="2%" class="theader text-center">
                                            <input type="checkbox" id="select_all">
                                        </th>
                                        <th scope="col">ID</th>
                                        <th scope="col">Level Name</th>
                                        <th scope="col">Menu</th>
                                        <th scope="col">Act.</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END TABLE ROW-->
            </div>
        </div>
        <!-- END CARD -->
    </div>
</div>

<!-- MODAL ADD -->
<div class="modal fade" id="modal-add" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- modal header-->
            <div class="modal-header bg-orange">
                <h5 class="modal-title text-capitalize text-white text-shadow">Add Level</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="false" class="text-white">&times;</span>
                </button>
            </div>
            <!-- end modal header-->
            <!-- modal body-->
            <div class="modal-body">
                <form name="form-add">
                    <!-- input level name -->
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="level-name">Level Name <span class="text-danger"><strong>*</strong></span></label>
                                <input type="text" class="form-control form-control-sm" name="level-name" id="level-name">
                            </div>
                        </div>
                    </div>
                    <!-- end input level name -->
                    <!-- menu tree -->
                    <div class="row">
                        <div class="col-12">
                            <label>Menu <span class="text-danger"><strong>*</strong></span></label>
                            <div class="card mb-1">
                                <div class="card-body" style="border: 1px solid silver; border-radius: 5px; overflow: auto; height: 200px">
                                    <div id="menu-tree"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end menu tree -->
                    <input type="hidden" name="uid" id="id" />
                </form>
               
            </div>
            <!-- end modal body-->
            <!-- footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save-level">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL ADD -->