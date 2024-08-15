<style>
    .b-block {
        width: 100%;
    }
</style>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Gudang Master</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Gudang Master</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-area me-1"></i>
                        <span id="titlefunction">Insert new Gudang</span>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label for="name">Nama Gudang<span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Isi nama gudang ...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mx-sm-3 mb-2">
                                        <label for="name">Kode Gudang<span class="text-danger">*</span></label>
                                        <input type="text" name="code" id="code" class="form-control" placeholder="Isis kode gudang ...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name">Alamat Gudang<span class="text-danger">*</span></label>
                                        <input type="text" name="address" id="address" class="form-control" placeholder="isi alamat gudang ...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <br>
                                    <button type="button" class="btn btn-success" id="btncreate" onclick="createWarehouse()"><i class="fa fa-save"></i> Simpan</button>
                                    <div id="btnGroupEdit">
                                        <button type="button" class="btn btn-primary" id="btnupdate" onclick="updateWarehouse"><i class="fa fa-edit"></i> Update</button>
                                        <button type="button" class="btn btn-danger" id="btncancel" onclick="whenCancel()"><i class="fa fa-times"></i> Cancel</button>
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Item
            </div>
            <div class="card-body">
                <table id="tableIn" class="table table-responsive compact">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Gudang</th>
                            <th>Kode</th>
                            <th>Alamat</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Nama Gudang</th>
                            <th>Kode</th>
                            <th>Alamat</th>
                            <th>Action</th>
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
                <h5 class="modal-title">Import Data Item Master</h5>
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
        $("#btnupdate").hide();
        $("#btncancel").hide();
        $("#spinLoad").hide();
        $("#notif").hide();
    })

    function whenEdit(){
        $("#btncreate").hide();
        $("#btnupdate").show();
        $("#btncancel").show();
        $("#name").focus();
    }

    function whenCancel(){
        $("#btncreate").show();
        $("#btnupdate").hide();
        $("#btncancel").hide();
        $("#name").val("")
        $("#code").val("")
        $("#address").val("")
        $("#titlefunction").text("Insert new Gudang");
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

    function createWarehouse(){
        $.ajax({
            url : "{{ url('warehouse/insert') }}",
            method : "POST",
            dataType : "JSON",
            data : {
                'name' : $("#name").val(),
                'code' : $("#code").val(),
                'address' : $("#address").val(),
            },
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(e){
                if(e.status == 200){
                    $("#name").val("").focus()
                    $("#code").val("")
                    $("#address").val("")
                    $(".vld").text("");
                    table.ajax.reload(null,false);
                }else if(e.status == 400){
                    $.each(e.errors,function(i,a){
                        $("#valid_"+i).text(a)
                    })
                    $("#btnRefresh").show();
                }else{
                    alert(e.message);
                }
            }
        })
    }

    function editWarehouse(id){
        whenEdit();
        $.ajax({
            url : "{{ url('warehouse/edit') }}/"+id,
            method : "GET",
            dataType : "JSON",
            success:function(e){
                if(e.status == 200){
                    $("#titlefunction").text("Edit Item "+e.data.name);
                    $("#name").val(e.data.name)
                    $("#code").val(e.data.code)
                    $("#address").val(e.data.address)
                    $("#btnupdate").attr('onclick',`updateWarehouse(${e.data.id_warehouse})`);
                }else{
                    alert(e.message);
                }
            }
        })
    }

    function updateWarehouse(id){
        $.ajax({
            url : "{{ url('warehouse/update') }}/"+id,
            method : "POST",
            dataType : "JSON",
            data : {
                'name' : $("#name").val(),
                'code' : $("#code").val(),
                'address' : $("#address").val(),
            },
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(e){
                if(e.status == 200){
                    $("#name").val("").focus()
                    $("#code").val("")
                    $("#address").val("")
                    whenCancel();
                    table.ajax.reload(null,false);
                }else if(e.status == 400){
                    $.each(e.errors,function(i,a){
                        $("#valid_"+i).text(a)
                    })
                    $("#btnRefresh").show();
                }else{
                    alert(e.message);
                }
            }
        })
    }

    function deleteWarehouse(id){
        $.ajax({
            url : "{{ url('warehouse/delete') }}/"+id,
            method : "GET",
            dataType: "JSON",
            success:function(e){
                if(e.status == 200){
                    table.ajax.reload(null,false);
                }else{
                    alert(e.message);
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
                url: '{{ url("warehouse/load") }}'
            },
            columns: [
                { name: 'DT_RowIndex', data: 'DT_RowIndex', orderable: false, searchable: false },
                { name: 'name', data: 'name'},
                { name: 'code', data: 'code'},
                { name: 'address', data: 'address'},
                { name: 'action', data: 'action'},
            ],
            lengthMenu: [10,50,-1],
            order: [[0, 'desc']],
        });

    function notif(type,message){
        $("#notif").show().attr('class',type);
        $("#message").text(message);
    }
</script>
