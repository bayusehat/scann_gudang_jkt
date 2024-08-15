<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Item Keluar</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Item Keluar</li>
        </ol>
        <div class="row">
            <div class="col-12 col-md-12">
                <a href="{{ url('in/create/IK') }}" class="btn btn-success"><i class="fas fa-insert"></i> Tambah Document</a>
            </div>
        </div>
        <hr>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Item Keluar
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
                            <th>Kode Item</th>
                            <th>Tanggal Masuk</th>
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

    // function importData(){
    //     var formData = new FormData($("#formImport")[0]);
    //     $.ajax({
    //         url : "{{ url('item/import') }}?flag=out",
    //         method : "POST",
    //         dataType : "JSON",
    //         data : formData,
    //         contentType: false,
    //         processData: false,
    //         headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         beforeSend:function(){
    //             $("#spinLoad").show();
    //             $("#btnImport").hide();
    //             $("#btnCancel").hide();
    //         },
    //         complete:function(){
    //             $("#spinLoad").hide();
    //         },
    //         success:function(e){
    //           if(e.status == 200){
    //             notif('alert alert-success',e.message);
    //             $("#file").val('');
    //             setTimeout(function(){
    //                 $("#modalImport").modal('hide');
    //             }, 3000);
    //             table.ajax.reload(null,false);
    //           }else{
    //             notif('alert alert-danger',e.message);
    //             $("#file").val('');
    //           }
    //         }
    //     })
    // }

    var table = new DataTable('#tableIn',{
            processing: true,
            serverSide: true,
            destroy: true,
            paging: true,
            ajax: {
                url: '{{ url("out/load") }}'
            },
            columns: [
                { name: 'DT_RowIndex', data: 'DT_RowIndex', orderable: false, searchable: false },
                { name: 'document_number', data: 'document_number'},
                { name: 'document_date', data: 'document_date'},
                { name: 'user_id', data: 'user_id'},
                { name: 'status', data: 'status'},
                { name: 'action', data: 'action'},
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
    //                 'part' : 'out'
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
