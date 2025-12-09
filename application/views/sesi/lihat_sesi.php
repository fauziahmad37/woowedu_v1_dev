<style>
	button:disabled {
		background-color: #A2A5B3 !important;
		color: #FFF;
		border-color: #A2A5B3 !important;
	}
</style>

<?php
	$day = date('N', strtotime($sesi['sesi_date']));
	switch($day){
		case 1: 
			$day = 'Senin';
			break;
		case 2:
			$day = 'Selasa';
			break;
		case 3:
			$day = 'Rabu';
			break;
		case 4:
			$day = 'Kamis';
			break;
		case 5:
			$day = 'Jum\'at';
			break;
		case 6:
			$day = 'Sabtu';
			break;
		case 7:
			$day = 'Minggu';
			break;
	}
?>

<div class="card rounded-3">
	<div class="card-header bg-primary text-white rounded-top-3">
		<img src="assets/themes/space/icons/ic_sesi.svg" alt="" width="16">
		<span class="ms-2"><?=$day.', '.date('d M Y, ', strtotime($sesi['sesi_date'])).' '.$sesi['sesi_jam_start'].' - '.$sesi['sesi_jam_end']?></span>
	</div>
	<div class="card-body">
		<form action="" id="form-sesi">
			<input type="hidden" name="student_id" value="<?=isset($_SESSION['student_id']) ? $_SESSION['student_id'] : ''?>">
			<input type="hidden" name="sesi_id" value="<?=$sesi['sesi_id']?>">
			<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
	
			<h5 class="fw-bold"><?=$sesi['sesi_title']?></h5>
			<p>Kelas: <span class="badge bg-primary"><?=$sesi['class_name']?></span></p>
			<p class="">Catatan: </p>
			<p class="fs-1"><?=$sesi['sesi_note']?></p>
		</form>
	</div>
	<div class="card-footer text-end">
		<?php 
			$sesiStart = strtotime($sesi['sesi_date'].' '.$sesi['sesi_jam_start']);
			$sesiEnd = strtotime($sesi['sesi_date'].' '.$sesi['sesi_jam_end']);
			$disableSesi = (time() >= $sesiStart && time() <= $sesiEnd) ? '' : 'disabled';
		?>
		<button class="btn btn-primary text-white gabung-sesi <?=(isset($_SESSION['teacher_id']) || $_SESSION['user_level'] == 5) ? 'd-none' : '' ?>" <?=$disableSesi?>><?=isset($absen['sesi_start']) ? 'Akhiri Sesi' : 'Gabung Sesi' ?></button>
	</div>
</div>

