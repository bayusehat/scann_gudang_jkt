<style>
    .b-block {
        width: 100%;
    }
    th {
    border-top: 1px solid #dddddd;
    border-bottom: 1px solid #dddddd;
    border-right: 1px solid #dddddd;
    }
    th:first-child {
    border-left: 1px solid #dddddd;
    }

    table.dataTable td, th {
    font-size: 0.8em;
    }
    table.dataTable tbody th, table.dataTable tbody td {
    padding: 0px
    }
    table tfoot {
        display: table-row-group;
    }
    td.nowrap{
        white-space: nowrap;
    }
    @media print {
        .header-print {
            display: table-header-group;
        }
    }
</style>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Laporan Stok</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Laporan Stok SO</li>
        </ol>
        <div class="row">
            <div class="col-12 col-md-3">
                <input type="date" name="date_from" id="date_from" class="form-control">
            </div>
            <div class="col-12 col-md-3">
                <input type="date" name="date_to" id="date_to" class="form-control">
            </div>
            <div class="col-12 col-md-3">
                <select name="id_warehouse" id="id_warehouse" class="form-control">
                    <option value="">-- Pilih Gudang --</option>
                    @foreach ($gudang_list as $gl)
                        <option value="{{ $gl->id_warehouse }}">{{ $gl->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <button type="button" name="btnFilter" id="btnFilter" class="btn btn-success" onclick="loadTable()"><i class="fa fa-search"></i> Cari</button>
                <a href="javascript:void(0)" class="btn btn-primary" onclick="print()"><i class="fa fa-print"></i> Print</a>
            </div>
            <span class="text-danger">*apabila filter tidak digunakan maka data yang ditampilkan adalah data perhari ini</span>
        </div>
        <hr>
        <div class="card mb-4" id="printArea">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Report Sisa Stok
            </div>
            <div class="card-body">
                <table id="tableIn" class="display compact cell-border" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center">No</th>
                            <th rowspan="2" class="text-center">Artikel</th>
                            <th rowspan="2" class="text-center">Warna</th>
                            <th rowspan="2" class="text-center" nowrap>Brand</th>
                            <th colspan="8" class="text-center">Stok Awal</th>
                            <th colspan="8" class="text-center">Mutasi</th>
                            <th colspan="8" class="text-center">Stok Akhir</th>
                        </tr>
                        <tr>
                            <th>39</th>
                            <th>40</th>
                            <th>41</th>
                            <th>42</th>
                            <th>43</th>
                            <th>44</th>
                            <th>45</th>
                            <th>Total</th>
                            <th>39</th>
                            <th>40</th>
                            <th>41</th>
                            <th>42</th>
                            <th>43</th>
                            <th>44</th>
                            <th>45</th>
                            <th>Total</th>
                            <th>39</th>
                            <th>40</th>
                            <th>41</th>
                            <th>42</th>
                            <th>43</th>
                            <th>44</th>
                            <th>45</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align: right">Total</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align: right">Total</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align: right">Total</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</main>
<script>
    $(document).ready(function(){
        loadTable();
        $("#btnupdate").hide();
        $("#btncancel").hide();
    })

    function print(){
        let from = $("#date_from").val();
        let to = $("#date_to").val();
        let warehouse = $("#id_warehouse").val();
        let url = '{{ url("print/stok") }}?from='+from+'&to='+to+'&warehouse='+warehouse;

        window.open(url, '_blank');
    }

    function whenEdit(){
        $("#btncreate").hide();
        $("#btnupdate").show();
        $("#btncancel").show();
        $("#barcode").focus();
    }

    function whenCancel(){
        $("#btncreate").show();
        $("#btnupdate").hide();
        $("#btncancel").hide();
        $("#barcode").val(""),
        $("#artikel").val(""),
        $("#warna").val(""),
        $("#size").val(""),
        $("#harga").val("");
        $("#titlefunction").text("Insert new Item");
    }

    function importItem(){
        $("#modalImport").modal('show');
    }

    function hideImport(){
        $("#modalImport").modal('hide');
        $("#file").val('');
    }

    function importData(){
        var formData = new FormData($("#formImport")[0]);
        $.ajax({
            url : "{{ url('item/import') }}?flag=master",
            method : "POST",
            dataType : "JSON",
            data : formData,
            contentType: false,
            processData: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(e){
              if(e.status == 200){
                $("#modalImport").modal('hide');
                $("#file").val('');
                loadTable()
              }else{
                $("#file").val('');
              }
            }
        })
    }

    function loadTable(){
        var table = new DataTable('#tableIn',{
            dom: 'Blfrtip',
            columnDefs:[{targets:[3,1], className:"nowrap"}],
            orderCellsTop: true,
            buttons: [
                {
                    extend: 'print',
                    title : '',
                    footer: true,
                    // exportOptions: {
                    //     columns: ':visible',
                    //     stripHtml: false
                    // },
                    repeatingHead: {
                        title : '<center><div style="top:10; left:50;font-size:30px;font-weight:50"><h1>Laporan Stok</h1></div></center><center><div style="top:10; left:20;">'+$("#date_from").val().replace
                            ('-','/')+' - '+$("#date_to").val().replace
                            ('-','/')+'</div></center><center><div style="top:30; left:450;font-size:20px;margin-botton:50px">'+$("#id_warehouse option:selected").text()+'</div></center>'
                    },
                    customize: function(win)
                    {
                        var last = null;
                        var current = null;
                        var bod = [];
                    
                        var css = '@page { size: landscape; }',
                            head = win.document.head || win.document.getElementsByTagName('head')[0],
                            style = win.document.createElement('style');
        
                        style.type = 'text/css';
                        style.media = 'print';
        
                        if (style.styleSheet)
                        {
                        style.styleSheet.cssText = css;
                        }
                        else
                        {
                        style.appendChild(win.document.createTextNode(css));
                        }
        
                        head.appendChild(style);
                    }
                }
            ],
            footerCallback: function (row, data, start, end, display) {
                let api = this.api();
        
                // Remove the formatting to get integer data for summation
                let intVal = function (i) {
                    return typeof i === 'string'
                        ? i.replace(/[\$,]/g, '') * 1
                        : typeof i === 'number'
                        ? i
                        : 0;
                };
        
                // Total over all pages
                totalAwal = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(parseInt(a) || 0) + intVal(parseInt(b) || 0), 0);
        
                // Total over this page
                pageTotalAwal = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(parseInt(a) || 0) + intVal(parseInt(b) || 0), 0);
        
                // Update footer
                api.column(11).footer().innerHTML =
                     pageTotalAwal;

                //MUTASI
                totalMutasi = api
                    .column(19)
                    .data()
                    .reduce((a, b) => intVal(parseInt(a) || 0) + intVal(parseInt(b) || 0), 0);
        
                // Total over this page
                pageTotalMutasi = api
                    .column(19, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(parseInt(a) || 0) + intVal(parseInt(b) || 0), 0);
        
                // Update footer
                api.column(19).footer().innerHTML =
                     pageTotalMutasi;

                //Akhir
                totalAkhir = api
                    .column(27)
                    .data()
                    .reduce((a, b) => intVal(parseInt(a) || 0) + intVal(parseInt(b) || 0), 0);
        
                // Total over this page
                pageTotalAkhir = api
                    .column(27, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(parseInt(a) || 0) + intVal(parseInt(b) || 0), 0);
        
                // Update footer
                api.column(27).footer().innerHTML =
                     pageTotalAkhir;
            },
            processing: true,
            serverSide: true,
            destroy: true,
            paging: true,
            scrollX: true,
            ajax: {
                url: '{{ url("item/stok/list") }}',
                data: {
                    'date_from' : $("#date_from").val(),
                    'date_to' : $("#date_to").val(),
                    'id_warehouse' : $("#id_warehouse").val()
                }
            },
            columns: [
                { name: 'DT_RowIndex', data: 'DT_RowIndex', orderable: false, searchable: false },
                { name: 'artikel', data: 'artikel'},
                { name: 'warna', data: 'warna'},
                { name: 'brand', data: 'brand'},
                { name: 'a_39', data: 'a_39'},
                { name: 'a_40', data: 'a_40'},
                { name: 'a_41', data: 'a_41'},
                { name: 'a_42', data: 'a_42'},
                { name: 'a_43', data: 'a_43'},
                { name: 'a_44', data: 'a_44'},
                { name: 'a_45', data: 'a_45'},
                { name: 'grand_total_awal', data: 'grand_total_awal'},
                { name: 'k_39', data: 'k_39'},
                { name: 'k_40', data: 'k_40'},
                { name: 'k_41', data: 'k_41'},
                { name: 'k_42', data: 'k_42'},
                { name: 'k_43', data: 'k_43'},
                { name: 'k_44', data: 'k_44'},
                { name: 'k_45', data: 'k_45'},
                { name: 'grand_total_mutasi', data: 'grand_total_mutasi'},
                { name: 's_39', data: 's_39'},
                { name: 's_40', data: 's_40'},
                { name: 's_41', data: 's_41'},
                { name: 's_42', data: 's_42'},
                { name: 's_43', data: 's_43'},
                { name: 's_44', data: 's_44'},
                { name: 's_45', data: 's_45'},
                { name: 'grand_total_akhir', data: 'grand_total_akhir'},
            ],
            lengthMenu: [[10, 20, 25, 50, -1], [10, 20, 25, 50, 'All']],
            order: [[0, 'desc']],
        });
    }
</script>
