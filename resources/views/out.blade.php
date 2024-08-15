<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Item Keluar</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Item Keluar</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-area me-1"></i>
                        Scan / Input Item
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Nomor Dokumen</label>
                                    <input type="text" class="form-control-plaintext" name="document_number" id="document_number" value="{{$data->document_number}}">
                                    <input type="hidden" name="counter" id="counter" value="{{$data->counter}}">
                                    <input type="hidden" name="id" id="id" value="{{$data->id_document}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Tipe</label>
                                    <input type="text" class="form-control-plaintext" name="document_type" id="document_type" value="{{$data->document_type}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Scan Input</label>
                                    <div class="form-group">
                                        <input type="text" name="kode_item" id="kode_item" class="form-control" placeholder="Scan or write item code here ..." onkeyup="auto_add(event)" autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Gudang</label>
                                    <div class="form-group">
                                        <select name="gudang" id="gudang" class="form-control">
                                            <option value="">-- Pilih Gudang --</option>
                                            @foreach ($gudang_list as $gl)
                                                <option value="{{ $gl->id_warehouse }}" @if ($data->id_warehouse == $gl->id_warehouse)
                                                    {{'selected'}}
                                                @else
                                                    {{''}}
                                                @endif>{{ $gl->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-primary b-block" name="btnimport" id="btnimport" onclick="importItem()"><i class="fas fa-file"></i> Import Data From Excel</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-danger float-end" name="btnCancel" id="btnCancel" onclick="deleteCreate()"><i class="fas fa-times"></i> Batal / Hapus Dokumen</button>
                            </div>
                        </div>
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
                            <th>Tanggal Keluar</th>
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
                <h5 class="modal-title">Import Data Item Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formImport" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div role="alert" id="notif">
                            <span id="message"></span>
                        </div>
                        <div class="form-group">
                            <input type="file" name="file" id="file" class="form-control">
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="btnCancel"><i class="fas fa-times"></i></button>
                    <button type="button" class="btn btn-success" onclick="importData()" id="btnImport">Import Data</button> 
                    <div class="spinner-border text-primary" role="status" id="spinLoad">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
      </div>
</main>
<script>
    $(document).ready(function(){
        $("#spinLoad").hide();
        $("#notif").hide();
        $("#gudang").select2();
        $("#gudang").on('change',function(){
            var id = "{{ $data->id_document }}";
            var id_warehouse = $(this).val();
            console.log(id_warehouse);

            $.ajax({
                url : '{{ url("warehouse/in") }}/'+id+'/'+id_warehouse,
                method : 'GET',
                success:function(res){
                    if(res.status == 200){
                        alert(res.message);
                    }else{
                        alert(res.message);
                    }
                }
            })
        })
    })

    function importItem(){
        $("#modalImport").modal('show');
    }

    function hideImport(){
        $("#modalImport").modal('hide');
        $("#file").val('');
    }

    function insertDocument(){
        $.ajax({
            url : "{{ url('document/insert') }}",
            method : "POST",
            dataType : "JSON",
            data : {
                "document_number" : $("#document_number").val(),
                "document_type" : $("#document_type").val(),
                "counter" : $("#counter").val(),
                "id_document" : $("#id").val()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(res){
                $("#id").val(res.data.id_document);
            }
        })
    }

    function deleteCreate(){
        let id = $("#id").val();
        $.ajax({
            url : "{{ url('/in/create/delete') }}/"+id,
            method : 'GET',
            success:function(res){
              if(res.status == 200){
                window.location = '{{ url("") }}/'+res.url;
              }else{
                alert(res.message);
              }              
            }
        })
    }

    function importData(){
        var formData = new FormData($("#formImport")[0]);
        $.ajax({
            url : "{{ url('item/import') }}?flag=out",
            method : "POST",
            dataType : "JSON",
            data : formData,
            contentType: false,
            processData: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend:function(){
                $("#spinLoad").show();
                $("#btnImport").hide();
                $("#btnCancel").hide();
            },
            complete:function(){
                $("#spinLoad").hide();
            },
            success:function(e){
              if(e.status == 200){
                notif('alert alert-success',e.message);
                $("#file").val('');
                setTimeout(function(){
                    $("#modalImport").modal('hide');
                }, 3000);
                table.ajax.reload(null,false);
              }else{
                notif('alert alert-danger',e.message);
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
                url: '{{ url("out/load/detail") }}',
                data : function(d){
                    d.id_document = $("#id").val()
                }
            },
            columns: [
                { name: 'DT_RowIndex', data: 'DT_RowIndex', orderable: false, searchable: false },
                { name: 'kode_item', data: 'kode_item'},
                { name: 'created_at', data: 'created_at'},
                { name: 'user_out', data: 'user_out'},
            ],
            lengthMenu: [10,50,-1],
            order: [[0, 'desc']],
        });

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
                    'part' : 'out'
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

    function notif(type,message){
        $("#notif").show().attr('class',type);
        $("#message").text(message);
    }
</script>
