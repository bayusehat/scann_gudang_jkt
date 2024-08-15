<style>
    .b-block {
        width: 100%;
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
            </div>
            <span class="text-danger">*apabila filter tidak digunakan maka data yang ditampilkan adalah data perhari ini</span>
        </div>
        <hr>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Report Sisa Stok
            </div>
            <div class="card-body">
                <table id="tableIn" class="table table-responsive compact">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Artikel</th>
                            <th>Warna</th>
                            <th>Brand</th>
                            <th>39</th>
                            <th>40</th>
                            <th>41</th>
                            <th>42</th>
                            <th>43</th>
                            <th>44</th>
                            <th>45</th>
                            <th>Grand Total</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Artikel</th>
                            <th>Warna</th>
                            <th>Brand</th>
                            <th>39</th>
                            <th>40</th>
                            <th>41</th>
                            <th>42</th>
                            <th>43</th>
                            <th>44</th>
                            <th>45</th>
                            <th>Grand Total</th>
                        </tr>
                    </tfoot>
                    <tbody>

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
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print',
            ],
            processing: true,
            serverSide: true,
            destroy: true,
            paging: true,
            
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
                { name: 's_39', data: 's_39'},
                { name: 's_40', data: 's_40'},
                { name: 's_41', data: 's_41'},
                { name: 's_42', data: 's_42'},
                { name: 's_43', data: 's_43'},
                { name: 's_44', data: 's_44'},
                { name: 's_45', data: 's_45'},
                { name: 'grand_total', data: 'grand_total'},
            ],
            lengthMenu: [10,50,-1],
            order: [[0, 'desc']],
        });
    }
</script>
