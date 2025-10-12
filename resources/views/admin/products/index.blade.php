@extends('layouts.app')

@section('title', 'Products')
@section('header-title', 'Products')

@section('content')

    <!-- Action bar: Search + Buttons -->
    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 25px; flex-wrap: wrap;">
        <!-- Search -->
        <input type="text" id="search" placeholder="Search products..."
            style="padding:10px 15px; border-radius:8px; border:1px solid #ccc; width: 250px; margin-bottom: 10px;">

        <!-- Buttons -->
        <div>
            <button
                style="background-color: var(--gold); color:white; padding:10px 18px; border:none; border-radius:6px; margin-right:10px; cursor:pointer;">
                Import
            </button>
            <button
                style="background-color: var(--dark); color:white; padding:10px 18px; border:none; border-radius:6px; cursor:pointer;">
                Create
            </button>
        </div>
    </div>

    <!-- Products Table -->
    <div style="height:80%; overflow-y:auto; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
        <table style="width:100%; border-collapse: collapse; background:white;">
            <thead style="background: var(--gold); color:white; text-align:left; position:sticky; top:0; z-index:2;">
                <tr>
                    <th style="padding:12px;">Product Name</th>
                    <th style="padding:12px;">Cost</th>
                    <th style="padding:12px;">Price</th>
                    <th style="padding:12px;">Category</th>
                    <th style="padding:12px;">In Stock</th>
                    <th style="padding:12px;">Threshold</th>
                    <th style="padding:12px; text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody id="products-body">
                <!-- Example rows with color identification -->
                <tr style="background:#f9f9f9;">
                    <td style="padding:12px;">Rice</td>
                    <td style="padding:12px;">$8.00</td>
                    <td style="padding:12px;">$12.00</td>
                    <td style="padding:12px;">Food</td>
                    <td style="padding:12px;">100</td>
                    <td style="padding:12px;">20</td>
                    <td style="padding:12px; text-align:center;">
                        <button style="background:#4CAF50; color:white; padding:6px 12px; border:none; border-radius:5px; cursor:pointer; margin-right:5px;">Edit</button>
                        <button style="background:#F44336; color:white; padding:6px 12px; border:none; border-radius:5px; cursor:pointer;">Delete</button>
                    </td>
                </tr>
                <tr style="background:#fff;">
                    <td style="padding:12px;">Coffee</td>
                    <td style="padding:12px;">$5.00</td>
                    <td style="padding:12px;">$8.00</td>
                    <td style="padding:12px;">Beverage</td>
                    <td style="padding:12px;">50</td>
                    <td style="padding:12px;">10</td>
                    <td style="padding:12px; text-align:center;">
                        <button style="background:#4CAF50; color:white; padding:6px 12px; border:none; border-radius:5px; cursor:pointer; margin-right:5px;">Edit</button>
                        <button style="background:#F44336; color:white; padding:6px 12px; border:none; border-radius:5px; cursor:pointer;">Delete</button>
                    </td>
                </tr>
                <tr style="background:#f9f9f9;">
                    <td style="padding:12px;">Sugar</td>
                    <td style="padding:12px;">$2.00</td>
                    <td style="padding:12px;">$3.50</td>
                    <td style="padding:12px;">Food</td>
                    <td style="padding:12px;">200</td>
                    <td style="padding:12px;">50</td>
                    <td style="padding:12px; text-align:center;">
                        <button style="background:#4CAF50; color:white; padding:6px 12px; border:none; border-radius:5px; cursor:pointer; margin-right:5px;">Edit</button>
                        <button style="background:#F44336; color:white; padding:6px 12px; border:none; border-radius:5px; cursor:pointer;">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination (static style) -->
    <div style="margin-top:20px; display:flex; justify-content:flex-end; gap:10px;">
        <button style="padding:6px 12px; border:none; border-radius:5px; background:#f0f0f0; cursor:pointer;">«</button>
        <button style="padding:6px 12px; border:none; border-radius:5px; background:#f0f0f0; cursor:pointer;">1</button>
        <button style="padding:6px 12px; border:none; border-radius:5px; background:#f0f0f0; cursor:pointer;">2</button>
        <button style="padding:6px 12px; border:none; border-radius:5px; background:#f0f0f0; cursor:pointer;">3</button>
        <button style="padding:6px 12px; border:none; border-radius:5px; background:#f0f0f0; cursor:pointer;">»</button>
    </div>

    <!-- JS for search -->
    <script>
            const searchInput = document.getElementById('search');
            const tableBody = document.getElementById('products-body');
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
                    noRow.innerHTML = `<td colspan="7" style="text-align:center; padding:12px; color:#888;">No product found</td>`;
                    tableBody.appendChild(noRow);
                }
            });
        </script>

@endsection
