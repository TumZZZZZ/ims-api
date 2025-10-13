@extends('layouts.app')

@section('title', 'Users')
@section('header-title', 'Users')

@section('content')

    <!-- Action bar: Search + Buttons -->
    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 25px; flex-wrap: wrap;">
        <!-- Search -->
        <input type="text" id="search" placeholder="Search users..."
            style="padding:10px 15px; border-radius:8px; border:1px solid #ccc; width: 250px; margin-bottom: 10px;">
    </div>

    <!-- Stores Table -->
    <div style="overflow-y:auto; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
        <table style="width:100%; border-collapse:collapse; background:white;">
            <thead style="background: var(--gold); color:white; text-align:left; position:sticky; top:0; z-index:2;">
                <tr>
                    <th>Image</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Store</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody id="users-body">
                @foreach ($data as $user)
                    <tr class="store-row">
                        <td style="display:flex; align-items:center;">
                            <div
                                style="margin-left:8px; width:50px; height:50px; border-radius:10px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#fff;">
                                @if ($user->image_url)
                                    <img src="{{ $user->image_url }}" style="width:100%; height:100%; object-fit:contain;">
                                @else
                                    <div
                                        style="width:50px; height:50px; border-radius:10px; display:flex; align-items:center; justify-content:center; background:#c9a643; color:white; font-weight:bold; font-size:20px;">
                                        {{ substr($user->full_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->store }}</td>
                        <td>{{ $user->role }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <!-- JS for search -->
    <script>
        const searchInput = document.getElementById('search');
        const tableBody = document.getElementById('users-body');
        const tableRows = tableBody.querySelectorAll('tr');

        searchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            let visibleCount = 0;

            tableRows.forEach(row => {
                const productName = row.cells[0].textContent.toLowerCase();
                if (productName.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Remove existing "no product found" row
            const existingNoRow = document.getElementById('no-product-row');
            if (existingNoRow) existingNoRow.remove();

            // If no rows visible, add "No product found" row
            if (visibleCount === 0) {
                const noRow = document.createElement('tr');
                noRow.id = 'no-product-row';
                noRow.innerHTML =
                    `<td colspan="7" style="text-align:center; padding:12px; color:#888;">No store found</td>`;
                tableBody.appendChild(noRow);
            }
        });
    </script>

@endsection
