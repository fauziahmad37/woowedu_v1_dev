	<link href='<?php echo base_url('assets/datatable/jquery.dataTables.min.css') ?>' rel='stylesheet' type='text/css'>
	<link href='<?php echo base_url('assets/datatable/responsive.dataTables.min.css') ?>' rel='stylesheet' type='text/css'>
	
	<script src="<?php echo base_url('assets/datatable/jquery.dataTables.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/datatable/dataTables.responsive.min.js') ?>"></script>
	
	<script src="<?php echo base_url('assets/datatable/dataTables.buttons.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/datatable/buttons.flash.min.js') ?>"></script>
	
	
	<script src="<?php echo base_url('assets/datatable/jszip.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/datatable/pdfmake.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/datatable/vfs_fonts.js') ?>"></script>
	
	<script src="<?php echo base_url('assets/datatable/buttons.html5.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/datatable/buttons.print.min.js') ?>"></script>
	

	<style>
		/*th { font-size: 12px; }
		td { font-size: 11px; }*/
		a.close_corner_top_left {
			position: absolute;
			top: -14px;
			left: -10px;
		}
		.mr-20 {
			margin-right:20px;
		}
		.dataTables_filter {
display: none;
} 
	</style>

<div class="row">
	<div class="col s12">
		<div class="card">
		<div class="card-content woowtix-bg red white-text">
		<span class="card-title"><?php echo $pageTitle; ?></span>

          <a href="<?php echo base_url('users/manageulevel'); ?>" class="btn-floating right halfway-fab waves-effect waves-light amber tooltipped" data-position="top" data-tooltip="Tambah Data"><i class="material-icons">add</i></a>
		</div>
		<div class="card-content">
			<div class="row">
	          <?php if($message = $this->session->flashdata('message')): ?>
            <div class="col s12">
              <div class="card-panel <?php echo ($message['status']) ? 'green' : 'red'; ?>">
                <span class="white-text"><?php echo $message['message']; ?></span>
              </div>
            </div>
          <?php endif; ?>
 
		</div>	
		
		<div class="card-content">	
			<table id="table_data" class="display responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th>ID</th>						
						<th>User Level</th> 						
						<th></th> 								
					</tr>
	
				</thead>
	
			</table>
		</div>

	</div>

		

	</div>
</div>

 

<script type="text/javascript">
    var table;
    $(document).ready(function() {
		 table = $('#table_data').DataTable({  
								"responsive": true,
		            "processing": true,
		            "serverSide": true,
								"pageLength": 15,
								"lengthMenu": [[15, 25, 50, -1], [15, 25, 50, "All"]],
								"lengthChange": true,
		            "ajax":{
								"url": "<?php echo base_url('users/tampil_data_ulevel') ?>",
								"dataType": "json",
								"type": "POST",
								"data":function(data){
													// Append to data
													data.sName = $('#co_val').val();
											 }								
								},
																		"sPaginationType": "full_numbers",
								"columns": [
												{ "data": "user_level_id" },
												{ "data": "user_level_name" },
				{ data: null, render: function ( data, type, row, meta) {
					return '<a href="manageulevel?id='+ data.user_level_id +'" class="btn-floating halfway-fab waves-effect waves-light tooltipped blue" data-position="top" data-tooltip="Edit Data"><i class="material-icons">edit</i></a> <a onclick="return confirm(\'Delete Data ?\')" href="deleteulevel/'+ data.user_level_id +'" class="btn-floating halfway-fab waves-effect waves-light tooltipped red" data-position="top" data-tooltip="Remove time ?"><i class="material-icons">delete</i></a>';
				     } 
				}
										 ],
								 "columnDefs": [
									 {
											 "targets": [0],
											 "orderable": false
										 },
									 {
											"targets": [0],
											"visible": false
									 }
					   ],
					   "language": {
					           "search": "Search",
					           "searchPlaceholder": "time..."
					       }
						

			    });
		
 
			$('#search_button').click(function(){
				table.draw();
			});
	$('select').material_select();
	
    $('#btn_hapus').on('click', function(){
			check_hapus('co_id','<?php echo base_url();?>setup/deletetime');
	    table.ajax.reload(null,false);
    });
		
	    $('#table_data tbody').on('click', 'tr', function () {
	      var data = table.row(this).data();
	      var id = $('#co_id').val(data.co_id);
	      if ($(this).hasClass('selected')) {
	        $(this).removeClass('selected');
	      }
	      else {
	        table.$('tr.selected').removeClass('selected');
	        $(this).addClass('selected');
	      }
	    });
});
 
</script>