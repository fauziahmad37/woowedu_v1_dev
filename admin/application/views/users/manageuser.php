<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content woowtix-bg red white-text">
        <span class="card-title"><?php echo $pageTitle; ?></span>
		<a href="<?php echo base_url('users'); ?>" class="btn-floating right halfway-fab waves-effect waves-light amber tooltipped" data-position="top" data-tooltip="Back"><i class="material-icons">replay</i></a>
      </div>
      <div class="card-content">
        <form class="row" id="add-user-form" method="post" action="<?php echo base_url();?>users/manageuser">
				  <input id="userid" name="userid" type="hidden" value="<?php echo $user->userid; ?>">
				  <input id="oldpass" name="oldpass" type="hidden" value="<?php echo $user->password; ?>">
          <?php if(validation_errors()): ?>
            <div class="col s12">
              <div class="card-panel red">
                <span class="white-text"><?php echo validation_errors('<p>', '</p>'); ?></span>
              </div>
            </div>
          <?php endif; ?>
          <?php if($message = $this->session->flashdata('message')): ?>
            <div class="col s12">
              <div class="card-panel <?php echo ($message['status']) ? 'green' : 'red'; ?>">
                <span class="white-text"><?php echo $message['message']; ?></span>
              </div>
            </div>
          <?php endif; ?>
          <div class="input-field col s12 m6">
              <input id="username" name="data[username]" type="text" value="<?php echo $user->username; ?>">
              <label for="username" class="">Username</label>
          </div>
          <div class="input-field col s12 m6">
              <input id="password" name="data[password]" type="password" value="<?php echo (empty($user->password))?'':'***' ; ?>">
              <label for="password" class="">Password</label>
          </div>
          <div class="input-field col s12 m6">
              <select id="user_level" name="data[user_level]">
															 <option value="" >--select level--</option>
							<?php
							
							$level = $this->db->get('user_level');
							foreach ($level->result() as $obj) {
								if($user->user_level==$obj->user_level_id){
							?>
							<option value="<?php echo $obj->user_level_id; ?>" selected><?php echo strtoupper($obj->user_level_name); ?></option>
							<?php 
								}else{?>
							<option value="<?php echo $obj->user_level_id; ?>"><?php echo strtoupper($obj->user_level_name); ?></option>
							<?php
							}
							}
							?>			
              </select>
              <label>Pilih Level</label>
          </div>
          <div class="input-field col s12 m6">
              <select id="active" name="data[active]">
                  <option value="0">Tidak</option>
                  <option value="1">Ya</option>
              </select>
              <label>Active</label>
          </div>
 
          <div class="input-field col s12 right-align">
              <button type="submit" name="submit" value="add_user" class="btn woowtix-bg red waves-effect waves-green">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>