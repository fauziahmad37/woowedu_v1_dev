<section class="explore-section section-padding" id="section_2">
	
<div class="container pt-5">
	<img class="rounded-4 mb-3" src="<?=($data['image']) ? base_url().'assets/images/news/'.$data['image'] : base_url().'assets/images/news/pengumuman.png' ?>" alt="" width="300" height="200">

	<h4 class="fw-bold"><?=$data['judul']?></h4>
	<br>

	<p><?=date('d M Y H:i', strtotime($data['tanggal']))?></p>

	<br><br>

	<?=nl2br($data['isi'])?>
	

</div>

</section>
