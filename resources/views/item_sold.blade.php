<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Item Sold</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Item Sold</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-area me-1"></i>
                        Scan / Input Item
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Scan or write item code here ..." onkeyup="auto_add(event)" autofocus>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Item Sold
            </div>
            <div class="card-body">
                <table id="tableIn">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barcode</th>
                            <th>Artikel</th>
                            <th>Warna</th>
                            <th>Size</th>
                            <th>Harga</th>
                            <th>Scan Date</th>
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
                            <th>Scan Date</th>
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
                url: '{{ url("item/sold/load") }}'
            },
            columns: [
                { name: 'DT_RowIndex', data: 'DT_RowIndex', orderable: false, searchable: false },
                { name: 'barcode', data: 'barcode'},
                { name: 'artikel', data: 'artikel'},
                { name: 'warna', data: 'warna'},
                { name: 'size', data: 'size'},
                { name: 'harga', data: 'harga'},
                { name: 'created_at', data: 'created_at'},
                // { name: 'action', data: 'action'},
            ],
            lengthMenu: [10,50,-1],
            order: [[0, 'desc']],
        });

    let timer = null;
    function auto_add(e){
        var kode = $("#barcode").val();
        e.preventDefault();
        if(timer){
            window.clearTimeout(timer);
            timer = null;
        }

        timer = window.setTimeout( ()=>{
            $.ajax({
                method : 'POST',
                url : '{{ url("/item/add") }}/',
                data : {
                    'barcode' : kode,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(e){
                    if(e.status == 'success'){
                        $('#barcode').val('').focus();
                        table.ajax.reload(null,false);
                    }else{
                        alert(e.message);
                        $('#barcode').val('').focus();
                    }
                }
            })
        }, 500);

    }
</script>
