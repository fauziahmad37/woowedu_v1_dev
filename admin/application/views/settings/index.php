<div class="row">
	<div class="col-6">

			<div class="card">
			<div class="card-header bg-primary">
				<h4 class="card-title text-white">
					Harga Tiket Masuk
				</h4>
			</div>
 
			<form class="form-horizontal editDataHTM" action="#" method="post" id="form_htm">
			<div class="card-body"> 
					<div class="row">
						<div class="col-md-8 ">
							<h4 class="text-black">Non Rombongan</h4>
						</div>						
					</div>
					<div class="row">
						<div class="col-md-4 ">
							<h5 class="text-black">Hari Biasa</h5>
						</div>						
						<div class="col-md-4 ">
							<h5 class="text-black"> 
						</div>
						<div class="col-md-4 ">
							<h5 class="text-black"> </h5>
						</div>							
					</div>					
					<div class="row">			
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Dewasa</label>
								<input type="number" class="form-control" id="dewasa_non" name="dewasa_non" value="<?php echo $htm_non->harga_dewasa ?>"   min="0" >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Anak</label>
								<input type="number" class="form-control" id="anak_non" name="anak_non" value="<?php echo $htm_non->harga_anak ?>"   min="0" >
							</div>
						</div>						
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Lansia</label>
								<input type="number" class="form-control" id="lansia_non" name="lansia_non" value="<?php echo $htm_non->harga_lansia ?>"   min="0" >
							</div>
						</div> 
					</div>				
					<div class="row">
						<div class="col-md-4 ">
							<h5 class="text-black">Sabtu / Minggu</h5>
						</div>						
						<div class="col-md-4 ">
							<h5 class="text-black">
						</div>
						<div class="col-md-4 ">
							<h5 class="text-black"></h5>
						</div>							
					</div>					
					<div class="row">			 
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Dewasa</label>
								<input type="number" class="form-control" id="dewasa_non_weekend" name="dewasa_non_weekend" value="<?php echo $htm_non->harga_dewasa_weekend ?>"    min="0">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Anak</label>
								<input type="number" class="form-control" id="anak_non_weekend" name="anak_non_weekend" value="<?php echo $htm_non->harga_anak_weekend ?>"    min="0">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Lansia</label>
								<input type="number" class="form-control" id="lansia_non_weekend" name="lansia_non_weekend" value="<?php echo $htm_non->harga_lansia_weekend ?>"    min="0">
							</div>
						</div>						
					</div>		
					<div class="row">
						<div class="col-md-4 ">
							<h5 class="text-black">Libur</h5>
						</div>						
						<div class="col-md-4 ">
							<h5 class="text-black">
						</div>
						<div class="col-md-4 ">
							<h5 class="text-black"></h5>
						</div>							
					</div>					
					<div class="row">			 
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Dewasa</label>
								<input type="number" class="form-control" id="dewasa_non_libur" name="dewasa_non_libur" value="<?php echo $htm_non->harga_dewasa_libur ?>"    min="0">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Anak</label>
								<input type="number" class="form-control" id="anak_non_libur" name="anak_non_libur" value="<?php echo $htm_non->harga_anak_libur ?>"    min="0">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Lansia</label>
								<input type="number" class="form-control" id="lansia_non_libur" name="lansia_non_libur" value="<?php echo $htm_non->harga_lansia_libur ?>"    min="0">
							</div>
						</div>						
					</div>						
			</div>			
			
			<hr />
			
			<div class="card-body"> 
					<div class="row">
						<div class="col-md-8 ">
							<h4 class="text-black">Rombongan Umum</h4>
						</div>						
					</div>
					<div class="row">
						<div class="col-md-4 ">
							<h5 class="text-black">Hari Biasa</h5>
						</div>						
						<div class="col-md-4 ">
							<h5 class="text-black"> 
						</div>
						<div class="col-md-4 ">
							<h5 class="text-black"> </h5>
						</div>							
					</div>					
					<div class="row">			
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Dewasa</label>
								<input type="number" class="form-control" id="dewasa_rombongan" name="dewasa_rombongan" value="<?php echo $htm_rombongan->harga_dewasa ?>"   min="0" >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Anak</label>
								<input type="number" class="form-control" id="anak_rombongan" name="anak_rombongan" value="<?php echo $htm_rombongan->harga_anak ?>"   min="0" >
							</div>
						</div>						
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Lansia</label>
								<input type="number" class="form-control" id="lansia_rombongan" name="lansia_rombongan" value="<?php echo $htm_rombongan->harga_lansia ?>"   min="0" >
							</div>
						</div> 
					</div>				
					<div class="row">
						<div class="col-md-4 ">
							<h5 class="text-black">Sabtu / Minggu</h5>
						</div>						
						<div class="col-md-4 ">
							<h5 class="text-black">
						</div>
						<div class="col-md-4 ">
							<h5 class="text-black"></h5>
						</div>							
					</div>					
					<div class="row">			 
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Dewasa</label>
								<input type="number" class="form-control" id="dewasa_rombongan_weekend" name="dewasa_rombongan_weekend" value="<?php echo $htm_rombongan->harga_dewasa_weekend ?>"    min="0">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Anak</label>
								<input type="number" class="form-control" id="anak_rombongan_weekend" name="anak_rombongan_weekend" value="<?php echo $htm_rombongan->harga_anak_weekend ?>"    min="0">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Lansia</label>
								<input type="number" class="form-control" id="lansia_rombongan_weekend" name="lansia_rombongan_weekend" value="<?php echo $htm_rombongan->harga_lansia_weekend ?>"    min="0">
							</div>
						</div>						
					</div>		
					<div class="row">
						<div class="col-md-4 ">
							<h5 class="text-black">Libur</h5>
						</div>						
						<div class="col-md-4 ">
							<h5 class="text-black">
						</div>
						<div class="col-md-4 ">
							<h5 class="text-black"></h5>
						</div>							
					</div>					
					<div class="row">			 
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Dewasa</label>
								<input type="number" class="form-control" id="dewasa_rombongan_libur" name="dewasa_rombongan_libur" value="<?php echo $htm_rombongan->harga_dewasa_libur ?>"    min="0">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Anak</label>
								<input type="number" class="form-control" id="anak_rombongan_libur" name="anak_rombongan_libur" value="<?php echo $htm_rombongan->harga_anak_libur ?>"    min="0">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Lansia</label>
								<input type="number" class="form-control" id="lansia_rombongan_libur" name="lansia_rombongan_libur" value="<?php echo $htm_rombongan->harga_lansia_libur ?>"    min="0">
							</div>
						</div>						
					</div>						
			</div>	
			
			<hr />
			
			<div class="card-body"> 
					<div class="row">
						<div class="col-md-8 ">
							<h4 class="text-black">Rombongan Sekolah</h4>
						</div>						
					</div>
					<div class="row">
						<div class="col-md-4 ">
							<h5 class="text-black">Hari Biasa</h5>
						</div>						
						<div class="col-md-4 ">
							<h5 class="text-black"> 
						</div>
						<div class="col-md-4 ">
							<h5 class="text-black"> </h5>
						</div>							
					</div>					
					<div class="row">			
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Dewasa</label>
								<input type="number" class="form-control" id="dewasa_sekolah" name="dewasa_sekolah" value="<?php echo $htm_sekolah->harga_dewasa ?>"   min="0" >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Anak</label>
								<input type="number" class="form-control" id="anak_sekolah" name="anak_sekolah" value="<?php echo $htm_sekolah->harga_anak ?>"   min="0" >
							</div>
						</div>						
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Lansia</label>
								<input type="number" class="form-control" id="lansia_sekolah" name="lansia_sekolah" value="<?php echo $htm_sekolah->harga_lansia ?>"   min="0" >
							</div>
						</div> 
					</div>				
					<div class="row">
						<div class="col-md-4 ">
							<h5 class="text-black">Sabtu / Minggu</h5>
						</div>						
						<div class="col-md-4 ">
							<h5 class="text-black">
						</div>
						<div class="col-md-4 ">
							<h5 class="text-black"></h5>
						</div>							
					</div>					
					<div class="row">			 
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Dewasa</label>
								<input type="number" class="form-control" id="dewasa_sekolah_weekend" name="dewasa_sekolah_weekend" value="<?php echo $htm_sekolah->harga_dewasa_weekend ?>"    min="0">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Anak</label>
								<input type="number" class="form-control" id="anak_sekolah_weekend" name="anak_sekolah_weekend" value="<?php echo $htm_sekolah->harga_anak_weekend ?>"    min="0">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Lansia</label>
								<input type="number" class="form-control" id="lansia_sekolah_weekend" name="lansia_sekolah_weekend" value="<?php echo $htm_sekolah->harga_lansia_weekend ?>"    min="0">
							</div>
						</div>						
					</div>		
					<div class="row">
						<div class="col-md-4 ">
							<h5 class="text-black">Libur</h5>
						</div>						
						<div class="col-md-4 ">
							<h5 class="text-black">
						</div>
						<div class="col-md-4 ">
							<h5 class="text-black"></h5>
						</div>							
					</div>					
					<div class="row">			 
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Dewasa</label>
								<input type="number" class="form-control" id="dewasa_sekolah_libur" name="dewasa_sekolah_libur" value="<?php echo $htm_sekolah->harga_dewasa_libur ?>"    min="0">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Anak</label>
								<input type="number" class="form-control" id="anak_sekolah_libur" name="anak_sekolah_libur" value="<?php echo $htm_sekolah->harga_anak_libur ?>"    min="0">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="site_title">Lansia</label>
								<input type="number" class="form-control" id="lansia_sekolah_libur" name="lansia_sekolah_libur" value="<?php echo $htm_sekolah->harga_lansia_libur ?>"    min="0">
							</div>
						</div>						
					</div>						
			</div>	

			<div class="card-body"> 
					<div class="row">
						<div class="col-md-8 ">
							<h4 class="text-black">Jumlah Deposit</h4>
						</div>						
					</div> 				
					<div class="row">			
						<div class="col-md-3">
							<div class="form-group"> 
								<input type="number" class="form-control" id="biaya_deposit" name="biaya_deposit" value="<?php echo $dt->biaya_deposit ?>"   min="0" >
							</div>
						</div>					
					</div>	
				
				
			</div>	
			<div class="card-body"> 
					<div class="row">
						<div class="col-md-8 ">
							<h4 class="text-black">Jumlah Service Fee</h4>
						</div>						
					</div> 				
					<div class="row">			
						<div class="col-md-3">
							<div class="form-group"> 
								<input type="number" class="form-control" id="service_fee" name="service_fee" value="<?php echo $dt->service_fee ?>"   min="0" >
							</div>
						</div>					
					</div>	
				
				
			</div>				
					<div class="mt-4">
						<input type="submit" class="btn btn-primary float-right waves-effect waves-light" value="Simpan HTM">
					</div>
				</form>				
		</div>
	</div> <!-- end col -->

	<div class="col-6">
		<div class="card">
			<div class="card-header bg-primary">
				<h4 class="card-title text-white">
					Jam Operasional
				</h4>
			</div>

			<form class="form-horizontal editDataJam" action="#" method="post" id="form_jam">
			<div class="card-body"> 
					<div class="row">
						<div class="col-md-8 ">
							<h5 class="text-black">Hari Biasa</h5>
						</div>						
					</div>					
					<div class="row">			
						<div class="col-md-4">
							<div class="form-group">
								<label for="site_title">Jam Buka</label>
								<input type="text" class="form-control" id="jam_buka" name="jam_buka" value="<?php echo $dt->jam_buka ?>"    >
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="site_title">Jam Tutup</label>
								<input type="text" class="form-control" id="jam_tutup" name="jam_tutup" value="<?php echo $dt->jam_tutup ?>"    >
							</div>
						</div>						
					</div>
				
				
					<div class="row">
						<div class="col-md-8 ">
							<h5 class="text-black">Sabtu / Minggu / Libur </h5>
						</div>						
					</div>
					
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="site_title">Jam Buka</label>
								<input type="text" class="form-control" id="jam_buka_libur" name="jam_buka_libur" value="<?php echo $dt->jam_buka_libur ?>"   >
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="site_title">Jam Tutup</label>
								<input type="text" class="form-control" id="jam_tutup_libur" name="jam_tutup_libur" value="<?php echo $dt->jam_tutup_libur ?>"   >
							</div>
						</div>						
					</div>				
			</div>			
			
			
					
					<div class="mt-4">
						<input type="submit" class="btn btn-primary float-right waves-effect waves-light" value="Simpan Jam Operasional">
					</div>
				</form>
			</div>
		</div>
	</div> <!-- end col -->
