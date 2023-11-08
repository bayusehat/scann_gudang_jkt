<style>
    .b-block {
        width: 100%;
    }
</style>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Item Master</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Item Master</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-area me-1"></i>
                        <span id="titlefunction">Insert new Item</span>
                    </div>
                    <div class="card-body">
                        <form class="form-inline">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Code Item / Barcode</label>
                                        <input type="text" class="form-control" name="barcode" id="barcode" placeholder="Barcode Item">
                                        <span class="text-danger vld" id="valid_barcode"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Artikel</label>
                                        <input type="text" class="form-control" name="artikel" id="artikel" placeholder="Artikel Name">
                                        <span class="text-danger vld" id="valid_artikel"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Warna</label>
                                        <select name="warna" id="warna" class="form-control">
                                            <option value="">Pilih Warna</option>
                                            @foreach ($warna as $w)
                                                <option value="{{$w}}">{{$w}}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger vld" id="valid_warna"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Size</label>
                                        <select name="size" id="size" class="form-control">
                                            <option value="">Pilih Size</option>
                                            @foreach ($size as $s)
                                                <option value="{{$s}}">{{$s}}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger vld" id="valid_size"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Harga</label>
                                        <input type="text" class="form-control" name="harga" id="harga" placeholder="Harga">
                                        <span class="text-danger vld" id="valid_harga"></span>
                                    </div>
                                    <br>
                                    <button type="button" class="btn btn-primary b-block" name="btncreate" id="btncreate" onclick="createItem()"><i class="fas fa-save"></i> Create</button>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-success b-block" name="btnuodate" id="btnupdate" onclick="updateItem()"><i class="fas fa-edit"></i> Update</button>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-danger b-block" name="btncancel" id="btncancel" onclick="whenCancel()"><i class="fas fa-times"></i> Cancel</button>
                                        </div>
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
                            <th>Barcode</th>
                            <th>Artikel</th>
                            <th>Warna</th>
                            <th>Size</th>
                            <th>Harga</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Barcode</th>
                            <th>Artikel</th>
                            <th>Warna</th>
                            <th>Size</th>
                            <th>Harga</th>
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

    function createItem(){
        $.ajax({
            url : "{{ url('item/insert') }}",
            method : "POST",
            dataType : "JSON",
            data : {
                'barcode' : $("#barcode").val(),
                'artikel' : $("#artikel").val(),
                'warna' : $("#warna").val(),
                'size' : $("#size").val(),
                'harga' : $("#harga").val()
            },
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(e){
                if(e.status == 200){
                    $("#barcode").val("").focus(),
                    $("#artikel").val(""),
                    $("#warna").val(""),
                    $("#size").val(""),
                    $("#harga").val("")
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

    function editItem(id){
        whenEdit();
        $.ajax({
            url : "{{ url('item/edit') }}/"+id,
            method : "GET",
            dataType : "JSON",
            success:function(e){
                if(e.status == 200){
                    $("#titlefunction").text("Edit Item "+e.data.artikel);
                    $("#barcode").val(e.data.barcode)
                    $("#artikel").val(e.data.artikel)
                    $("#warna").val(e.data.warna).trigger('change')
                    $("#size").val(e.data.size).trigger('change')
                    $("#harga").val(e.data.harga)
                    $("#btnupdate").attr('onclick',`updateItem(${e.data.id_item})`);
                }else{
                    alert(e.message);
                }
            }
        })
    }

    function updateItem(id){
        $.ajax({
            url : "{{ url('item/update') }}/"+id,
            method : "POST",
            dataType : "JSON",
            data : {
                'barcode' : $("#barcode").val(),
                'artikel' : $("#artikel").val(),
                'warna' : $("#warna").val(),
                'size' : $("#size").val(),
                'harga' : $("#harga").val()
            },
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(e){
                if(e.status == 200){
                    $("#barcode").val(""),
                    $("#artikel").val(""),
                    $("#warna").val(""),
                    $("#size").val(""),
                    $("#harga").val("")
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

    function deleteItem(id){
        $.ajax({
            url : "{{ url('item/delete') }}/"+id,
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
                url: '{{ url("item/load") }}'
            },
            columns: [
                { name: 'DT_RowIndex', data: 'DT_RowIndex', orderable: false, searchable: false },
                { name: 'barcode', data: 'barcode'},
                { name: 'artikel', data: 'artikel'},
                { name: 'warna', data: 'warna'},
                { name: 'size', data: 'size'},
                { name: 'harga', data: 'harga'},
                { name: 'action', data: 'action'},
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
</script>
