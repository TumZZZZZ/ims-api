@extends('layouts.app')

@section('title', __('purchase_orders'))
@section('header-title', __('purchase_orders'))

@push('styles')
    <style>
        /* Container */
        .cycle-tab-container {
            margin: 0 auto;
            font-size: 16px;
        }

        /* Tabs wrapper */
        .cycle-tabs {
            display: flex;
            gap: 20px;
            padding: 0;
            margin: 0;
            list-style: none;
            border-bottom: 1px solid #ddd;
        }

        /* Tab item */
        .cycle-tab-item {
            width: 180px;
            text-align: center;
            position: relative;
        }

        /* Tab link */
        .cycle-tab-item a {
            display: block;
            padding: 12px 0;
            text-decoration: none;
            color: #555;
            font-weight: 500;
            transition: color 0.2s ease;
            text-align: center;
        }

        /* Hover / focus */
        .cycle-tab-item a:hover {
            color: var(--gold);
        }

        /* Active underline animation */
        .cycle-tab-item::after {
            content: "";
            display: block;
            height: 3px;
            background-color: var(--gold);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.25s ease;
        }

        /* Active tab */
        .cycle-tab-item.active a {
            color: var(--gold);
        }

        .cycle-tab-item.active::after {
            transform: scaleX(1);
        }

        /* Fade animation (if needed for content) */
        .fade {
            opacity: 0;
            transition: opacity 0.4s ease-in-out;
        }

        .fade.active {
            opacity: 1;
        }

    </style>
@endpush

@php
    $closedTab      = 'closed';
    $draftTab       = 'draft';
    $sentTab        = 'sent';
    $rejectedTab    = 'rejected';
@endphp

@section('content')

    <div class="cycle-tab-container">
        <ul class="cycle-tabs">
            <li class="cycle-tab-item {{ $activeTab == $closedTab ? 'active' : '' }}">
                <a href="{{ route('inventory.purchase-orders.closed') }}">@lang('closed')</a>
            </li>
            <li class="cycle-tab-item {{ $activeTab == $draftTab ? 'active' : '' }}">
                <a href="{{ route('inventory.purchase-orders.draft') }}">@lang('draft')</a>
            </li>
            <li class="cycle-tab-item {{ $activeTab == $sentTab ? 'active' : '' }}">
                <a href="{{ route('inventory.purchase-orders.sent') }}">@lang('sent')</a>
            </li>
            <li class="cycle-tab-item {{ $activeTab == $rejectedTab ? 'active' : '' }}">
                <a href="{{ route('inventory.purchase-orders.rejected') }}">@lang('rejected')</a>
            </li>
        </ul>
    </div>

    <div style="padding-top: 35px;"></div>

    <div class="action-bar">
        {{-- Keep search during pagination --}}
        <input type="text"
               id="search"
               placeholder="{{ __('search') }}"
               value="{{ request('search') }}">
        {{-- Buttons --}}
        <div>
            @if ($activeTab == 'closed')
                <button class="btn" style="background: #4CAF50;"
                    onclick="window.location.href='/inventory/purchase-orders/create'"
                > + @lang('create')
                </button>
            @endif
        </div>
    </div>

    <div class="table-wrapper">
        <table class="activity-table">
            <thead class="table-header">
                <tr>
                    @if ($activeTab == $closedTab)
                        <th>@lang('order_no')</th>
                        <th>@lang('branch')</th>
                        <th>@lang('supplier')</th>
                        <th>@lang('requested_date')</th>
                        <th>@lang('received_date')</th>
                        <th>@lang('status')</th>
                        <th>@lang('total_cost')</th>
                    @elseif ($activeTab == $draftTab)
                        <th>@lang('order_no')</th>
                        <th>@lang('branch')</th>
                        <th>@lang('supplier')</th>
                        <th>@lang('status')</th>
                        <th>@lang('total_cost')</th>
                        <th>@lang('actions')</th>
                    @elseif ($activeTab == $sentTab)
                        <th>@lang('order_no')</th>
                        <th>@lang('branch')</th>
                        <th>@lang('supplier')</th>
                        <th>@lang('requested_date')</th>
                        <th>@lang('status')</th>
                        <th>@lang('total_cost')</th>
                    @elseif ($activeTab == $rejectedTab)
                        <th>@lang('order_no')</th>
                        <th>@lang('branch')</th>
                        <th>@lang('supplier')</th>
                        <th>@lang('rejected_date')</th>
                        <th>@lang('status')</th>
                        <th>@lang('total_cost')</th>
                        <th>@lang('reason')</th>
                    @endif
                </tr>
            </thead>

            <tbody id="table-body">
                @forelse($data as $po)
                    <tr>
                        @if ($activeTab == $closedTab)
                            <td>{{ $po->order_number }}</td>
                            <td>{{ $po->branch->name }}</td>
                            <td>{{ $po->supplier->name }}</td>
                            <td>{{ $po->requested_date }}</td>
                            <td>{{ $po->received_date }}</td>
                            <td>{{ $po->status }}</td>
                            <td>{{ $po->total_cost }}</td>
                        @elseif ($activeTab == $draftTab)
                            <td>{{ $po->order_number }}</td>
                            <td>{{ $po->branch->name }}</td>
                            <td>{{ $po->supplier->name }}</td>
                            <td>{{ $po->status }}</td>
                            <td>{{ $po->total_cost }}</td>
                            <td></td>
                        @elseif ($activeTab == $sentTab)
                            <td>{{ $po->order_number }}</td>
                            <td>{{ $po->branch->name }}</td>
                            <td>{{ $po->supplier->name }}</td>
                            <td>{{ $po->requested_date }}</td>
                            <td>{{ $po->status }}</td>
                            <td>{{ $po->total_cost }}</td>
                        @elseif ($activeTab == $rejectedTab)
                            <td>{{ $po->order_number }}</td>
                            <td>{{ $po->branch->name }}</td>
                            <td>{{ $po->supplier->name }}</td>
                            <td>{{ $po->rejected_date }}</td>
                            <td>{{ $po->status }}</td>
                            <td>{{ $po->total_cost }}</td>
                            <td>{{ $po->reason }}</td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">{{ __('record_not_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    {{-- Pagination --}}
    @include('layouts.pagination')

    <!-- ===== Modal ===== -->
    @include('modal')

    @push('scripts')
        <script src="{{ asset('js/search.js') }}"></script>
        <script src="{{ asset('js/actions.js') }}"></script>
        <script>
            // Pass the translated template to JS
            window.confirmationTemplate = @json(__('confirmation_action', [
                'action' => ':action',
                'objectName' => ':object_name'
            ]));

            // Check if Laravel has a success message
            @if(session('success_message'))
                const toast = document.getElementById("toast");
                toast.innerHTML = @json(session('success_message')); // safely pass the message
                toast.style.display = "block";

                // Optional: hide toast after 5 seconds
                setTimeout(() => {
                    toast.style.display = "none";
                }, 5000);
            @endif

            // Tab-Pane change function
            const tabs = document.querySelectorAll('.cycle-tab-item');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                });
            });
        </script>
    @endpush

@endsection
