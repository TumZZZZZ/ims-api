<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('storage/default-images/favicon.png') }}">
    <title>Khmer Angkor | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body style="font-family: 'Poppins', Arial;">
    @php
        $user = auth()->user();
        if (!$user) {
            header('Location: ' . route('login'));
            exit();
        }
        $role       = $user->role;
        $superAdmin = $role === 'SUPER_ADMIN';
        $admin      = $role === 'ADMIN';
        $manager    = $role === 'MANAGER';
        $roles      = [
            "SUPER_ADMIN" => "Super Admin",
            "ADMIN"       => "Admin",
            "MANAGER"     => "Manager",
        ];
    @endphp
    <aside class="sidebar">
        <div>
            <div class="sidebar-header">Khmer Angkor</div>
            <div class="menu">
                {{-- Menu Super Admin --}}
                @if ($superAdmin)
                    <a href="{{ route('super-admin.dashboard') }}" class="{{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">@lang('dashboard')</a>
                    <a href="{{ route('super-admin.merchants') }}" class="{{ request()->routeIs('super-admin.merchants') ? 'active' : '' }}">Merchants</a>
                    <a href="{{ route('super-admin.branches') }}" class="{{ request()->routeIs('super-admin.branches') ? 'active' : '' }}">Branches</a>
                    <a href="{{ route('super-admin.users') }}" class="{{ request()->routeIs('super-admin.users') ? 'active' : '' }}">Users</a>

                {{-- Menu Super Admin --}}
                @elseif ($admin)
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                    @php
                        $isActiveCategoriesMenu = request()->routeIs('admin.category.list') || request()->routeIs('admin.category.create');
                    @endphp
                    <a href="{{ route('admin.category.list') }}" class="{{ $isActiveCategoriesMenu ? 'active' : '' }}">Categories</a>
                    <a href="{{ route('admin.product.list') }}" class="{{ request()->routeIs('admin.product.list') ? 'active' : '' }}">Products</a>

                {{-- Menu Super Admin --}}
                @elseif ($manager)
                    <a href="{{ route('manager.dashboard') }}" class="{{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">Dashboard</a>
                @endif
            </div>
        </div>
        <div class="user-profile">
            <img src="{{ $user->image->url ?? url('storage/default-images/no-image.png') }}" alt="User">
            <h4>{{ $user['first_name']." ".$user['last_name'] }}</h4>
            <p>{{ $roles[$role] }}</p>
        </div>
    </aside>

    <main class="main">
        <div class="main-header">
            <h1>@yield('header-title')</h1>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>

        <div class="content">
            @yield('content')
        </div>

        {{-- <footer>© 2025 Khmer Angkor. All rights reserved.</footer> --}}
    </main>
</body>

</html>
