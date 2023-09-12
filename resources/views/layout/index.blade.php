@include('layout.head')
@if ($data['content'])
    {{ view($data['content'],$data) }}
@else
    {{ view('notfound') }}
@endif
@include('layout.foot');
