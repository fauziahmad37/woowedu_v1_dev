<section class="explore-section section-padding" id="section_2">
	<div class="container mt-4">
		<!-- <div class="card p-4"> -->
			<h5 class="text-center mt-3 mb-5"><?=$data['sesi_title']?></h5>
	
			<span class="fs-16">Waktu Sesi:</span>
			<span class=""><?=date('d M Y', strtotime($data['sesi_date'])).', Jam '.$data['sesi_jam_start'].' s/d '.$data['sesi_jam_end'] ?></span>
			<p></p>
			
			<div class="row">
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<span>Guru: <?=$data['teacher_name']?></span>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 text-end">
					<span class="">Mata Pelajaran: <?=$data['subject_name']?></span>
				</div>
			</div>
			
			<span class="mt-5">Detail Sesi:</span>
			<span class=""><?=$data['sesi_note']?></span>
		<!-- </div> -->
	</div>
</section>
