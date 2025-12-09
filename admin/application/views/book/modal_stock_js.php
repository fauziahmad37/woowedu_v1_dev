<script>
    const formStock = document.forms['save-stock'];
    const btnAddStock = document.getElementById('btn-add-stock'),
          tableAddStock = document.getElementById('table-add-stock');
    const stockTbody = tableAddStock.tBodies[0];

    (async $ => {
        const books = [...await getBooks()];

        // select
        var $select = $('#save-stock select[name="book"]').selectize({
            create: false,
            valueField: 'id',
            labelField: 'title',
            options: books,
            searchField: ['title']
        });

        var sel = $select[0].selectize;

        // modal stock event
        setBookValue(sel, formStock['book']); 
        <?php if($is_readonly): ?>
                sel.lock();
        <?php endif ?>


        $('#modal_stock').on('show.bs.modal', e => {

            setBookValue(sel, formStock['book']); 
          
            <?php if($is_readonly): ?>
                sel.lock();
            <?php endif ?>
        });

        // Tambah row di tabel add-stock
        btnAddStock.addEventListener('click', e => {
            addStockField();
        });

        formStock.addEventListener('submit', e => {
            loading();
        })
    })(jQuery);


    const addStockField = () => {
        let panjang = stockTbody.rows.length;
        const row = stockTbody.insertRow();

        const cell_0 = row.insertCell(0);
        const cell_1 = row.insertCell(1);
        const cell_2 = row.insertCell(2);

        cell_0.innerHTML = panjang + 1;
        // cell 1
        cell_1.innerHTML = `<input type="text" class="form-control" name="stock_codes[${panjang}]">`;
        // cell 2
        cell_2.innerHTML = '<button type="button" class="btn btn-circle btn-danger" onclick="deleteStockField(event)"><i class="fas fa-trash"></i></button>';
    }

    const deleteStockField = e => {
        const el = e.target.parentNode.closest('tr');
        el.remove();
    }

    const setBookValue = (selec, el) => {
        if(el.getAttribute('value'))
            selec.setValue(el.getAttribute('value'));
    }
</script>