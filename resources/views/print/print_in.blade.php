<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <script src="{{asset('js/jquery-3.5.1.js')}}"></script>
    <script src="{{asset('js/fontawesome.min.js')}}"></script>

    <script src="{{asset('js/bootstrap.bundle.min.js')}}" crossorigin="anonymous"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('assets/demo/chart-bar-demo.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fixedHeader.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.bootstrap.min.css') }}">
    <script src="{{ asset('js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/responsive.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.buttons.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('css/buttons.dataTables.min.css') }}">
    <script src="{{ asset('js/jszip.min.js')}}"></script>
    <script src="{{ asset('js/pdfmake.min.js')}}"></script>
    <script src="{{ asset('js/vfs_fonts.js')}}"></script>
    <script src="{{ asset('js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('js/buttons.print.js')}}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
    th {
    border-top: 1px solid #dddddd;
    border-bottom: 1px solid #dddddd;
    border-right: 1px solid #dddddd;
    }
    th:first-child {
    border-left: 1px solid #dddddd;
    }

    table.dataTable td, th {
    font-size: 0.8em;
    }
    table.dataTable tbody th, table.dataTable tbody td {
    padding: 0px
    }
    table tfoot {
        display: table-row-group;
    }
    td.nowrap{
        white-space: nowrap;
    }
    @media print {
     @page {
        margin-left: 0.3in;
        margin-right: 0.3in;
        margin-top: 0;
        margin-bottom: 1;
      }
    }
    .title{
        border: 0 none !important;
    }
    </style>
    
</head>
<body>
    <div class="paper">
        <table id="tableIn" class="table table-striped table-bordered table-sm" style="width:100%;padding-top:20px">
            <thead>
                <tr>
                    <th colspan="28" class="title"><center><div style="top:30;left:50;font-size:30px;font-weight:50"><h1>Laporan Stok Masuk</h1></div></center>
                        <center><div style="top:10; left:20; font-size:20px">{{ $data['main']->document_number ?? "" }} / {{ $data['main']->document_date ?? "" }}</div></center><center><div style="top:30; left:450;font-size:15px;margin-botton:50px">Gudang : {{ $data['main']->warehouse->name ?? "" }}</div></center></th>
                </tr>
                <tr>
                    <th rowspan="2" class="text-center">No</th>
                    <th rowspan="2" class="text-center">Artikel</th>
                    <th rowspan="2" class="text-center">Warna</th>
                    <th rowspan="2" class="text-center" nowrap>Brand</th>
                    <th colspan="8" class="text-center">Masuk</th>
                </tr>
                <tr>
                    <th>39</th>
                    <th>40</th>
                    <th>41</th>
                    <th>42</th>
                    <th>43</th>
                    <th>44</th>
                    <th>45</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_awal = 0;
                @endphp
                @foreach ($data['data'] as $i => $item)
                    @php
                        $total_awal += $item->grand_total_awal;
                    @endphp
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $item->artikel }}</td>
                        <td>{{ $item->warna }}</td>
                        <td>{{ $item->brand }}</td>
                        <td>{{ $item->a_39 > 0 ? $item->a_39 : '.' }}</td>
                        <td>{{ $item->a_40 > 0 ? $item->a_40 : '.'}}</td>
                        <td>{{ $item->a_41 > 0 ? $item->a_41 : '.'}}</td>
                        <td>{{ $item->a_42 > 0 ? $item->a_42 : '.'}}</td>
                        <td>{{ $item->a_43 > 0 ? $item->a_43 : '.'}}</td>
                        <td>{{ $item->a_44 > 0 ? $item->a_44 : '.'}}</td>
                        <td>{{ $item->a_45 > 0 ? $item->a_45 : '.'}}</td>
                        <td>{{ $item->grand_total_awal > 0 ? $item->grand_total_awal : '.'}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="11" style="text-align: right">Total</th>
                    <th>{{ $total_awal }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <script>
        $(document).ready(function(){
            var css = '@page { size: landscape; }',
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');

            style.type = 'text/css';
            style.media = 'print';

            if (style.styleSheet){
            style.styleSheet.cssText = css;
            } else {
            style.appendChild(document.createTextNode(css));
            }

            head.appendChild(style);

            window.print();
        })
    </script>
</body>
</html>