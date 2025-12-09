 <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<section class="explore-section section-padding" id="section_2">
	
	<div class="container">

	<div class="row" >

<form action="<?php echo base_url(); ?>student/save_exam" method="post" id="form_exam" >	
<h3 style="background-color:#FFFFFF;margin-left:50px;margin-right:50px"> 
Soal  
</h3><br />

	<?php 
	if($data_exam){
	$no=0;
		foreach($data_exam as $rec){
			$no++;
		?>		
			<div class="col-md-12">
			<div class="ceb-item-style-2" style="background-color:#FFFFFF;">
				
				<div class="ceb-infor" style="background-color:#FFFFFF; margin:0;padding:0"> 
					<table style=" margin:0;padding:0;border:1px solid" width="100%" >
					<tr>
						<td width="5%" style=" border-right:1px solid">
							<span style="color:#000000; font-size:18px;padding-left:20px;" ><?=$no?></span> 
						</td>
						<td>						
							<span style="color:#000000; font-size:18px;padding-left:20px;" ><?=$rec['question']?></span>
							<br />							<br />
							<?php
							if(!empty($rec['question_file']))
							{
								echo '<img src="'.base_url().'uploads/soal/'.$rec['question_file'].'" />';
								echo '<br /><br />';
							}	
							?>
							<input type="hidden" value="<?=$rec['soal_id']?>" name="soal_id<?=$no?>" />
							<input type="hidden" value="<?=$rec['answer']?>" name="correct_answer<?=$no?>" />
							
							<p style="color:#000000; font-size:18px;padding-left:20px;">
							
							Jawaban : <br />
							<?php
							if($rec['type']==1)
							{
							?>
								<input type="radio" name="answer<?=$no?>" value="a" /> A. <?=$rec['choice_a']?> <br />
								<?php
								if(!empty($rec['choice_a_file']))
								{
									echo '<img src="'.base_url().'uploads/soal/'.$rec['choice_a_file'].'" />';
									echo '<br /><br />';
								}	
								?>
								<input type="radio" name="answer<?=$no?>"  value="b" /> B. <?=$rec['choice_b']?> <br />
								<?php
								if(!empty($rec['choice_b_file']))
								{
									echo '<img src="'.base_url().'uploads/soal/'.$rec['choice_b_file'].'" />';
									echo '<br /><br />';
								}	
								?>							
								<input type="radio" name="answer<?=$no?>"  value="c"  /> C. <?=$rec['choice_c']?> <br />
								<?php
								if(!empty($rec['choice_c_file']))
								{
									echo '<img src="'.base_url().'uploads/soal/'.$rec['choice_c_file'].'" />';
									echo '<br /><br />';
								}	
								?>							
								<input type="radio" name="answer<?=$no?>" value="d"  /> D. <?=$rec['choice_d']?> <br />
								<?php
								if(!empty($rec['choice_d_file']))
								{
									echo '<img src="'.base_url().'uploads/soal/'.$rec['choice_d_file'].'" />';
									echo '<br /><br />';
								}	
							}elseif($rec['type']==2)
							{
							?>
							<textarea cols="40" rows="5" style="height:80px" name="answer<?=$no?>" ></textarea>
							<?php }elseif($rec['type']==3){ ?>	
							<input type="radio" name="answer<?=$no?>"  value="TRUE"  /> TRUE <br />							
							<input type="radio" name="answer<?=$no?>"  value="FALSE"  /> FALSE <br />							
							<?php } ?>							
							</p>
						
						</td>
						</tr>
					</table>
				</div>
			</div>
		</div> 
		<?php
			
		}
	 
	?>				
  <input type="hidden" value="<?=$no?>" name="total_soal" />
	<div class="col-md-2"></div>
	<div class="col-md-4;">
	 <input style="margin-top:30px" type="button" value="Submit" class="btn btn-primary"  id="examSubmit" class="btnsubmit" />
	  
	</div>
					 
</form>
	<?php
	}else{
	echo 'NO Data';
	}	
	?>
	</div>
										
	</div>

</section>
  
<script>
	$(function(){
		$("#examSubmit").click(function(){ 
			if (confirm("Apakah sudah yakin dengan jawaban-jawaban anda ? ") == true) {
				$('#form_exam').submit();
			} 
		});
	});
</script>