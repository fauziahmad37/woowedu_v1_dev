	<script>
		function base64_encode(str) {
			return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
				function toSolidBytes(match, p1) {
					return String.fromCharCode('0x' + p1);
			}));
		}
		
		function base64_decode(str) {
			return decodeURIComponent(atob(str).split('').map(function(c) {
				return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
			}).join(''));
		}
	</script>
 
 
         <div class="d-flex"> 
            <div class="w-100">
              <h5 class="lh-2">
							<?php
						 
							echo $this->model_kelas->get_class_name($class_id);
							?> 							
							</h5>
            
            </div>
          </div>
          <hr>
          <div class="msg_history" style="height: 380px; overflow-y: scroll;">
					
					
 

			<?php
			$userid 				= $this->session->userdata('userid');
			foreach($chat_history as $val){
				if($val['uc_to_id']==$userid){
 
          echo '<div class="outgoing_msg">
              <div class="sent_msg">
                <p>'.$val['uc_message'].'</p>
                <span class="time_date">'.$val['uc_date'].'</span> </div>
            </div>';
								
				}else{
					echo '<div class="incoming_msg">
              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
              <div class="received_msg">
                <div class="received_withd_msg">
                  <p>'.$val['uc_message'].'</p>
                  <span class="time_date">'.$val['uc_date'].'</span></div>
              </div>
            </div>';								
				} 
			}
			?>			



						
          </div>
          <hr>

          <!-- Start kolom teks chat -->
          <div class="row">
            <div class="col">
                <input type="text" class="form-control" id="input_msg"  placeholder="Ketik pesan Anda...">
            </div>
            <div class="col-auto"> 
                <button class="btn-kirimchat" id="send_msg_btn">
                    Kirim
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20.5815 11.3465L4.74572 2.47843C4.6125 2.40383 4.45968 2.37166 4.30769 2.38624C4.1557 2.40081 4.01177 2.46143 3.89515 2.55999C3.77854 2.65855 3.69478 2.79035 3.65507 2.93779C3.61537 3.08523 3.62161 3.24127 3.67297 3.38506L6.65994 11.7486C6.7182 11.9117 6.7182 12.09 6.65994 12.2531L3.67297 20.6166C3.62161 20.7604 3.61537 20.9165 3.65507 21.0639C3.69478 21.2113 3.77853 21.3431 3.89515 21.4417C4.01177 21.5403 4.1557 21.6009 4.30769 21.6155C4.45968 21.63 4.6125 21.5979 4.74573 21.5233L20.5815 12.6552C20.6978 12.5901 20.7947 12.4951 20.8621 12.3801C20.9295 12.2651 20.965 12.1342 20.965 12.0008C20.965 11.8675 20.9295 11.7366 20.8621 11.6216C20.7947 11.5066 20.6978 11.4116 20.5815 11.3465Z" fill="white"/>
                        <path d="M6.75 12H12.75" stroke="#281B93" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
          <!-- Finish kolom teks chat -->
 

<script>

setInterval(loadchat, 1000);		

function loadchat(){ 
 $('.mesgs').load("<?=base_url('chat/msghistory')?>");
}


(function(){
	username = '<?=$username?>';
	room = <?=$class_id?>; 
  
	$("#send_msg_btn").click(function(){
		send();
	});	
 
	$("#input_msg").on("keyup", function(e){
		if(e.keyCode == 13){
			send();
		}
	});
	
	function send(){ 
		var msg = $("#input_msg").val();
		$.ajax({
			type: "POST",
			url: BASE_URL+"chat/savechat",
			data: { 
				msg: msg
			}, 
			dataType: "JSON",
		});	 
	}
	
 
})();
</script>					