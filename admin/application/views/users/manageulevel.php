<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content woowtix-bg red white-text">
        <span class="card-title"><?php echo $pageTitle; ?></span>
		<a href="<?php echo base_url('users/listulevel'); ?>" class="btn-floating right halfway-fab waves-effect waves-light amber tooltipped" data-position="top" data-tooltip="Back"><i class="material-icons">replay</i></a>
      </div>
      <div class="card-content">
        <form class="row" id="add-user-form" method="post" action="<?php echo base_url();?>users/manageulevel">
				  <input id="level_id" name="level_id" type="hidden" value="<?php echo $level_id; ?>">
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
			<div class="col s12" style="padding: 0px 0px 0px 15px !important;">
				<div class="col s4">User Level</div>
				<div class="col s8"><input   id="user_level_name" class="left-align" name="user_level_name" type="text" value="<?=$ulevel?>"  ></div>
			</div>
			<div class="col s12" style="padding: 0px 0px 0px 15px !important;">
				<div class="col s4">Menu</div>
				<div class="col s8">	
					<div id="menu_list" style=" width:400px;height:350px; border:0px solid dimgray;overflow-x: hidden; overflow-y: scroll; ">
					<?php
					$menu = $this->db->where('menu_parent',0)->where('menu_id >',1)->order_by('menu_sort')->get('menu');
					$i=0;
				//	var_dump($menu_level);
					foreach ($menu->result() as $obj) {
						
						if(in_array($obj->menu_id,$menu_level)){
					?>
					<label><input class="check" type="checkbox" checked  name="menu_level_menu<?=$i?>" class="filled-in" value="<?=$obj->menu_id?>" /></label>&nbsp;&nbsp;&nbsp;<?=$obj->menu_name?><br />
					<?php }else{ ?>
					<label><input class="check" type="checkbox"  name="menu_level_menu<?=$i?>" class="filled-in" value="<?=$obj->menu_id?>" /></label>&nbsp;&nbsp;&nbsp;<?=$obj->menu_name?><br />
					<?php
					}
						$i++;
						$this->db->where('menu_parent', $obj->menu_sort);
						$menuchild = $this->db->get('menu')->result();
						foreach ($menuchild as $mc) {
							
							if(in_array($mc->menu_id,$menu_level)){
					?>

								<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input  checked   class="check" type="checkbox"  name="menu_level_menu<?=$i?>" class="filled-in" value="<?=$mc->menu_id?>" /></label>&nbsp;&nbsp;&nbsp;<?=$mc->menu_name?><br />
					
					<?php			
							}else{
					?>
								<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="check" type="checkbox"  name="menu_level_menu<?=$i?>" class="filled-in" value="<?=$mc->menu_id?>" /></label>&nbsp;&nbsp;&nbsp;<?=$mc->menu_name?><br />					
					<?php			
								
							}
								$i++;
						}
					
						
				
					}
					?>			
						</div>	
					<input type="hidden" name="total_menu" id="total_menu" value="<?=$i?>" />					
				</div>
			</div>			

 
 
          <div class="input-field col s12 right-align">
              <button type="submit" name="submit" value="add_user" class="btn woowtix-bg red waves-effect waves-green">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>