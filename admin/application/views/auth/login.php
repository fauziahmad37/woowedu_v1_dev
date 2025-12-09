<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="theme-color" content="#ffffff">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo base_url('assets/images/favicon'); ?>/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url('assets/images/favicon'); ?>/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url('assets/images/favicon'); ?>/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('assets/images/favicon'); ?>/favicon-16x16.png">
	<link rel="manifest" href="<?php echo base_url('assets/images/favicon'); ?>/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php echo base_url('assets/images/favicon'); ?>/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
    <title>Woow VMS Login</title>
    <link href="<?php echo base_url('assets/css/material.css'); ?>"  rel="stylesheet">
  <?php
  if (!empty($getconf->style)) {
	  $style = $getconf->style;
	  	}
  else {
	  $style = 'blue-light';
	  }
  ?>
	<link href="<?php echo base_url('assets/css/styles/'.$style.'.css'); ?>" rel="stylesheet">
   <link href="<?php echo base_url('assets/materialize/css/materialize.min.css'); ?>" type="text/css" rel="stylesheet" media="screen,projection"/>

    <style>
      main {
        flex: 1 0 auto;
      }
      .login-box {
        margin-top: 30px;
		max-width:500px;
      }
	  .card {
		  background-color:rgba(0, 0, 0, 0.5) !important;
	  }
	  input#username, input#password {
		  background-color:#ffffff;
		  border-radius:0px;
		  padding:0px 10px;
		  border:1px solid #ccc;
	  }
	  .input-field .prefix{
		  color:#000 !important;
	  }
	  .face-title {
	  text-align:center;
	  text-transform:uppercase;
	  font-size: 200%;
		font-weight: bold;
	  }
select {
    display: block;
}	  
    </style>
</head>
  <?php
  if (!empty($getconf->img_login)) {
	  $imglogin = $getconf->img_login;
  }
  else {
	  $imglogin = 'default-login.php';
  }	
  ?>
	
  <body style="/*background: url('<?php echo base_url('uploads/logo/'.$imglogin.''); ?>') no-repeat center center fixed;*/ -webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;background-position:center top;">
    <!--<nav class="red" role="navigation">
	    <?php
	    if (!empty($getconf->title)) {
	  	  $vmstitle = $getconf->title;
	    }
	    else {
	  	  $vmstitle = 'Woow VMS';
	    }	
	    ?>
      <div class="nav-wrapper container center"><a style="position:relative;" id="logo-container" href="<?php echo base_url(); ?>" class="brand-logo"><?php echo $vmstitle;?></a></div>
    </nav>-->
    <main style="">
      <div class="left-align login-box" style="margin: 0 auto";>
		  
        <div class="card" style="background-color:#fff !important;box-shadow:unset !important;margin-top:150px !important;">
          <div class="card-content">
			  <div class="center-align">
									  <?php
				  if (!empty($getconf->img_logo)) {
					  $logo = $getconf->img_logo;
				  }
				  else {
					  $logo = 'default-logo.jpg';
				  }	
				  ?>
		          <!--<img width="auto" height="120px" src="<?php echo base_url('uploads/logo/'.$logo); ?>" class="responsive-img">-->
			  </div>
            
            <div class="row">
			<div class="center-align">
				<img width="150px" height="auto" src="<?php echo base_url();?>assets/images/face-img.png">
			</div>
			
			 <div class="face-title">Face Recognition<br /> 
			 Management System</div>
              <form class="col s12" id="login-form" method="post" action="<?php echo base_url('auth/login'); ?>">
                <div class="row">
				 

                  <?php if(validation_errors()): ?>
                  <div class="col s12">
                    <div class="card-panel red">
                      <span class="white-text"><?php echo validation_errors('<p>', '</p>'); ?></span>
                    </div>
                  </div>
                  <?php endif; ?>						

                  <div class="input-field col m12">
					 <!--<i class="material-icons prefix">account_circle</i>-->
					 <div class="col s3" style="padding-top:12px;">LOGIN ID</div>
					 <div class="col s9"><input id="username" type="text" class="validate" name="username"></div>
                    
                    <!--<label for="username">Username</label>-->
                  </div>
                  <div class="input-field col m12">
					  <!--<i class="material-icons prefix">lock_outline</i>-->
					  <div class="col s3" style="padding-top:12px;">PASSWORD</div>
                    <div class="col s9"><input id="password" type="password" class="validate" name="password"></div>
                   <!-- <label for="password" data-error="Password yang anda masukkan salah">Password</label>-->
					</div>
				  
                  <div class="input-field col m12"> 
					  <div class="col s3" style="padding-top:12px;">LOCATION</div>
                    <div class="col s9">
						<select id="location" name="location">
						<!--		 <option value="" >--select --</option>-->
							<?php
							$locsite = $this->db->get('location_site');
							foreach ($locsite->result() as $obj) {
							?>
							<option value="<?php echo $obj->location_id; ?>"><?php echo strtoupper($obj->location_name); ?></option>
							<?php
							}
							?>			
						</select>					
					</div> 
                  </div>
				  
                  <div class="input-field col m12 center-align">
                    <button class="btn waves-effect waves-light btn-login red" type="submit" name="submit" value="login">
							Login
						</button>
					</div>
                </div>
				</form>
			</div>
		</div>
	</div>
      </div>
    </main>
	
    <footer class="page-footer" style="margin-top:0px !important;padding:0px !important;position:absolute !important;bottom :0px !important;width:100%">
      <div class="footer-copyright woowtix-bg grey row" style="padding:0px 10px 0px 10px !important;margin-bottom:0px !important;">
	  <div class="col s2 left-align">
	  <img style="width: auto;height: 22px;vertical-align:middle;" src="<?php echo base_url();?>assets/images/logohit.jpg">
	  </div>
		<div class="col s8 center-align">
			Copyright &copy; <?php echo date('Y');?>, All Rights Reserved.
		</div>
	  <div class="col s2 right-align">
		          <img  style="width: auto;height: 30px;vertical-align:middle;" src="<?php echo base_url(); ?>assets/images/mandiri-logo-trans.png">
	  </div>
      </div>
    </footer>
   <script src="<?php echo base_url('assets/js/jquery-3.4.1.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/materialize/js/materialize.js'); ?>"></script>
</body>
</html>
