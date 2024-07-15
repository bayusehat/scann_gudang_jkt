<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Item Masuk</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Item Masuk</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-area me-1"></i>
                        Scan / Input Item
                    </div>
                    <div class="form-group">
                        <input type="text" name="kode_item" id="kode_item" class="form-control" placeholder="Scan or write item code here ..." onkeyup="auto_add(event)" autofocus>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-default b-block" name="btnimport" id="btnimport" onclick="importItem()"><i class="fas fa-file"></i> Import Data From Excel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Item Masuk
            </div>
            <div class="card-body">
                <table id="tableIn">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Item</th>
                            <th>Tanggal Masuk</th>
                            <th>User</th>
                            {{-- <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th> --}}
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Kode Item</th>
                            <th>Tanggal Masuk</th>
                            <th>User</th>
                            {{-- <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th> --}}
                        </tr>
                    </tfoot>
                    <tbody>

                </table>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="modalImport">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Import Data Item Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formImport" enctype="multipart/form-data">
                     <div class="modal-body">
                        <input type="file" name="file" id="file" class="form-control">
                    </div>
                </form>
                <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                <button type="button" class="btn btn-success" onclick="importData()">Import Data</button>
                </div>
            </div>
        </div>
      </div>
</main>
<script>
    $(document).ready(function(){

    })

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
            url : "{{ url('item/import') }}?flag=in",
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
                table.ajax.reload(null,false);
              }else{
                $("#file").val('');
              }
            }
        })
    }

    var table = new DataTable('#tableIn',{
            processing: true,
            serverSide: true,
            destroy: true,
            paging: true,
            ajax: {
                url: '{{ url("in/load") }}'
            },
            columns: [
                { name: 'DT_RowIndex', data: 'DT_RowIndex', orderable: false, searchable: false },
                { name: 'kode_item', data: 'kode_item'},
                { name: 'created_at', data: 'created_at'},
                { name: 'user_in', data: 'user_in'},
            ],
            lengthMenu: [10,50,-1],
            order: [[0, 'desc']],
        });

        // $("#kode_item").one('keyup',function(){
        //     var kode = $("#kode_item").val();
        //     $.ajax({
        //         method : 'POST',
        //         url : '{{ url("/autoadd") }}/',
        //         data : {
        //             'kode_item' : kode
        //         },
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success:function(e){
        //             if(e.status == 'success'){
        //                 $('#kode_item').val('').focus();
        //                 table.ajax.reload(null,false);
        //             }else{
        //                 alert(e.messge);
        //                 $('#kode_item').val('').focus();
        //             }
        //         }
        //     })
        // })
    let timer = null;
    function auto_add(e){
        var kode = $("#kode_item").val();
        e.preventDefault();
        if(timer){
            window.clearTimeout(timer);
            timer = null;
        }

        timer = window.setTimeout( ()=>{
            $.ajax({
                method : 'POST',
                url : '{{ url("/autoadd") }}/',
                data : {
                    'kode_item' : kode,
                    'part' : 'in'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(e){
                    if(e.status == 'success'){
                        $('#kode_item').val('').focus();
                        table.ajax.reload(null,false);
                    }else{
                        alert(e.message);
                        $('#kode_item').val('').focus();
                    }
                }
            })
        }, 500);

    }
</script>
