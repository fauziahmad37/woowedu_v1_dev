<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">
                <!-- SEARCH -->
                <form name="form-search" class="row">
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="s_username">Username</label>
                            <input type="search" class="form-control form-control-sm" id="s_username" name="s_username" placeholder="Username">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="s_userlevel">User Level</label>
                            <input type="search" class="form-control form-control-sm" id="s_userlevel" name="s_userlevel" placeholder="User Level">
                        </div>
                    </div>
                    <div class="col-12 col-md-2 align-self-center">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-primary" id="btn-search"><i class="fa fa-search"></i></button>
                            <button type="button" class="btn btn-danger" id="reset-search"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                </form>
                <!-- TOOLBAR -->
                <hr />
                <div class="row">
                    <div class="col-12">
                        <div class="btn-group btn-group-sm">
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
                <!-- DATATABLE -->
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="tbl-users" class="table table-sm table-striped w-100">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th scope="col">
                                            <input type="checkbox" id="select_all">
                                        </th>
                                        <th scope="col">ID</th>
                                        <th scope="col">SEKOLAH ID</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Nama Sekolah</th>
                                        <th scope="col">Password</th>
                                        <th scope="col">User Level ID</th>
                                        <th scope="col">User Level</th>
                                        <th scope="col">Expired</th>
                                        <th scope="col">Active</th>
                                        <th scope="col">Act.</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- MODAL ADD -->
<div id="modal-add" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-info">
                <h5 class="modal-title text-capitalize text-white text-shadow">Add Users</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="false" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="form-add">
                    <div class="row">
										
                        <div class="col-12">
                            <div class="form-group row">
                                <label class="col-md-3 col-12 col-form-label">Nama Sekolah <span class="text-danger"><strong>*</strong></span></label>
                                <div class="col-md-9">
									<select type="text" class="form-control form-control-sm col-10" name="slc-sekolah" data-live-search="true" required></select>
									<button type="button" id="reset-sekolah" class="btn btn-sm btn-primary"><i class="fas fa-undo"></i></button>
									<input type="hidden" name="txt-sekolah">
                                </div>
                            </div>
                        </div>
												
												
                        <div class="col-12">
                            <div class="form-group row">
                                <label class="col-md-3 col-12 col-form-label">Username <span class="text-danger"><strong>*</strong></span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="txt-username" name="txt-username">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group row">
                                <label class="col-md-3 col-12 col-form-label">Password <span class="text-danger"><strong>*</strong></span></label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" id="txt-password" name="txt-password" value="***">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group row">
                                <label class="col-md-3 col-12 col-form-label">User Level <span class="text-danger"><strong>*</strong></span></label>
                                <div class="col-md-9">
                                    <select class="form-control" id="slc-userlevel" name="slc-userlevel"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group row align-item-baseline">
                                <label class="col-md-3 col-12 col-form-label">Active <span class="text-danger"><strong>*</strong></span></label>
                                <div class="col-md-9 align-self-center">
                                    <div class="custom-control custom-switch">
                                      <input type="checkbox" checked class="custom-control-input" id="chk-active" name="chk-active">
                                      <label class="custom-control-label" for="chk-active">&nbsp;</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="userid" />
                        <div class="col-12 d-flex flex-wrap">
                            <div class="ml-auto">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- MODAL ACTIVATE -->
<div id="modal-activation" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header bg-info">
				<h5 class="modal-title text-capitalize text-white text-shadow">Activate Users</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="false" class="text-white">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<form name="form-activation">
					<div class="row">
						<input type="hidden" name="userid" />

						<!-- input end date activation -->
						<div class="col-12">
							<div class="form-group row">
								<label class="col-md-3 col-12 col-form-label">Expired <span class="text-danger"><strong>*</strong></span></label>
								<div class="col-md-9">
									<input type="date" class="form-control" id="expired_date" name="expired_date" required>
								</div>
							</div>
						</div>
						
						<div class="col-12 d-flex flex-wrap">
							<div class="ml-auto">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</div>
				</form>
			</div>

		</div>
	</div>

</div>
