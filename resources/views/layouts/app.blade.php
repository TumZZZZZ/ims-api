<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('storage/default-images/favicon.png') }}">
    <title>Khmer Angkor | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dropdown.css') }}">
    <link rel="stylesheet" href="{{ asset('css/selection.css') }}">
    @stack('styles')
</head>

<body style="font-family: 'Khmer OS Siemreap Regular', Arial;">
    @php
        $user = auth()->user();
        if (!$user) {
            header('Location: ' . route('login'));
            exit();
        }
        $role       = $user->role;
        $superAdmin = $role === App\Enum\Constants::ROLE_SUPER_ADMIN;
        $admin      = $role === App\Enum\Constants::ROLE_ADMIN;
        $manager    = $role === App\Enum\Constants::ROLE_MANAGER;

        $menus = [
            'superAdmin' => [
                [
                    'label' => __('dashboard'),
                    'route' => 'super-admin.dashboard',
                ],
                [
                    'label' => __('merchants'),
                    'route' => 'super-admin.merchants.index',
                    'active' => [
                        'super-admin.merchants.index',
                        'super-admin.merchant.create',
                        'super-admin.merchant.edit',
                    ],
                ],
                [
                    'label' => __('branches'),
                    'route' => 'super-admin.branches.index',
                ],
                [
                    'label' => __('users'),
                    'route' => 'super-admin.users.index',
                ],
                [
                    'label' => __('activity_logs'),
                    'route' => 'super-admin.activity-logs.index',
                ],
            ],

            'admin' => [
                [
                    'label' => __('dashboard'),
                    'route' => 'admin.dashboard',
                ],
                [
                    'label' => __('branches'),
                    'route' => 'admin.branches.index',
                    'active' => [
                        'admin.branches.index',
                        'admin.branch.create',
                        'admin.branch.edit',
                    ],
                ],
                [
                    'label' => __('users'),
                    'route' => 'admin.users.index',
                    'active' => [
                        'admin.users.index',
                        'admin.user.create',
                        'admin.user.edit',
                    ],
                ],
                [
                    'type'  => 'group',
                    'label' => __('menu'),
                    'items' => [
                        [
                            'label' => __('categories'),
                            'route' => 'admin.categories.index',
                            'active' => [
                                'admin.categories.index',
                                'admin.category.create',
                                'admin.category.edit',
                            ],
                        ],
                        [
                            'label' => __('products'),
                            'route' => 'admin.products.index',
                            'active' => [
                                'admin.products.index',
                                'admin.product.create',
                                'admin.product.edit',
                            ],
                        ],
                        [
                            'label' => __('promotions'),
                            'route' => 'admin.promotions.index',
                            'active' => [
                                'admin.promotions.index',
                                'admin.promotion.create',
                                'admin.promotion.edit',
                            ],
                        ],
                    ],
                ],
                [
                    'type'  => 'group',
                    'label' => __('inventory'),
                    'items' => [
                        [
                            'label' => __('suppliers'),
                            'route' => 'inventory.suppliers.index',
                            'active' => [
                                'inventory.suppliers.index',
                                'inventory.supplier.create',
                                'inventory.supplier.edit',
                            ],
                        ],
                        [
                            'label' => __('purchase_orders'),
                            'route' => 'inventory.purchase-orders.index',
                        ],
                        [
                            'label' => __('ledgers'),
                            'route' => 'inventory.ledgers.index',
                        ],
                    ],
                ],
            ],

            'manager' => [
                [
                    'label' => __('dashboard'),
                    'route' => 'manager.dashboard',
                ],
                [
                    'label' => __('suppliers'),
                    'route' => 'inventory.suppliers.index',
                    'active' => [
                        'inventory.suppliers.index',
                        'inventory.supplier.create',
                        'inventory.supplier.edit',
                    ],
                ],
                [
                    'label' => __('purchase_orders'),
                    'route' => 'inventory.purchase-orders.index',
                ],
                [
                    'label' => __('ledgers'),
                    'route' => 'inventory.ledgers.index',
                ],
            ],
        ];

        $roleKey    = $superAdmin ? 'superAdmin' : ($admin ? 'admin' : 'manager');
        $roleMenus  = $menus[$roleKey] ?? [];
    @endphp
    <aside class="sidebar">
        <div>
            <div class="sidebar-header">Khmer Angkor</div>
            <div class="menu">
                @foreach ($roleMenus as $menu)

                    {{-- GROUP MENU --}}
                    @if (($menu['type'] ?? null) === 'group')

                        @php
                            $groupActive = collect($menu['items'])
                                ->pluck('active')
                                ->flatten()
                                ->contains(fn ($route) => request()->routeIs($route));
                        @endphp

                        <div class="menu-group {{ $groupActive ? 'open' : '' }}">
                            <button type="button" class="menu-parent {{ $groupActive ? 'active' : '' }}">
                                <span>{{ $menu['label'] }}</span>
                                <svg class="arrow" width="25" height="25" viewBox="0 0 24 24">
                                    <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </button>

                            <div class="menu-children">
                                @foreach ($menu['items'] as $item)
                                    <a href="{{ route($item['route']) }}"
                                    class="{{ isActive($item['active'] ?? $item['route']) ? 'active' : '' }}">
                                        {{ $item['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                    {{-- NORMAL MENU --}}
                    @else
                        <a href="{{ route($menu['route']) }}"
                        class="{{ isActive($menu['active'] ?? $menu['route']) ? 'active' : '' }}">
                            {{ $menu['label'] }}
                        </a>
                    @endif

                @endforeach
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
                @if (!$superAdmin)
                    <button class="dropdown-toggle" id="dropdownBranchToggle">
                        {{ @$user->getActiveBranch()->name }}
                    </button>
                    @if (@$user->getBranches()->count() >= 2)
                        @php
                            $branches = @$user->getBranches();
                            $activeBranch = @$user->getActiveBranch();

                            // Move active branch to top
                            if ($activeBranch) {
                                $branches = $branches
                                    ->sortByDesc(function ($branch) use ($activeBranch) {
                                        return $branch->id === $activeBranch->id ? 1 : 0;
                                    })
                                    ->values();
                            }
                        @endphp
                        <ul class="dropdown-menu" id="dropdownBranchMenu">
                            @foreach ($branches as $branch)
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
    <script>
        // JS for menu group
        document.addEventListener('DOMContentLoaded', function () {

            // Auto-open menu groups that have active children
            document.querySelectorAll('.menu-group').forEach(group => {
                if (group.querySelector('.menu-children .active')) {
                    group.classList.add('open');
                }
            });

            // Toggle on click (event delegation)
            document.addEventListener('click', function (e) {
                const parentBtn = e.target.closest('.menu-parent');
                if (!parentBtn) return;

                const group = parentBtn.closest('.menu-group');
                if (!group) return;

                group.classList.toggle('open');
            });
        });
    </script>
</body>

</html>
