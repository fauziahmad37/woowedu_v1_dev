<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"> -->
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" /> -->
<!-- <link rel="stylesheet" href="assets/node_modules/pagination-system/dist/pagination-system.min.css" /> -->

<style>
	.fc-event-title { inline-size: 300px; overflow-wrap: break-word; } /* style untuk tab sesi */
	.fc-daygrid-dot-event .fc-event-title{ overflow-x: auto !important; } /* style untuk tab sesi */
	.select2-container {width: 100% !important;}
	.fc-daygrid-event-harness { border-radius: 14px; margin-bottom: 18px; margin-left: 24px; border: 1px solid rgba(0, 0, 0, .1); }
	/* .paginate_button.current {border-color: var(--bs-primary) !important; border-radius: 5px;} */
	/* a.paginate_button.current {background-color: var(--bs-primary);} */
</style>

<?php 
	$user_level = $this->session->userdata('user_level');
?>

<section class="explore-section section-padding" id="section_2">
	<div class="container">
		<?php if($user_level == 3 || $user_level == 6): ?>
		<p class="mt-4"><a href="<?=base_url()?>student" class="text-secondary">Semua Siswa</a> > <span class="fw-bold">Detail Siswa</span></p>
		<?php endif ?>
	</div>

	<input type="hidden" id="teacher_id" value="<?=isset($_SESSION['teacher_id']) ? $_SESSION['teacher_id'] : ''?>">
	<input type="hidden" id="student_id" value="<?=$detail['student_id']?>">
	<input type="hidden" id="class_id" value="<?=$detail['class_id']?>">
	<input type="hidden" id="class_level" value="<?=(isset($_SESSION['class_level_id']) && isset($_SESSION['student_id']) ) ? $_SESSION['class_level_id'] : '' ?>">
	<input type="hidden" id="sekolah_id" value="<?=$_SESSION['sekolah_id']?>">
	<input type="hidden" id="user_level" value="<?=$_SESSION['user_level']?>">

	<div class="row mt-5 <?=($_SESSION['user_level'] == 4) ? 'd-none':''?>">
		<div class="col-xl-5 col-lg-5 col-md-6 col-sm-8 col-xs-12">
			<select class="form-select" name="a_select_student" id="a_select_student">
				<?php foreach ($students as $key => $val) : ?>
					<?php if($val['student_id'] == $detail['student_id']) : ?>
						<option value="<?=$val['student_id']?>" selected><?=$val['student_name']?></option>
					<?php else : ?>
						<option value="<?=$val['student_id']?>"><?=$val['student_name']?></option>
					<?php endif ?>
				<?php endforeach  ?>
			</select>
		</div>
	</div>

	<div class="row mt-3">

		<div class="col-xl-5 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3">
			<div class="container p-0 h-100 mb-3 border border-primary border-2 rounded-4" style="height: 162px;">
				<div class="row g-0 card-image-profile" style="height: 100%;">
					<div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-xs-12 position-relative p-0">
						<img src="assets/images/bg-profile.png" class="img-fluid rounded-start-4" alt="..." style="height: 100%;">
						<span class="position-absolute top-50 start-50 translate-middle border border-3 rounded-circle">
							<img src="<?=base_url('assets/images/users/').$detail['photo']?>" alt="" class="rounded-circle w-100">
						</span>
					</div>
					<div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-xs-12 pt-3">
						
							<p style="font-size: 12px;">
								<img class="ms-3 me-2" src="assets/themes/space/icons/user-graduate-svgrepo-com.svg" alt="">
								<?=$detail['student_name']?>
							</p>
							<p style="font-size: 12px;">
								<img class="ms-3 me-2" src="assets/themes/space/icons/student-cap-svgrepo-com.svg" alt="">
								<?=$detail['nama_sekolah']?>
							</p>
							<p style="font-size: 12px;">
								<img class="ms-3 me-2" src="assets/themes/space/icons/class-scene-svgrepo-com.svg" alt="">
								<?=$detail['class_name']?>
							</p>
							<p style="font-size: 12px;">
								<img class="ms-3 me-2" src="assets/themes/space/icons/email-svgrepo-com.svg" alt="">
								<?=$detail['email']?>
							</p>
							<p style="font-size: 12px;">
								<img class="ms-3 me-2" src="assets/themes/space/icons/family-silhouette-svgrepo-com.svg" alt="">
								<?=$detail['parent_name']?>
							</p>
						
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
			<div class="container h-100 mb-3 border border-primary border-2 rounded-4 d-flex align-items-center" style="max-width: 540px;">
				<div class="row g-0 d-flex align-items-center">
					<div class="col-xl-8 col-lg-8 col-md-6 col-sm-6 col-xs-6">
						<h5 class="fw-bold ms-3">Total tugas yang diberikan oleh guru</h5>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-6">
						<p class="fw-bold text-primary total-tugas text-end me-3" style="font-size: 64px;">0</p>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-3">
			<div class="container h-100 mb-3 border border-primary border-2 rounded-4 d-flex align-items-center" style="max-width: 540px;">
				<div class="row g-0 d-flex align-items-center">
					<div class="col-xl-8 col-lg-8 col-md-6 col-sm-6 col-xs-6">
						<h5 class="fw-bold ms-3">Total tugas yang sudah dikerjakan</h5>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-6">
						<h1 class="fw-bold text-primary total-tugas-dikerjakan text-end me-3" style="font-size: 64px;">0</h1>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<!-- profile content -->
		<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 mb-1 card-foto-siswa">
			
		</div>
	</div>

	<!-- laporan kinerja siswa -->
	<div class="container mt-5">

		<div class="select-group mb-4">
			<h5 class="mt-1 fw-bold">Laporan Kinerja Siswa</h5>
			<select class="form-select" name="select-menu" id="select-menu">
				<option value="nav-sesi">Jadwal Sesi</option>
				<option value="nav-kehadiran">Kehadiran Sesi</option>
				<option value="nav-materi-guru">Materi Guru</option>
				<option value="nav-tugas">Tugas</option>
				<option value="nav-ujian">Asesmen</option>
				<option value="nav-ebook">Ebook</option>
				<option value="nav-absen">Absensi</option>
			</select>
		</div>

			<nav>
				<div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
					<div class="col-4">
						<h5 class="mt-1 fw-bold">Laporan Kinerja Siswa</h5>
					</div>
					<div class="col-8">
						<button class="nav-link d-inline-block active" id="nav-sesi-tab" data-bs-toggle="tab" data-bs-target="#nav-sesi" type="button" role="tab" aria-controls="nav-sesi" aria-selected="true"><i class="fa-regular fa-calendar-days h6"></i> Jadwal Sesi</button>
						<button class="nav-link d-inline-block" id="nav-kehadiran-tab" data-bs-toggle="tab" data-bs-target="#nav-kehadiran" type="button" role="tab" aria-controls="nav-kehadiran" aria-selected="false"><i class="fa-solid fa-clock h6"></i> Kehadiran Sesi</button>					
						<button class="nav-link d-inline-block" id="nav-materi-guru-tab" data-bs-toggle="tab" data-bs-target="#nav-materi-guru" type="button" role="tab" aria-controls="nav-materi-guru" aria-selected="false"><i class="fa-solid fa-chalkboard-user h6"></i> Materi Guru</button>
						<button class="nav-link d-inline-block" id="nav-tugas-tab" data-bs-toggle="tab" data-bs-target="#nav-tugas" type="button" role="tab" aria-controls="nav-tugas" aria-selected="false"><i class="fa-solid fa-pen-clip h6"></i> Tugas</button>  
						<button class="nav-link d-inline-block" id="nav-ujian-tab" data-bs-toggle="tab" data-bs-target="#nav-ujian" type="button" role="tab" aria-controls="nav-ujian" aria-selected="false"><i class="fa-solid fa-list-check h6"></i> Asesmen</button>
						<button class="nav-link d-inline-block" id="nav-ebook-tab" data-bs-toggle="tab" data-bs-target="#nav-ebook" type="button" role="tab" aria-controls="nav-ebook" aria-selected="false"><i class="fa-solid fa-book-bookmark h6"></i> Ebook</button>
						<button class="nav-link d-inline-block" id="nav-absen-tab" data-bs-toggle="tab" data-bs-target="#nav-absen" type="button" role="tab" aria-controls="nav-absen" aria-selected="false"><i class="fa-solid fa-book-bookmark h6"></i> Absensi</button>						
					</div>
				</div>
			</nav>
			

				
			<div class="tab-content mb-4" id="nav-tabContent" style="overflow-x: auto;">
			
				<div class="tab-pane fade" id="nav-kehadiran" role="tabpanel" aria-labelledby="nav-kehadiran-tab" tabindex="0">
					<table class="table table-rounded" id="tableKehadiran" style="width: 100%;">
						<thead class="bg-primary text-white"> 				
							<tr>
								<th>No</th>
								<th>Nama Sesi</th>
								<th>Tanggal Sesi</th>
								<th>Waktu Sesi</th>
								<th>Status Kehadiran</th>  
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				
				<div class="tab-pane fade" id="nav-materi-guru" role="tabpanel" aria-labelledby="nav-materi-guru-tab" tabindex="0">

					<div class="row mb-5 mt-3 cari-materi-group">
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-2">
							<select class="js-example-basic-single" name="s_materi_guru_mapel" id="s_materi_guru_mapel"></select>
						</div>
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-2">
							<select class="form-select" style="width: 100%;" name="s_materi_guru_nama_guru" id="s_materi_guru_nama_guru"></select>
						</div>
						<div class="col-xl-2 col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<button class="btn btn-primary rounded-3 border-2 fw-semibold" id="cari-materi"><i class="bi bi-search"></i> Cari</button>
						</div>
					</div>

					<table class="table table-rounded" id="tableMateriGuru" style="width: 100%;">
						<thead class="bg-primary text-white">
							<tr>
								<th>Nama Guru</th>
								<th>Nama Mapel</th>
								<th>Nama Materi</th>
								<th>Terakhir di Update</th>
								<th>File Tautan</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>

				<div class="tab-pane fade" id="nav-tugas" role="tabpanel" aria-labelledby="nav-tugas-tab" tabindex="0">
					<table class="table table-rounded" id="tableTask" style="width: 100%;">
						<thead class="bg-primary text-white">
							<tr>
								<th>Id</th>
								<th>Kode</th>
								<th>Mata Pelajaran</th>
								<th>Guru</th>
								<th>Ditugaskan</th>
								<th>Batas waktu</th>
								<th>Tanggal penyerahan</th>
								<th>File</th>
								<th>Status Tugas</th>
								<th>Nilai</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>

				<div class="tab-pane fade" id="nav-ujian" role="tabpanel" aria-labelledby="nav-ujian-tab" tabindex="0">
					<table class="table table-rounded w-100" id="tableExam">
						<thead class="bg-primary text-white">
							<tr>
								<th>Id</th>
								<th>Nama Asesmen</th>
								<th>Nama Mapel</th>
								<th>Guru</th>
								<th>Batas Waktu</th>
								<th>Tanggal Pengerjaan</th>
								<th>Status Asesmen</th>
								<th>Total Nilai</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>

				<div class="tab-pane fade" id="nav-ebook" role="tabpanel" aria-labelledby="nav-ebook-tab" tabindex="0">
					<div class="container">
						<table class="table table-rounded w-100" id="tableBookHistory">
							<thead class="bg-primary text-white">
								<tr>
									<th>Id</th>
									<th>Cover</th>
									<th>Terakhir dibaca</th>
									<th>Kode Buku</th>
									<th>Title</th>
									<th>Kategori</th>
									<th>Pengarang</th>
									<th>Tahun</th>
									<th>Deskripsi</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
	

				<div class="tab-pane fade" id="nav-absen" role="tabpanel" aria-labelledby="nav-absen-tab" tabindex="0">
					<div class="container">
						<table class="table table-rounded w-100" id="tableAbsensi">
							<thead class="bg-primary text-white">
								<tr>
									<th style="text-align:center">Tanggal</th>
									<th style="text-align:center">Absen Masuk</th>
									<th style="text-align:center">Absen Pulang</th>
									<th style="text-align:center">Keterangan</th>
									<th style="text-align:center">Terlambat</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>	
				<div class="tab-pane fade show active" id="nav-sesi" role="tabpanel" aria-labelledby="nav-sesi-tab" tabindex="0">
					<div id="calendar" class="mb-3"></div>
				</div>
			</div>
		
	</div>
