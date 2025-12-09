<style>
	.border-custom {
		border: solid 1px whitesmoke;
	}
	.shadow-custom {
		box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
	}
</style>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">

						<div class="bg-soft-primary">
							<div class="row">
								<div class="col-12">
									<div class="text-primary p-3">
										<h4 class="text-primary text-right"><strong>User Profile</strong></h4>
										<br>
									</div>
								</div>
								<div class="col-5 align-self-end">
									<img src="<?= base_url('assets/images/profile-img.png') ?>" alt="" class="img-fluid">
								</div>
							</div>
						</div>
						<div class="card-body pt-0">
							<div class="row">
								<div class="col-sm-8">
									<div class="avatar-md profile-user-wid mb-4">
										<?php if($dt->foto_user != ''){ ?>
										<img src="<?= base_url('assets/images/users/'.$dt->ava.'') ?>" alt="" class="img-thumbnail rounded-circle">
										<?php }else{ ?>
										<div class="avatar-md">
											<span class="avatar-title font-size-24 rounded-circle ml-2">
												<?= substr($dt->username,0,1) ?>
											</span>
										</div>
										<?php } ?>
									</div>
									<h5 class="font-size-15 text-truncate"><?= $dt->user_level_name ?></h5>
									<p class="text-muted mb-0 text-truncate"><?= $dt->username ?></p>
								</div>

								<div class="col-sm-4">
									<div class="pt-4">
									   
										<div class="row">
											<div class="col-12">
												<span class="font-size-15 badge badge-pill badge-success float-right"><?= $dt->role ?></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- end card -->
						<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
							<a class="nav-link mb-2 active" id="detail-profile-tab" data-toggle="pill" href="#detail-profile" role="tab" aria-controls="detail-profile" aria-selected="true"><i class="bx bx-detail font-size-16 mr-3"></i>Detail Users</a>
							<a class="nav-link mb-2" id="edit-profile-tab" data-toggle="pill" href="#edit-profile" role="tab" aria-controls="edit-profile" aria-selected="false"><i class="bx bx-user-check font-size-16 mr-3"></i>Edit User</a>
							<a class="nav-link mb-2" id="edit-password-tab" data-toggle="pill" href="#edit-password" role="tab" aria-controls="edit-password" aria-selected="false"><i class="bx bx-lock-open-alt font-size-16 mr-3"></i>Ubah Password</a>
							<a class="nav-link" id="edit-foto-tab" data-toggle="pill" href="#edit-foto" role="tab" aria-controls="edit-foto" aria-selected="false"><i class="bx bx-image-alt font-size-16 mr-3"></i>Ubah Foto</a>
						</div>
					</div>
					<div class="col-md-8">
						<div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
							<div class="tab-pane fade show active" id="detail-profile" role="tabpanel" aria-labelledby="detail-profile-tab">
								<div class="table-responsive">
									<table class="table table-nowrap table-hover mb-0">
										<thead>
											<tr>
												<th class="bg-primary text-white" colspan="3">Biodata Users</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th width="20%">Username</th>
												<th width="2%"> : </th>
												<td class="text-primary"><?= $dt->username ?></td>
											</tr>
											<tr>
												<th>Roles</th>
												<th width="2%"> : </th>
												<td><?= $dt->user_level_name ?></td>
											</tr>
											<tr>
												<th>Last Login</th>
												<th width="2%"> : </th>
												<td><?= date('d F Y, H:i', strtotime($dt->last_login)) ?></td>
											</tr>
											<tr>
												<th width="20%">Status</th>
												<th width="2%"> : </th>
												<td class="text-primary">
                                                    <?php
                                                        if($dt->active == 1){
                                                            echo '<span class="badge badge-pill badge-success">Aktif</span>';
                                                        }else{
                                                            echo '<span class="badge badge-pill badge-danger">Tidak Aktif</span>';
                                                        }
                                                    ?>
                                                </td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="tab-pane fade" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
								<form class="form-horizontal editData" action="#" method="POST" id="form_user" enctype='multipart/form-data'>
									<div class="row">
										<div class="col-md-12">
											<h4 class="text-secondary mt-3">Biodata Users</h4>
											<hr>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="nama_user"><?=$this->lang->line('woow_username')?><span class="text-danger"><strong>*</strong></span></label>
												<input type="text" class="form-control" placeholder="<?=$this->lang->line('woow_username')?>" id="nama_user" name="nama_user" value="<?= $dt->nama_user ?>" required>
											</div>
										</div>
									</div>

									<input type="hidden" value="<?= $dt->userid ?>" name="id_user" id="id_user">

									<div class="mt-4">
										<input type="submit" class="btn btn-primary float-right waves-effect waves-light" value="Simpan Users">
									</div>
								</form>
							</div>
							<div class="tab-pane fade" id="edit-password" role="tabpanel" aria-labelledby="edit-password-tab">
								<form class="changePassword" action="#" method="post" id="form_password">
									<div class="form-group row mb-4">
										<label for="password_lama" class="col-md-3 col-form-label"><?=$this->lang->line('woow_old_pass')?><span class="text-danger text-bold">*</span></label>
										<div class="col-md-9">
											<input type="password" class="form-control" id="password_lama" name="password_lama" placeholder="<?=$this->lang->line('woow_old_pass')?>">
										</div>
									</div>
									<div class="form-group row mb-4">
										<label for="password_baru" class="col-md-3 col-form-label"><?=$this->lang->line('woow_new_pass')?><span class="text-danger text-bold">*</span></label>
										<div class="col-md-9">
											<input type="password" class="form-control" id="password_baru" name="password_baru" placeholder="<?=$this->lang->line('woow_new_pass')?>">
										</div>
									</div>
									<div class="form-group row mb-4">
										<label for="repassword" class="col-md-3 col-form-label"><?=$this->lang->line('woow_re_pass')?><span class="text-danger text-bold">*</span></label>
										<div class="col-md-9">
											<input type="password" class="form-control" id="repassword" name="repassword" placeholder="<?=$this->lang->line('woow_re_pass')?>">
										</div>
									</div>

									<input type="hidden" value="<?= $dt->userid ?>" name="id_user">

									<div class="mt-4">
										<input type="submit" class="btn btn-primary float-right waves-effect waves-light btn-change" value="<?=$this->lang->line('woow_modify')?> Password">
									</div>
									
								</form>
							</div>
							<div class="tab-pane fade" id="edit-foto" role="tabpanel" aria-labelledby="edit-foto-tab">
								<span for="foto" class="h5">Foto Users</span>
								<!-- PREVIEW PICTURE -->
								<div class="row mt-2">
									<div class="col-md-4">
										<figure class="figure border-custom rounded">
											<?php 
												//$img = $this->db->get_where('users', ['userid' => $_SESSION['userid']])->row_array();
												$imgUrl = (!empty($dt->photo)) ? base_url(html_escape('assets/files/users/'.$dt->photo)) : base_url(html_escape('assets/images/user-preview.png'));
											?>
											<img class="figure-img img-fluid" id="preview" src="<?=$imgUrl?>" />
										</figure>
									</div>
								</div>
								<div class="row mt-2">
									<div class="col-md-4">
										<button type="button" id="change-pic" class="btn btn-primary waves-effect waves-light w-100">Ubah Foto</button>
									</div>
								</div>
								<!-- PREVIEW PICTURE -->
								<form class="uploadData" id="form_foto" enctype='multipart/form-data'>

									<div class="row">
										<div class="col-12" style="display: none">
											<div class="form-group">
												<input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
											</div>
										</div>
										<div class="col-md-12 mt-8">
											<div class="mt-8 d-flex flex-wrap">
												<input type="hidden" value="<?= $dt->userid ?>" name="id_user">
												<input type="submit" class="btn btn-primary waves-effect waves-light ml-auto" value="Simpan">
											</div>
										</div>
									</div>
								</form>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end card -->
	</div>
