@extends('layouts.admin.app')

{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script> --}}
@section('header', 'Page User')
@section('title', 'User Dashboard')
@section('content')
    <div class="space-y-4">
        <div class="max-w-3xl mx-auto p-6">
            {{-- <h1 class="text-2xl font-bold mb-4">Danh mục</h1> --}}
            <div class="rounded-lg border bg-white p-4">
                <x-category-tree :nodes="$tree" />
            </div>
        </div>
        <div class="mt-3">
            {{ $tree->links('pagination.page_custom') }}
        </div>
    </div>
@endsection
