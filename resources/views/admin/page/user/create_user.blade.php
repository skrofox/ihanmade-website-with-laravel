@extends('layouts.admin.app')
@section('header', 'Add User')
@section('title', 'Add User')
@section('content')
    <div class="max-w-xl mx-auto bg-white rounded-xl border border-slate-200 p-6">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('user_store') }}" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="user@example.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Mật khẩu</label>
                <input type="password" id="password" name="password" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Minimum password of 8 characters">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-white
                       font-medium hover:bg-blue-700 active:scale-[.98]">
                    Add
                </button>

                <a href="{{ route('user_index') }}" class="text-slate-600 hover:text-slate-800">Back</a>
            </div>
        </form>
    </div>
@endsection
