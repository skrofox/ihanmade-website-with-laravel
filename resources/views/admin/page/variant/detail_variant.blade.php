@extends('layouts.admin.app')
@section('header', 'Create Variant')
@section('title', 'Create Variant')
@section('content')
    Detail Variant
    @php
        echo "<p>Detail variant product:" . $variant->id . "</p>";
    @endphp
@endsection