</div>

<script>
var base_url = document.querySelector('base').href;
$(document).ready(function() {

	$('#form_user').on('submit',function(e) {
		e.preventDefault();
		var target = e.target;
		$.ajax({
		  url: base_url + 'users/updateUsername', //nama action script php
		  data: $(e.target).serialize(),
		  type:'POST',
		  beforeSend: (xhr) => {
                Swal.fire({
                    html: 	'<div class="d-flex flex-column align-items-center">'
                    + '<span class="spinner-border text-primary"></span>'
                    + '<h3 class="mt-2">Loading...</h3>'
                    + '<div>',
                    showConfirmButton: false,
                    width: '10rem'
                });
            },
            success: (resp) => {
                //var res = JSON.parse(resp);
                var res = resp;
                Swal.fire({
                    type: res.err_status,
                    title:'<h5 class="text-success text-uppercase">'+res.err_status+'</h5>',
                    html: res.message,
					timer: 2000
                })
				.then((b) => {
					location.reload();
				});
                //csrfToken.setAttribute('content', res.token);
            },
            error: (err) => {
                var response = JSON.parse(err.responseText);
                Swal.fire({
                    type: response.err_status,
                    title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
                    html: response.message
                });
                //csrfToken.setAttribute('content', response.token);
            },
			complete: function() {
				target.reset();
				
			}
		});
	});

	$('#form_password').on('submit', e => {
		e.preventDefault();

		$.ajax({
			url: base_url + 'users/changePassword',
			type: 'POST',
			data: $(e.target).serialize(),
			beforeSend: (xhr) => {
                Swal.fire({
                    html: 	'<div class="d-flex flex-column align-items-center">'
                    + '<span class="spinner-border text-primary"></span>'
                    + '<h3 class="mt-2">Loading...</h3>'
                    + '<div>',
                    showConfirmButton: false,
                    width: '10rem'
                });
            },
            success: (resp) => {
                //var res = JSON.parse(resp);
                var res = resp;
                Swal.fire({
                    type: res.err_status,
                    title:'<h5 class="text-success text-uppercase">'+res.err_status+'</h5>',
                    html: res.message,
					timer: 2000
                })
				.then((b) => {
					location.reload();
				});
                //csrfToken.setAttribute('content', res.token);
            },
            error: (err) => {
                var response = JSON.parse(err.responseText);
                Swal.fire({
                    type: response.err_status,
                    title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
                    html: response.message
                });
                //csrfToken.setAttribute('content', response.token);
            }
		});
	});

	//change-pic
	$('#change-pic').on("click", function() {
		var file = $('#foto');
		file.trigger("click");
		//
	});

	document.getElementById('foto').addEventListener('change', function(e) {
		var fileName = e.target.files[0].name;
		var reader = new FileReader();
		reader.onload = function(e) {
			// get loaded data and render thumbnail.
			document.getElementById("preview").src = e.target.result;
		};
		// read the image file as a data URL.
		reader.readAsDataURL(this.files[0]);
	});

	$('#form_foto').on('submit', e => {
		e.preventDefault();

		$.ajax({
			url: base_url + 'users/uploadPhoto',
			type: 'POST',
			data: new FormData(e.target),
	  		contentType: false, 
	  		cache: false,
	  		processData: false,
			beforeSend: (xhr) => {
                Swal.fire({
                    html: 	'<div class="d-flex flex-column align-items-center">'
                    + '<span class="spinner-border text-primary"></span>'
                    + '<h3 class="mt-2">Loading...</h3>'
                    + '<div>',
                    showConfirmButton: false,
                    width: '10rem'
                });
            },
            success: (resp) => {
                //var res = JSON.parse(resp);
                var res = resp;
                Swal.fire({
                    type: res.err_status,
                    title:'<h5 class="text-success text-uppercase">'+res.err_status+'</h5>',
                    html: res.message,
					timer: 2000
                })
				//.then((b) => {
				//	location.reload();
				//});
                //csrfToken.setAttribute('content', res.token);
            },
            error: (err) => {
                var response = JSON.parse(err.responseText);
                Swal.fire({
                    type: response.err_status,
                    title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
                    html: response.message
                });
                //csrfToken.setAttribute('content', response.token);
            }
		});
	});

	/*$('.changePassword').on('submit',function(e) {

		var pw_lama = $('#password_lama').val();
		var pw_baru = $('#password_baru').val();
		var re_pw   = $('#repassword').val();

		if(pw_lama.length == ''){
			Swal.fire({
				title: "Warning",
				text: "Password Lama Tidak Boleh Kosong",
				confirmButtonColor: "#EF5350",
				type: "warning",
				closeOnConfirm: false,
				closeOnClickOutside: false,
				closeOnCancel: false
			});
		}else if(pw_baru.length == ''){
			Swal.fire({
				title: "Warning",
				text: "Password Baru Tidak Boleh Kosong",
				confirmButtonColor: "#EF5350",
				type: "warning",
				closeOnConfirm: false,
				closeOnClickOutside: false,
				closeOnCancel: false
			});
		}else if(re_pw.length == ''){
			Swal.fire({
				title: "Warning",
				text: "Re-Password Tidak Boleh Kosong",
				confirmButtonColor: "#EF5350",
				type: "warning",
				closeOnConfirm: false,
				closeOnClickOutside: false,
				closeOnCancel: false
			});
		}else if(re_pw != pw_baru){
			Swal.fire({
				title: "Password Tidak Sama",
				text: "Cek kembali password baru Anda!",
				confirmButtonColor: "#EF5350",
				type: "danger",
				closeOnConfirm: false,
				closeOnClickOutside: false,
				closeOnCancel: false
			});
		}else{
			$.ajax({
				url: '<?= base_url() ?>auth/changePassword', //nama action script php
				data: new FormData(this),
				type:'POST',
				contentType: false, 
				cache: false,
				processData: false,
				success:function(data){
					if (data == 'success') {
						Swal.fire({
							title: "Berhasil !",
							text: "Data Berhasil Disimpan!",
							confirmButtonColor: "#66BB6A",
							type: "success"
						}).then(function(t) {
							 window.location.reload()
						});
					}else if (data == 'pw_salah') {
						Swal.fire({
							title: "Gagal !",
							text: "Password Lama Salah!",
							confirmButtonColor: "#EF5350",
							type: "warning"
						});
					}else {
						Swal.fire({
							title: "Gagal !",
							text: "Gagal Ubah Password!",
							confirmButtonColor: "#EF5350",
							type: "error"
						}).then(function(t) {
							 window.location.reload()
						})
					}
				},
				error:function(data){
					Swal.fire({
						title: "Gagal !",
						text: "Gagal Ubah Password!",
						confirmButtonColor: "#EF5350",
						type: "error"
					});
				}
			});
		}
		e.preventDefault(); 
	});*/

	/*$('.uploadData').on('submit',function(e) {
	$.ajax({
	  url: '<?= $uploadData; ?>', //nama action script php
	  data: new FormData(this),
	  type:'POST',
	  contentType: false, 
	  cache: false,
	  processData: false,
	  success:function(data){
		//console.log(data);
			Swal.fire({
				title: "Berhasil !",
				text: "Foto Berhasil Upload!",
				confirmButtonColor: "#66BB6A",
				type: "success"
			}).then(function(t) {
				 window.location.reload()
			})
	  },
	  error:function(data){
		Swal.fire({
			title: "Gagal !",
			text: "Gagal Upload Foto!",
			confirmButtonColor: "#EF5350",
			type: "error"
		});
	  }
	});
		e.preventDefault(); 
	});*/


  });

</script>