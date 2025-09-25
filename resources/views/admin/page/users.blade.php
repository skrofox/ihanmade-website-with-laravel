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
        <h1 style="margin:0; font-size:1.1rem;">
            <form action="{{ route('user_search') }}" method="get">
                <input type="text" name="keyword" id="keyword" placeholder="id, email,.." value="{{ request('keyword') }}" class="rounded-sm border border-collapse">
                <button type="submit" class="bg-slate-300 hover:bg-slate-500 px-1 py-2 rounded border border-collapse ">
                    Search
                </button>
            </form>
        </h1>
        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
            <table class="table min-w-[720px] w-full text-left text-lg">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 w-16">Number</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Create at</th>
                        <th class="px-4 py-3">Update at</th>
                        <th class="px-4 py-3 text-center">Khóa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-slate-500 hover:text-white">
                            <td scope="row" class="text-center px-4 py-3 font-medium">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3 ">
                                {{ $user->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-3 ">
                                {{ $user->updated_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('user_destroy', $user->id) }}" method="post"
                                    onsubmit="return confirm('Delete This User?');">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="inline-flex items-center gap-1 rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium hover:bg-slate-500 active:scale-[.98]">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $users->links('pagination.page_custom') }}
        </div>
    </div>
@endsection
