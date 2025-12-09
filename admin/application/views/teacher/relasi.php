            <div class="row align-items-center">
            	<div class="col-6">
            		<h3>Kelas </h3>
            	</div>

            	<div class="col-6">
            		<h3>Mata Pelajaran</h3>
            	</div>
            </div>
            <div class="row align-items-center mt-2">
            	<div class="col-6" style="height:250px;overflow: auto;">
            		<!--	<input type="text" name="teacher_class" id="xteacher_class" value="," />
								<input type="text" name="teacher_subject" id="xteacher_subject" value="," />-->
            		<?php
					$teacher_id =  $_GET['id'];
					$teacher_class = $this->db->where('teacher_id', $teacher_id)->get('class_teacher');

					$_arr_teacher_class = [];
					foreach ($teacher_class->result() as $obj) $_arr_teacher_class[] = $obj->class_id;

					$_arr_teacher_subject = [];
					$teacher_subject = $this->db->where('teacher_id', $teacher_id)->get('subject_teacher');
					foreach ($teacher_subject->result() as $obj) $_arr_teacher_subject[] = $obj->subject_id;

					if (isset($_SESSION['sekolah_id'])) { // untuk user login admin sekolah karna punya sekolah_id
						$kelas = $this->db->where('sekolah_id', $_SESSION['sekolah_id'])->order_by('class_id')->get('kelas');
					} else { // untuk super admin
						$kelas = $this->db->order_by('class_id')->get('kelas');
					}

					$i = 0;
					foreach ($kelas->result() as $obj) {
						if (in_array($obj->class_id, $_arr_teacher_class)) {
					?>
            				<label><input type="checkbox" name="teacher_class[]" checked class=" teacher_class filled-in" id="teacher_class<?= $obj->class_id ?>" value="<?= $obj->class_id ?>" /></label>
            				&nbsp;&nbsp;&nbsp;<?= $obj->class_name ?><br />
            			<?php } else { ?>
            				<label><input type="checkbox" name="teacher_class[]" class="teacher_class filled-in" id="teacher_class<?= $obj->class_id ?>" value="<?= $obj->class_id ?>" /></label>
            				&nbsp;&nbsp;&nbsp;<?= $obj->class_name ?><br />
            		<?php
						}
						$i++;
					}
					?>
            	</div>

            	<div class="col-6" style="height:250px;overflow: auto;">
            		<?php
					if (isset($_SESSION['sekolah_id'])) { // untuk user login admin sekolah karna punya sekolah_id
						$subject = $this->db->where('sekolah_id', $_SESSION['sekolah_id'])->order_by('subject_id')->get('subject');
					} else { // untuk super admin
						$subject = $this->db->order_by('subject_id')->get('subject');
					}

					$i = 0;
					foreach ($subject->result() as $obj) {
						if (in_array($obj->subject_id, $_arr_teacher_subject)) {
					?>
            				<label><input type="checkbox" name="teacher_subject[]" checked class="filled-in teacher_subject" value="<?= $obj->subject_id ?>" /></label>
            				&nbsp;&nbsp;&nbsp;<?= $obj->subject_name ?><br />
            			<?php } else { ?>
            				<label><input type="checkbox" name="teacher_subject[]" class="filled-in teacher_subject" value="<?= $obj->subject_id ?>" /></label>
            				&nbsp;&nbsp;&nbsp;<?= $obj->subject_name ?><br />
            		<?php
							$i++;
						}
					}
					?>
            	</div>
            </div>
