<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Khmer Angkor | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
    @php
        $user = session('user');
        $role = $user ? $user['role'] : 'GUEST';
        $superAdmin = $role === 'SUPER_ADMIN';
        $admin = $role === 'ADMIN';
        $manager = $role === 'MANAGER';
    @endphp
    <aside class="sidebar">
        <div>
            <div class="sidebar-header">Khmer Angkor</div>
            <div class="menu">
                {{-- Menu Super Admin --}}
                @if ($superAdmin)
                    <a href="{{ route('super-admin.dashboard') }}" class="{{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">Dashboard</a>

                {{-- Menu Super Admin --}}
                @elseif ($admin)
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>

                {{-- Menu Super Admin --}}
                @elseif ($manager)
                    <a href="{{ route('mamager.dashboard') }}" class="{{ request()->routeIs('mamager.dashboard') ? 'active' : '' }}">Dashboard</a>
                @endif
            </div>
        </div>
        <div class="user-profile">
            <img src="https://i.pravatar.cc/100?img=8" alt="User">
            <h4>{{ Auth::user()->name ?? 'Sok Dara' }}</h4>
            <p>Administrator</p>
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
