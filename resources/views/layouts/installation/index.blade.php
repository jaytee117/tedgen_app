@extends('theme.default')
@section('content')
<div class="container-fluid px-4">
    @include('components.installationlist',['installations' => $installations])
</div>
@endsection