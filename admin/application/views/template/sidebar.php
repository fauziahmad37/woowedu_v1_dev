<aside style="bottom:0px">
  
  <ul id="sidenav" class="side-nav fixed">
    
    <!--<li class="side-logo">
      <div class="userView">
		  <?php  $logo = $this->session->userdata('img_logo');  ?>
          <img src="<?php echo base_url('uploads/logo/'.$logo); ?>" class="responsive-img">
      </div>
    </li>-->
	<li><a class="hello" href="#!name">Welcome, <?php echo $this->session->userdata('username'); ?></a></li>
	<div class="divider"></div>
    <?php
      $id_level = $this->session->userdata('level_id');
      $this->db->join('menu', 'menu.menu_id = menu_level.menu_level_menu');
      $this->db->where('menu.menu_parent', 0);
      $this->db->where('menu_level.menu_level_user_level', $this->session->userdata('user_level'));
      $this->db->order_by('menu_sort');
      $menuparent = $this->db->get('menu_level')->result();
      foreach ($menuparent as $mp) {
				if (trim($mp->menu_link) != '') {
					?>
					<li>
 
					<a class="waves-effect" href="<?php echo site_url($mp->menu_link); ?>">
						<i class="fa <?php echo $mp->menu_icon?> fa-lg" aria-hidden="true"></i><?php echo strtoupper($mp->menu_name); ?>
					</a>

					</li>
		<?php } else { 		 ?>
				<li>
				<div class="divider"></div>
				</li>
				<li class="subheaderblock">
				<a href="#!" class="subheader"><?php echo strtoupper($mp->menu_name); ?></a>
				</li>
        <?php
	
          $this->db->join('menu', 'menu.menu_id =  menu_level.menu_level_menu');
          $this->db->where('menu.menu_parent', $mp->menu_sort);
          $this->db->where('menu_level.menu_level_user_level', $this->session->userdata('user_level'));
          $menuchild = $this->db->get('menu_level')->result();
          foreach ($menuchild as $mc) {
				?>
				 <li>
					 <a class="waves-effect" href="<?php echo site_url($mc->menu_link); ?>"><i class="fa <?php echo $mc->menu_icon?> fa-lg" aria-hidden="true"></i>
					 <?php echo strtoupper($mc->menu_name); ?></a>
				 </li>				
				 </li>
				<?php		
					}
        ?>				
		<?php
			}
			}
		?>		
		<li>
		<div class="divider"></div>
		</li>		
    <li>
      <a class="waves-effect"  href="<?php echo base_url('auth/logout'); ?>"><i class="fa fa-sign-out fa-lg" aria-hidden="true"></i>LOG OUT</a>
    </li>
    
  </ul>
</aside>