</section>

<!-- MODAL VIEW BOOK -->
<div id="modal-show" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">DETAIL BUKU</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <img data-item="cover_img" class="img-fluid" src="" alt="">
                    </div>
                    <aside class="col-12 col-lg-9">
                        <h3 class="mb-4 text-dark" data-item="title"></h3>
                        <dl class="row">
                            <dt class="col-4 text-primary">
                                Kode Buku
                            </dt>
							<dd class="col-8 mb-1">
                                :&nbsp;<span data-item="book_code"></span>
                            </dd>
                            <dt class="col-4 text-primary">
                                Penulis
                            </dt>
                             <dd class="col-8 mb-1">
                                :&nbsp;<span data-item="author"></span>
                            </dd>
                            <dt class="col-4 text-primary">
                                Penerbit
                            </dt>
                             <dd class="col-8 mb-1">
                                :&nbsp;<span data-item="publisher_name"></span>
                            </dd>
                            <dt class="col-4 text-primary">
                                Tahun Terbit
                            </dt>
                             <dd class="col-8 mb-1">
                                :&nbsp;<span data-item="publish_year"></span>
                            </dd>
                            <dt class="col-4 text-primary">
                                ISBN
                            </dt>
                             <dd class="col-8 mb-1">
                                :&nbsp;<span data-item="isbn"></span>
                            </dd>
							<dt class="col-4">
                                
                            </dt>
                             <dd class="col-8 mb-1 mt-3">
                                <a href="" class="read-link btn btn-primary d-inline text-white">Baca</a>
                            </dd>

							
                        </dl>
                        
                    </aside>
                    <span class="col-12">
                        <hr class="my-3" />
                        <h6 class="font-weight-bold text-primary">Deskripsi</h6>
                        <p data-item="description" class="text-justify font-weight-light text-dark fs-14"></p>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?=base_url('assets/node_modules/moment/moment.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/node_modules/daterangepicker/daterangepicker.js')?>"></script>
<script src="<?=base_url('assets/fullcalendar/index.global.js')?>"></script> 
<!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
<!-- <script src="<?//=base_url('assets/js/student_detail.js')?>"></script> -->
