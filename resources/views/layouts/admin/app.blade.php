<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title', 'Admin Page')
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Anton&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/admin/app.css', 'resources/js/app.js'])
</head>

<body>
    @include('layouts.admin.header')

    <div class="app">
        <input type="checkbox" id="menu-toggle" hidden>

        <aside class="sidebar" aria-label="Thanh bên">
            <div class="brand">Skrofox Dashboard</div>

            <nav aria-label="Chính">
                <a class="nav-link {{ request()->routeIs('admin_home') ? 'active' : '' }}"
                    href="{{ route('admin_home') }}">Overview</a>

                {{-- Users có submenu, mở sẵn khi đang ở bất kỳ route user_* --}}
                <details class="menu-collapse" {{ request()->routeIs('user_*') ? 'open' : '' }}>
                    <summary
                        class="nav-link flex items-center justify-between cursor-pointer {{ request()->routeIs('user_*') ? 'active' : '' }}">
                        Users
                        {{-- Chevron --}}
                        <svg class="h-4 w-4 menu-chevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.117l3.71-3.886a.75.75 0 111.08 1.04l-4.24 4.44a.75.75 0 01-1.08 0l-4.24-4.44a.75.75 0 01.02-1.06z" />
                        </svg>
                    </summary>

                    <ul class="mt-1 ml-2 flex flex-col gap-1">
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('user_index') ? 'active-sub' : '' }}"
                                href="{{ route('user_index') }}">List User</a>
                        </li>
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('user_create') ? 'active-sub' : '' }}"
                                href="{{ route('user_create') }}">Add User</a>
                        </li>
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('user_trash') ? 'active-sub' : '' }}"
                                href="{{ route('user_trash') }}">User Trash</a>
                        </li>
                    </ul>
                </details>
                <details class="menu-collapse" {{ request()->routeIs('category_*') ? 'open' : '' }}>
                    <summary
                        class="nav-link flex items-center justify-between cursor-pointer {{ request()->routeIs('category_*') ? 'active' : '' }}">
                        Category
                        {{-- Chevron --}}
                        <svg class="h-4 w-4 menu-chevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.117l3.71-3.886a.75.75 0 111.08 1.04l-4.24 4.44a.75.75 0 01-1.08 0l-4.24-4.44a.75.75 0 01.02-1.06z" />
                        </svg>
                    </summary>

                    <ul class="mt-1 ml-2 flex flex-col gap-1">
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('category_tree') ? 'active-sub' : '' }}"
                                href="{{ route('category_tree') }}">Tree</a>
                        </li>
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('category_index') ? 'active-sub' : '' }}"
                                href="{{ route('category_index') }}">List Category</a>
                        </li>
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('category_create') ? 'active-sub' : '' }}"
                                href="{{ route('category_create') }}">Add Category</a>
                        </li>
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('category_trash') ? 'active-sub' : '' }}"
                                href="{{ route('category_trash') }}">Category Trash</a>
                        </li>
                    </ul>
                </details>
                <details class="menu-collapse" {{ request()->routeIs('product_*') ? 'open' : '' }}>
                    <summary
                        class="nav-link flex items-center justify-between cursor-pointer {{ request()->routeIs('product_*') ? 'active' : '' }}">
                        Product
                        {{-- Chevron --}}
                        <svg class="h-4 w-4 menu-chevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.117l3.71-3.886a.75.75 0 111.08 1.04l-4.24 4.44a.75.75 0 01-1.08 0l-4.24-4.44a.75.75 0 01.02-1.06z" />
                        </svg>
                    </summary>

                    <ul class="mt-1 ml-2 flex flex-col gap-1">
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('product_index') ? 'active-sub' : '' }}"
                                href="{{ route('product_index') }}">List Products</a>
                        </li>
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('product_create') ? 'active-sub' : '' }}"
                                href="{{ route('product_create') }}">Add Product</a>
                        </li>
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('product_trash') ? 'active-sub' : '' }}"
                                href="{{ route('product_trash') }}">Products Trash</a>
                        </li>
                    </ul>
                </details>
                
                <details class="menu-collapse" {{ request()->routeIs('warehouse_*') ? 'open' : '' }}>
                    <summary
                        class="nav-link flex items-center justify-between cursor-pointer {{ request()->routeIs('warehouse_*') ? 'active' : '' }}">
                        Warehouse
                        {{-- Chevron --}}
                        <svg class="h-4 w-4 menu-chevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.117l3.71-3.886a.75.75 0 111.08 1.04l-4.24 4.44a.75.75 0 01-1.08 0l-4.24-4.44a.75.75 0 01.02-1.06z" />
                        </svg>
                    </summary>

                    <ul class="mt-1 ml-2 flex flex-col gap-1">
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('warehouse_index') ? 'active-sub' : '' }}"
                                href="{{ route('warehouse_index') }}">List Warehouses</a>
                        </li>
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('warehouse_create') ? 'active-sub' : '' }}"
                                href="{{ route('warehouse_create') }}">Add Warehouse</a>
                        </li>
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('warehouse_trash') ? 'active-sub' : '' }}"
                                href="{{ route('warehouse_trash') }}">Warehouse Trash</a>
                        </li>
                    </ul>
                </details>
                
                <details class="menu-collapse" {{ request()->routeIs('variant_*') ? 'open' : '' }}>
                    <summary
                        class="nav-link flex items-center justify-between cursor-pointer {{ request()->routeIs('variant_*') ? 'active' : '' }}">
                        Products Variant
                        {{-- Chevron --}}
                        <svg class="h-4 w-4 menu-chevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.117l3.71-3.886a.75.75 0 111.08 1.04l-4.24 4.44a.75.75 0 01-1.08 0l-4.24-4.44a.75.75 0 01.02-1.06z" />
                        </svg>
                    </summary>

                    <ul class="mt-1 ml-2 flex flex-col gap-1">
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('variant_index') ? 'active-sub' : '' }}"
                                href="{{ route('variant_index') }}">List Products Variant</a>
                        </li>
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('variant_create') ? 'active-sub' : '' }}"
                                href="{{ route('variant_create') }}">Add Products Variant</a>
                        </li>
                    </ul>
                </details>

                <details class="menu-collapse" {{ request()->routeIs('stock_*') ? 'open' : '' }}>
                    <summary
                        class="nav-link flex items-center justify-between cursor-pointer {{ request()->routeIs('stock_*') ? 'active' : '' }}">
                        Stock Items
                        {{-- Chevron --}}
                        <svg class="h-4 w-4 menu-chevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.117l3.71-3.886a.75.75 0 111.08 1.04l-4.24 4.44a.75.75 0 01-1.08 0l-4.24-4.44a.75.75 0 01.02-1.06z" />
                        </svg>
                    </summary>

                    <ul class="mt-1 ml-2 flex flex-col gap-1">
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('stock_index') ? 'active-sub' : '' }}"
                                href="{{ route('stock_index') }}">List Stock Items</a>
                        </li>
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('stock_create') ? 'active-sub' : '' }}"
                                href="{{ route('stock_create') }}">Add Stock Item</a>
                        </li>
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('stock_trash') ? 'active-sub' : '' }}"
                                href="{{ route('stock_trash') }}">Stock Items Trash</a>
                        </li>
                    </ul>
                </details>

                <details class="menu-collapse" {{ request()->routeIs('order_*') ? 'open' : '' }}>
                    <summary
                        class="nav-link flex items-center justify-between cursor-pointer {{ request()->routeIs('order_*') ? 'active' : '' }}">
                        Orders
                        {{-- Chevron --}}
                        <svg class="h-4 w-4 menu-chevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.117l3.71-3.886a.75.75 0 111.08 1.04l-4.24 4.44a.75.75 0 01-1.08 0l-4.24-4.44a.75.75 0 01.02-1.06z" />
                        </svg>
                    </summary>

                    <ul class="mt-1 ml-2 flex flex-col gap-1">
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('order_index') ? 'active-sub' : '' }}"
                                href="{{ route('order_index') }}">List Orders</a>
                        </li>
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('order_pending') ? 'active-sub' : '' }}"
                                href="{{ route('order_pending') }}">Pending Orders</a>
                        </li>
                        <li>
                            <a class="nav-sublink {{ request()->routeIs('order_processing') ? 'active-sub' : '' }}"
                                href="{{ route('order_processing') }}">Processing Orders</a>
                        </li>
                    </ul>
                </details>

                <a class="nav-link" href="#">Setting</a>
            </nav>

        </aside>

        <div class="wrap">
            <header class="topbar">
                <label for="menu-toggle" class="menu-btn" aria-label="Mở menu">Menu</label>
                <h1 style="margin:0; font-size:1.1rem;">
                    @yield('header', 'Trang Admin')
                </h1>
            </header>

            <div class="__admin-panel-content">
                @yield('content')
            </div>
        </div>

        <label for="menu-toggle" class="backdrop" aria-hidden="true"></label>
    </div>
    @include('layouts.admin.footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>

</html>