(($, base_url) => {
    'use strict';
 
    var table = $('#tbl_teacher').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + 'settings/get_all_log',
            type: 'GET',
            data: function(d) {   
                return d;
            }
        }, select: {
			style:	'multi', 
			selector: 'td:first-child'
		},
        columns: [   {
            data: 'id', 
                visible: false 
        }  , {
            data: 'user'
        }  , {
            data: 'ipadd'
        }  , {
            data: 'logtime'
        }  , {
            data: 'logdetail'
        }    ],
				pageLength: 8,
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