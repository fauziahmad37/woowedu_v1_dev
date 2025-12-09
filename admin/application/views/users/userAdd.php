<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content woowtix-bg red white-text">
        <span class="card-title"><?php echo $pageTitle; ?></span>
		<a href="<?php echo base_url('users'); ?>" class="btn-floating right halfway-fab waves-effect waves-light amber tooltipped" data-position="top" data-tooltip="Back"><i class="material-icons">replay</i></a>
      </div>
      <div class="card-content">
        <form class="row" id="add-user-form" method="post" action="">
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
              <input id="username" name="username" type="text" value="<?php echo set_value('username'); ?>">
              <label for="username" class="">Username</label>
          </div>
          <div class="input-field col s12 m6">
              <input id="password" name="password" type="password" value="<?php echo set_value('password'); ?>">
              <label for="password" class="">Password</label>
          </div>
          <div class="input-field col s12 m6">
              <select id="level" name="level">
                  <option value="administrator">Administrator</option>
                  <option value="user">User</option>
              </select>
              <label>Pilih Level</label>
          </div>
          <div class="input-field col s12 m6">
              <select id="active" name="active">
                  <option value="0">Tidak</option>
                  <option value="1">Ya</option>
              </select>
              <label>Active</label>
          </div>
          <div class="input-field col s12 m6">
              <input id="deposit" name="deposit" type="text" value="<?php echo set_value('deposit'); ?>">
              <label for="deposit" class="">Deposit</label>
          </div>
          <div class="input-field col s12 m6">
              <input id="rate" name="rate" type="text" value="<?php echo set_value('rate'); ?>">
              <label for="rate" class="">Rate</label>
          </div>
          <div class="input-field col s12 m6">
              <input id="telp" name="telp" type="text" value="<?php echo set_value('telp'); ?>">
              <label for="telp" class="">No. WA</label>
          </div>
          <div class="input-field col s12 right-align">
              <button type="submit" name="submit" value="add_user" class="btn woowtix-bg red waves-effect waves-green">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>