<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('storage/default-images/favicon.png') }}">
    <title>Khmer Angkor | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Kantumruy+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dropdown.css') }}">
    <link rel="stylesheet" href="{{ asset('css/selection.css') }}">
    @stack('styles')
</head>

<body style="font-family: '{{ getFontFamilyByLocale(app()->getLocale()) }}', Arial;">
    @php
        $user = auth()->user();
        if (!$user) {
            header('Location: ' . route('login'));
            exit();
        }
        $role = $user->role;
        $superAdmin = $role === App\Enum\Constants::ROLE_SUPER_ADMIN;
        $admin = $role === App\Enum\Constants::ROLE_ADMIN;
        $manager = $role === App\Enum\Constants::ROLE_MANAGER;
    @endphp
    <aside class="sidebar">
        <div>
            <div class="sidebar-header">Khmer Angkor</div>
            <div class="menu">
                {{-- Menu Super Admin --}}
                @if ($superAdmin)
                    @php
                        $activeMerchantMenu =
                            request()->routeIs('super-admin.merchants') ||
                            request()->routeIs('super-admin.merchants.create.form') ||
                            request()->routeIs('super-admin.merchants.update.form');
                    @endphp
                    <a href="{{ route('super-admin.dashboard') }}"
                        class="{{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">@lang('dashboard')</a>
                    <a href="{{ route('super-admin.merchants') }}"
                        class="{{ $activeMerchantMenu ? 'active' : '' }}">@lang('merchants')</a>
                    <a href="{{ route('super-admin.branches') }}"
                        class="{{ request()->routeIs('super-admin.branches') ? 'active' : '' }}">@lang('branches')</a>
                    <a href="{{ route('super-admin.users') }}"
                        class="{{ request()->routeIs('super-admin.users') ? 'active' : '' }}">@lang('users')</a>
                    <a href="{{ route('super-admin.activity-logs') }}"
                        class="{{ request()->routeIs('super-admin.activity-logs') ? 'active' : '' }}">@lang('activity_logs')</a>

                    {{-- Menu Super Admin --}}
                @elseif ($admin)
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">@lang('dashboard')</a>
                    @php
                        $isActiveCategoriesMenu =
                            request()->routeIs('admin.categories') || request()->routeIs('admin.category.create');
                    @endphp
                    <a href="{{ route('admin.categories') }}"
                        class="{{ $isActiveCategoriesMenu ? 'active' : '' }}">Categories</a>
                    <a href="{{ route('admin.product.list') }}"
                        class="{{ request()->routeIs('admin.product.list') ? 'active' : '' }}">Products</a>

                    {{-- Menu Super Admin --}}
                @elseif ($manager)
                    <a href="{{ route('manager.dashboard') }}"
                        class="{{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">Dashboard</a>
                @endif
            </div>
        </div>
        <div class="user-profile">
            <img src="{{ $user->image->url ?? url('storage/default-images/no-image.png') }}" alt="User">
            <h4>{{ $user['first_name'] . ' ' . $user['last_name'] }}</h4>
            <p>{{ App\Enum\Constants::ROLES[$role] }}</p>
        </div>
    </aside>

    <main class="main">
        <div class="main-header">
            <h1>@yield('header-title')</h1>
            <div class="dropdown-wrapper">
                <button class="dropdown-toggle" id="dropdownBranchToggle">
                    {{ $user->getActiveBranch()->name }}
                </button>
                @if ($user->getBranches()->count() >= 2)
                    <ul class="dropdown-menu" id="dropdownBranchMenu">
                        @foreach ($user->getBranches() as $branch)
                            <li data-value="branch-{{ $branch->_id }}">
                                <form id="branch-{{ $branch->_id }}" action="{{ route('select.branch.post', ['user_id' => $user->_id, 'branch_id' => $branch->_id]) }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <a onclick="event.preventDefault(); document.getElementById('branch-{{ $branch->_id }}').submit();">
                                    {{ $branch->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <div class="circle-img" id="dropdownLanguageToggle">
                    <img src="{{ asset('storage/default-images/flags/' . app()->getLocale() . '.png') }}" alt="language">
                </div>
                <ul class="dropdown-menu" id="dropdownLanguageMenu">
                    <li data-value="English" onclick="window.location='{{ route('language.switch','en') }}'">
                        <img src="{{ asset('storage/default-images/flags/en.png') }}" alt="" style="width: 30px; height: 20px;">
                        {{ "English" }}
                    </li>
                    <li data-value="Khmer" onclick="window.location='{{ route('language.switch','km') }}'">
                        <img src="{{ asset('storage/default-images/flags/km.png') }}" alt="" style="width: 30px; height: 20px;">
                        {{ "ភាសាខ្មែរ" }}
                    </li>
                </ul>
                <div class="circle-img" id="dropdownSettingToggle">
                    <img src="{{ $user->image->url ?? url('storage/default-images/no-image.png') }}" alt="User">
                </div>
                <ul class="dropdown-menu" id="dropdownSettingMenu">
                    @if (!$superAdmin)
                        <li data-value="Profile">{{ __('profile') }}</li>
                    @endif
                    <li data-value="Logout">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('logout') }}
                        </a>
                    </li>
                </ul>
            </div>
            </div>
        </div>

        <div class="content">
            @yield('content')
        </div>

        {{-- <footer>© 2025 Khmer Angkor. All rights reserved.</footer> --}}
    </main>
    <script src="{{ asset('js/dropdown.js') }}"></script>
    @stack('scripts')
</body>

</html>
