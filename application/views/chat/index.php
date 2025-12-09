<style>
	/* width */
	::-webkit-scrollbar {
		width: 5px;
	}

	/* Track */
	::-webkit-scrollbar-track {
		background: #f1f1f1; 
	}
	
	/* Handle */
	::-webkit-scrollbar-thumb {
		background: #888; 
	}

	/* Handle on hover */
	::-webkit-scrollbar-thumb:hover {
		background: #555; 
	}
</style>

<section class="explore-section section-padding" id="section_2">
	<div class="container"> 
		<div class="messaging">
			<div class="inbox_msg" style="padding-bottom:20px" >

				<div class="inbox_people">
					<div class="row p-3">
							<div class="col">
									<h5 class="p-2 w-75">Ruang Chatting</h5>
							</div> 
					</div>
          <div class="px-4 pb-5">
            <input type="email" class="form-control" id="floatingInput" placeholder="Cari nama guru disini...">
          </div>

          <div class="inbox_chat">
 
 
 						<?php foreach($friend_list as $val){ ?>
							<div class="chat_list active_chat">
								<div class="chat_people">
								<a href="#" onclick="open_chatbox('<?=$val['nik']?>');return false;" >
									<div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
									<div class="chat_ib">
										<h5><?=$val['teacher_name']?></h5> 
									</div>
									</a>
								</div>
							</div> 
						<?php } ?>	
 
 
          </div>
        </div>				

        <div class="mesgs"></div>				
				
				
			</div>
		</div>
	</div>
</section>
 
<script> 
function open_chatbox(nik)
{ 
	$('.mesgs').load("<?=base_url()?>chat/chatbox?nik="+nik);
}

 $('.mesgs').load("<?=base_url('chat/chatbox')?>");
</script>  
