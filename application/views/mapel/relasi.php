<div class="row align-items-center">
	<div class="col-6"><h3>Kelas  </h3></div>
</div>

<div class="row align-items-center mt-2">
	<div class="col-6" style="height:250px;overflow: auto;" >
		<?php
		$teacher_id =  $_GET['id'];
		// ambil semua kelas yang di ajar oleh guru
		$teacher_class = $this->db->where('teacher_id',$teacher_id)
						->join('kelas k', 'k.class_id = ct.class_id', 'left')
						->get('class_teacher ct');

		// ambil materi kelas
		$materi_kelas = $this->db->where('materi_id', $_GET['materi_id'])->get('materi_kelas mk');

		$_arr_materi_class = [];
		foreach ($materi_kelas->result() as $obj) $_arr_materi_class[] = $obj->class_id;

		// $kelas = $this->db->order_by('class_id')->get('kelas');
		$i=0;
		foreach ($teacher_class->result() as $obj) {
			if(in_array($obj->class_id,$_arr_materi_class)){ ?>

				<label><input  type="checkbox" name="teacher_class[]"  checked class=" teacher_class filled-in" id="teacher_class<?=$obj->class_id?>" value="<?=$obj->class_id?>" /></label>
				&nbsp;&nbsp;&nbsp;<?=$obj->class_name?><br />
			
			<?php 
			}else{ 
			?>
				<label><input  type="checkbox" name="teacher_class[]"    class="teacher_class filled-in"  id="teacher_class<?=$obj->class_id?>" value="<?=$obj->class_id?>" /></label>
				&nbsp;&nbsp;&nbsp;<?=$obj->class_name?><br />
				<?php
			}
				
			$i++;
		}
		?>
	
	</div>
</div>
