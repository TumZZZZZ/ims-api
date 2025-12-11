@extends('layouts.app')

@section('title', 'Categories')
@section('header-title', 'Categories')

@section('content')

    <div class="action-bar">
        {{-- Keep search during pagination --}}
        <input type="text"
               id="search"
               placeholder="{{ __('search') }}"
               value="{{ request('search') }}">
        {{-- Buttons --}}
        <div>
            <button class="btn" style="background: #4CAF50;"
            onclick="window.location.href='/admin/categories/create'">
                + @lang('create')
            </button>
        </div>
    </div>

    {{-- Categories Table --}}
    <div class="table-wrapper">
        <table class="activity-table">
            <thead class="table-header">
                <tr>
                    <th></th>
                    <th>@lang('name')</th>
                    <th>@lang('products')</th>
                    <th>@lang('actions')</th>
                </tr>
            </thead>

            <tbody id="table-body">
                @forelse ($data as $category)
                    <tr>
                        <td class="avatar-cell">
                            <div class="avatar-wrapper">
                                @if (@$category->image->url)
                                    <div class="avatar-image-wrapper">
                                        <img src="{{ $category->image->url }}" class="avatar-image">
                                    </div>
                                @else
                                    <div class="avatar-initials">
                                        {{ initials($category->name) }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->getProducts()->isNotEmpty() ? $category->getProducts()->count() : '-' }}</td>
                        <td class="text-center">
                            @if ($category->getProducts()->isEmpty())
                                <button class="btn"
                                    onclick="openDialog('{{ $category->id }}', '{{ $category->name }}', '{{ __('delete') }}')"
                                    style="background: #F44336;">{{ __('delete') }}
                                </button>
                            @endif
                            <button class="btn"
                                {{-- onclick="window.location.href='/super-admin/merchants/update/{{ $category->id }}'" --}}
                                style="background: #666666;">{{ __('edit') }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">{{ __('record_not_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination-wrapper">
        @if ($data->isNotEmpty())
            <div>
                Showing {{ $data->firstItem() }}–{{ $data->lastItem() }} of {{ $data->total() }}
            </div>
        @endif
        {{ $data->links() }}
    </div>

    <!-- ===== Modal ===== -->
    @include('modal')

    @push('scripts')
        <script src="{{ asset('js/search.js') }}"></script>
        <script>
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

            // Show toast from sessionStorage (after page reload)
            document.addEventListener('DOMContentLoaded', function () {
                const deleteMerchantMessage = sessionStorage.getItem('delete_category_message');
                if (deleteMerchantMessage) {
                    const toast = document.getElementById("toast");
                    toast.innerHTML = deleteMerchantMessage;
                    toast.style.display = "block";

                    sessionStorage.removeItem('delete_category_message');

                    setTimeout(() => {
                        toast.style.display = "none";
                    }, 5000);
                }
            });

            // Modal
            let currentCategoryId = '';
            let currentCategoryName = '';
            let currentAction = '';

            function openDialog(categoryId, categoryName, action) {
                currentCategoryId = categoryId;
                currentCategoryName = categoryName;
                currentAction = action;

                // Get the localized string with placeholders from Blade
                let template = @json(__('confirm_category_action_delete', ['action' => ':action', 'categoryName' => ':categoryName']));

                // Replace placeholders with actual values in JS
                let confirmationMessage = template
                    .replace(':action', action.toLowerCase())
                    .replace(':categoryName', categoryName);

                document.getElementById("modal-message").innerHTML = confirmationMessage;
                document.getElementById("modal").style.display = "flex";
            }

            function closeDialog() {
                document.getElementById("modal").style.display = "none";
            }

            function confirmAction() {

                let action = currentAction.toUpperCase();

                // Handle delete action separately
                if (['DELETE'].includes(action)) {
                    const url = `/admin/categories/delete/${currentCategoryId}?category_name=${encodeURIComponent(currentCategoryName)}`;

                    fetch(url, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json()) // <-- parse JSON
                    .then(data => {
                        if (data.success) {
                            closeDialog();

                            // Store message in sessionStorage for reload
                            sessionStorage.setItem('delete_category_message', data.message);

                            // Reload page
                            window.location.reload();
                        } else {
                            alert('Failed to delete category.');
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });
                    return;
                }
            }
        </script>

    @endpush

@endsection
