<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Item Masuk</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Item Masuk List</li>
        </ol>
        <div class="row">
            <div class="col-12 col-md-12">
                <a href="{{ url('in/create/IM') }}" class="btn btn-success"><i class="fas fa-insert"></i> Tambah Document</a>
            </div>
        </div>
        <hr>
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
                            <th>Nomor Dokumen</th>
                            <th>Tanggal Dokumen</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Nomor Dokumen</th>
                            <th>Tanggal Dokumen</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Action</th>
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
        $("#spinLoad").hide();
        $("#notif").hide();
    })

    function importItem(){
        $("#modalImport").modal('show');
    }

    function hideImport(){
        $("#modalImport").modal('hide');
        $("#file").val('');
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
                { name: 'document_number', data: 'document_number'},
                { name: 'document_date', data: 'document_date'},
                { name: 'user_id', data: 'user_id'},
                { name: 'status', data: 'status'},
                { name: 'action', data: 'action'}
            ],
            lengthMenu: [10,50,-1],
            order: [[0, 'desc']],
        });

    // let timer = null;
    // function auto_add(e){
    //     var kode = $("#kode_item").val();
    //     e.preventDefault();
    //     if(timer){
    //         window.clearTimeout(timer);
    //         timer = null;
    //     }

    //     timer = window.setTimeout( ()=>{
    //         $.ajax({
    //             method : 'POST',
    //             url : '{{ url("/autoadd") }}/',
    //             data : {
    //                 'kode_item' : kode,
    //                 'part' : 'in'
    //             },
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //             success:function(e){
    //                 if(e.status == 'success'){
    //                     $('#kode_item').val('').focus();
    //                     table.ajax.reload(null,false);
    //                 }else{
    //                     alert(e.message);
    //                     $('#kode_item').val('').focus();
    //                 }
    //             }
    //         })
    //     }, 500);

    // }

    function notif(type,message){
        $("#notif").show().attr('class',type);
        $("#message").text(message);
    }
</script>
