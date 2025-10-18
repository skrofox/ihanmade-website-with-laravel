@extends('layouts.web.app')

@section('content')
    @include('components.web.slide')
    <div class="bg-slate-200">
        @include('components.web.product_review')
    </div>
@endsection
