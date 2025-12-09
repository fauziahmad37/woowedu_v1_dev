'use strict';
const base_url = document.querySelector('base').href,
                s_code = document.getElementById('s_code'),
                s_kelas = document.getElementById('s_kelas'),
                s_mapel = document.getElementById('s_mapel'),
                fs = document.forms['form-search'];

(async $ => {
    const table = $('#tbl_ujian').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + 'api/exam/getAll'
        },
        select: {
			style:	'multi', 
			selector: 'td:first-child'
		},
        columns: [
            {
                data: null,
                className: 'select-checkbox',
                checkboxes: {
                    selectRow: true
                },
                orderable: false,
                render(data, type, row, _meta) {
                    return '';
                }
            },
            {
                data: 'id_ujian',
                visible:false
            },
            {
                data: 'kode_ujian'
            },
            {
                data: 'category_id',
                visible: false
            },
            {
                data: 'category_name',

            },
            {
                data: 'class_id',
                visible: false
            },
            {
                data: 'class_name'
            },
            {
                data:'subject_id',
                visible: false
            },
            {
                data: 'subject_code',
                visible: false
            },
            {
                data: 'subject_name'
            },
            {
                data: 'start_time'
            },
            {
                data: 'end_time'
            },
            {
                data: 'durasi'
            },
            {
                data: 'total_soal',
                render(data, type, row, meta) {
                    return (!data) ? 0 : data;
                }
            },
            {
                data: 'id_ujian',
                render(data, type, row, meta) {
                    var view =  '<a rol="button" class="btn btn-sm btn-warning" href="'+base_url+'api/exam/question/'+data+'">' + 
                                    '<i class="fas fa-pen"></i>' + 
                                '</a>';
                    return view;
                }
            }
        ]
    });

    $('#btn-refresh').on('click', e => {
        table.ajax.reload();
    })
    
    document.getElementById('select_all').addEventListener('click', e => {

        if(e.target.checked)
            table.rows().select();
        else
            table.rows().deselect();
    });

    await getKelas();
    await getMapel();
    //await getCategories();


    $(s_kelas).selectpicker('val', null);
    $(s_mapel).selectpicker('val', null);

    
})(jQuery);

// ambil data kels
async function getKelas() {
    try 
    {
        const f = await fetch(`${base_url}api/kelas/get_all`);
        let datas = await f.json();
        
        for(let d of datas.data)
        {
            // search
            let opt1 = document.createElement('option');
            opt1.text = d.class_name;
            opt1.value = d.class_id;
            s_kelas.add(opt1);
        }
    }
    catch(err)
    {
        console.log(err);
    }
}

// ambil data mapel
async function getMapel() {
    try 
    {
        const f = await fetch(`${base_url}api/subject/getAll`);
        let datas = await f.json();
        console.log(datas);
        for(let d of datas.data)
        {
            // search
            let opt1 = document.createElement('option');
            opt1.text = d.nama_mapel;
            opt1.value = d.id_mapel;
            s_mapel.add(opt1);
        }
    }
    catch(err) 
    {
        console.log(err);
    }

}
