@extends('layouts.app')

@section('title', 'Stores')
@section('header-title', 'Stores')

@section('content')

    <!-- Action bar: Search + Buttons -->
    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 25px; flex-wrap: wrap;">
        <!-- Search -->
        <input type="text" id="search" placeholder="Search stores..." style="padding:10px 15px; border-radius:8px; border:1px solid #ccc; width: 250px; margin-bottom: 10px;">
    </div>

    <!-- Stores Table -->
    <div style="overflow-y:auto; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
        <table style="width:100%; border-collapse:collapse; background:white;">
            <thead style="background: var(--gold); color:white; text-align:left; position:sticky; top:0; z-index:2;">
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Currency</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody id="stores-body">
                @foreach ($data as $store)
                    <tr class="store-row">
                        <td style="display:flex; align-items:center;">
                            <div style="margin-left:8px; width:50px; height:50px; border-radius:10px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#fff;">
                                @if ($store->image_url)
                                    <img src="{{ $store->image_url }}" style="width:100%; height:100%; object-fit:contain;">
                                @else
                                    <div style="width:50px; height:50px; border-radius:10px; display:flex; align-items:center; justify-content:center; background:#c9a643; color:white; font-weight:bold; font-size:20px;">
                                        {{ substr($store->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>{{ $store->name }}</td>
                        <td>{{ $store->currency }}</td>
                        <td>{{ $store->address }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <!-- JS for search -->
    <script>
        const searchInput = document.getElementById('search');
        const tableBody = document.getElementById('stores-body');
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