</div> <!-- end row -->

<script>

$(document).ready(function() {

	$('.editDataHTM').on('submit',function(e) {
	$.ajax({
	  url: '<?php echo base_url().'settings/update' ?>', //nama action script php
	  data: new FormData(this),
	  type:'POST',
	  contentType: false, 
	  cache: false,
	  processData: false,
	  success:function(data){
		//console.log(data);
			Swal.fire({
				title: "Berhasil !",
				text: "Data Berhasil Update!",
				confirmButtonColor: "#66BB6A",
				type: "success"
			}).then(function(t) {
				 window.location.reload()
			})
	  },
	  error:function(data){
		Swal.fire({
			title: "Gagal !",
			text: "Gagal Update Data!",
			confirmButtonColor: "#EF5350",
			type: "error"
		});
	  }
	});
	e.preventDefault(); 
	});

	$('.editDataJam').on('submit',function(e) {
	$.ajax({
	  url: '<?php echo base_url().'settings/updatejam' ?>', //nama action script php
	  data: new FormData(this),
	  type:'POST',
	  contentType: false, 
	  cache: false,
	  processData: false,
	  success:function(data){
		//console.log(data);
			Swal.fire({
				title: "Berhasil !",
				text: "Data Berhasil Update!",
				confirmButtonColor: "#66BB6A",
				type: "success"
			}).then(function(t) {
				 window.location.reload()
			})
	  },
	  error:function(data){
		Swal.fire({
			title: "Gagal !",
			text: "Gagal Update Data!",
			confirmButtonColor: "#EF5350",
			type: "error"
		});
	  }
	});
	e.preventDefault(); 
	});	 

});

</script>