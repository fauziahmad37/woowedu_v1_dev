<div class="vertical-menu">
	<div data-simplebar class="h-100">
		<!--- Sidemenu -->
		<div id="sidebar-menu">
			<!-- Left Menu Start -->
			<ul class="metismenu list-unstyled" id="side-menu">
				<li class="menu-title">Menu</li>
					<?=$this->acl_menu->menu($_SESSION['user_level'])?> 
 					
			</ul>
		</div>
		<!-- Sidebar -->
	</div>
</div>