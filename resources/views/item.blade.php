<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Item Master</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Item Master</li>
        </ol>
        {{-- <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-area me-1"></i>
                        Scan / Input Item
                    </div>
                    <div class="form-group">
                        <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Scan or write item code here ..." onkeyup="auto_add(event)" autofocus>
                    </div>
                </div>
            </div>
        </div> --}}
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

    })
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
