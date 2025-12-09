<style>
	.profile-container {
    position: absolute; /* Menjadikan posisi elemen absolute */
    top: 60px; /* Menentukan posisi dari atas halaman */
    right: 20px; /* Menentukan posisi dari sisi kanan halaman */
    z-index: 1000; /* Menentukan urutan tampilan elemen agar muncul di atas konten utama */
    background-color: white; /* Memberi latar belakang putih */
    width: 200px; /* Sesuaikan lebar sesuai kebutuhan */
    padding: 10px; /* Sesuaikan padding sesuai kebutuhan */
    border: 1px solid #ccc; /* Garis tepi untuk menandai batas container */
    border-radius: 5px; /* Menggunakan sudut membulat untuk estetika */
}

.profile-image-menu {
    text-align: center; /* Posisi teks ke tengah */
    margin-bottom: 20px; /* Jarak bawah antara gambar profil dan teks */
}

.profile-image-menu img {
    border-radius: 50%; /* Membuat gambar profil menjadi lingkaran */
}

.profile-container p,
.profile-container a {
    margin-bottom: 10px; /* Jarak antara setiap paragraf atau tautan */
}

.profile-container hr {
    margin: 15px 0; /* Jarak sebelum dan sesudah garis pemisah */
}

.dg-paging{
	height: 36px;
	border-width: 1px;
	border-color: var(--bs-primary);
	border-style: solid;
	border-radius: 5px;
}

.pagination {
	padding: 0px !important;
}

.dg-number-pages{
	position:relative;
	height: 36px;
}

.dg-number-pages button {
	border-width: 1px !important;
	margin-left: 0px;
	margin-right: 0px;
	border-radius: 0px;
	width: 36px;
}

.dg-number-pages button.active {
    border: 0px;
	background-color: var(--bs-primary);
    color: var(--bs-white);
}

.pagination {
	align-items: center !important;
}

</style>

<section class="explore-section section-padding" id="section_2">
	



<div class="container-fluid">
	<!-- <h4>Ebook</h4> -->

	<!-- section search -->
	<form class="row mt-4 align-items-end" name="frm-search">
		<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-2">
			<label>Judul</label>
			<input type="text" class="form-control form-control-sm" name="filter[title]" placeholder="Ketik nama atau sebagian nama yang di cari"/>
		</div>	
		<!--<div class="col-3">
			<label>Kategori</label>
			<select class="form-select form-select-sm" name="filter[category]"></select>
		</div>-->	
		<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-2">
			<label>Penerbit</label>
			<select class="form-select form-select-sm" name="filter[penerbit]">
				<?php foreach($penerbit as $pub): ?>
					<option value="<?=html_escape($pub['id'])?>"><?=html_escape(trim($pub['publisher_name']))?></option>
				<?php endforeach; ?>
			</select>
		</div>	
		<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-2" id="btn-group-search">
			<div class="btn-group btn-group-sm">
				<!-- <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search text-white"></i></button> -->
				<button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search text-white"></i></button>
				<button type="reset" class="btn btn-sm btn-danger"><i class="bi bi-x text-white"></i></button>
			</div>
		</div>	
	</form>

	<!-- content -->
	<div class="row mt-4" id="content">

		<div id="grid" class="row">
			<div class="d-flex flex-column justify-content-center w-100">
				<i class="bi bi-database-x text-center" style="font-size: 5.8rem"></i>
				<h4 class="text-center">NO DATA</h4>
			</div>
		</div>

		<nav aria-label="Page navigation example" class="d-flex justify-content-center">
			<ul class="pagination">
				
			</ul>
		</nav>
		
	</div>
		
	
	
</section>


