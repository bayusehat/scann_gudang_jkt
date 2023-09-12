@include('layout.head')
@if ($data['content'])
    {{ view($data['content']) }}
@else
    {{ view('notfound') }}
@endif
@include('layout.foot');
