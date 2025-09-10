@extends('layouts.admin.app')
@section('header', 'Add Category')
@section('title', 'Add Category')
@section('content')
    <div>
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif
        <div class="w-full pt-4">
            <div class="w-max justify-self-center items-center ">
                <form action="{{ route('category_update', $category->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="mb-4 flex flex-col gap-5">
                        <div>
                            <label for="">Parent category</label>
                            <select name="parent_id" id="parent_id"
                                class="w-96 bg-transparent placeholder:text-black text-black text-[16px] border border-slate-200 rounded pl-3 pr-8 py-2 transition duration-300 ease focus:outline-none focus:border-slate-200 hover:border-slate-200 shadow-sm focus:shadow-md appearance-none cursor-pointer">
                                <option value="{{ $category->parent?->name }}" class="bg-slate-200 hover:bg-slate-300">
                                    {{ $category->parent?->name }}</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}" class="bg-slate-200 hover:bg-slate-400">
                                        {{ $item->name }} ({{ $item->id }})</option>
                                @endforeach
                            </select>
                            <p class="text-sm">if there is no parent category, leave it blank</p>
                        </div>
                        <div>
                            <label for="" class="text-blue-800">Name Category</label>
                            <label for="text-sm text-red-500">
                                @error('name')
                                    {{ $message }}
                                @enderror
                            </label>
                            <input type="text" name="name" id="" placeholder="name.."
                                value="{{ $category->name }}" class="w-full" required>
                        </div>
                    </div>
                    <div class="">
                        <a href="{{ route('category_index') }}" class="hover:underline">Back</a>
                        <button type="submit"
                            class="rounded-lg border border-collapse px-1 ps-3 pe-3 bg-slate-200 hover:bg-slate-400 float-right">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
