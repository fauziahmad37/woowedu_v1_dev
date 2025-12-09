(($, base_url) => {
    'use strict';
		
 
	
    var table = $('#tbl_rekapnilai').DataTable({
				dom: 'Bf<"float-right"l>rtip',
				lengthMenu: [[5, 10, -1], [5, 10, "All"]],
				bFilter: true,
				searching: false,
				processing: true,
        serverSide: true, 
				pageLength: 5,
				ajax: {
					url: base_url + 'reports/get_rekap_nilai',
					type: 'GET',
					data: function(d) {   
							return d;
					}
				},  
        columns: [   {
          data: 'student_id',  
					render(data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						}
					} , 
					{ data: 'nis'}  , 
					{ data: 'student_name'}  , 
					{ data: 'class_name'}  , 
					{ data: 'subject_name'},      
					{ data: 'nilai_tugas'} ,  
					{ data: 'nilai_ujian'}      
				],
		buttons: {
			buttons: [
				{
					extend: 'pdfHtml5',
					exportOptions: {
						columns: [0, 1, 2, 3, 4,5,6]
					},
					className: 'btn btn-danger btn-sm',
					text: 'Export to PDF &nbsp;&nbsp; <i class="bx bxs-file-pdf"></i>', 
					orientation: 'landscape',
					pageSize: 'A4'
				},
				{
					extend: 'excel',
					exportOptions: {
						columns: [0, 1, 2, 3, 4,5,6]
					},
					className: 'btn btn-success btn-sm',
					text: 'Export to Excell &nbsp;&nbsp; <i class="bx bx-file"></i>',
					orientation: 'landscape',
					pageSize: 'A4'
				}, 
				{
					className: 'btn bg-primary btn-sm',
					text: 'Refresh Table &nbsp;&nbsp; <i class="bx bx-check-double"></i>',
					action: function () { 
						table.draw();
					}
				}
			]
		}, 
		language:{
			processing:   '<div class="d-flex flex-column align-items-center shadow">'
						+	'<span class="spinner-border text-info"></span>'
						+	'<h4 class="mt-2"><strong>Loading...</strong></h4>'
						+ '</div>',
		}

    });
 
    $('#btn-refresh').on('click', e => {
        table.ajax.reload();
    });


 

 
})(jQuery, document.querySelector('base').href);