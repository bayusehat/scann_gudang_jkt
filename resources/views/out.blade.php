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
                    <div class="form-group">
                        <input type="text" name="kode_item" id="kode_item" class="form-control" placeholder="Scan or write item code here ..." onkeyup="auto_add(event)" autofocus>
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
                url: '{{ url("out/load") }}'
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
                        alert(e.messge);
                        $('#kode_item').val('').focus();
                    }
                }
            })
        }, 500);

    }
</script>
