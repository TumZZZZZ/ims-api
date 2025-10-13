@extends('layouts.app')

@section('title', 'Categories')
@section('header-title', 'Categories')

@section('content')

    <!-- Action bar: Search + Buttons -->
    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 25px; flex-wrap: wrap;">
        <!-- Search -->
        <input type="text" id="search" placeholder="Search categories..."
            style="padding:10px 15px; border-radius:8px; border:1px solid #ccc; width: 250px; margin-bottom: 10px;">

        <!-- Buttons -->
        <div>
            <button
                style="background-color: var(--dark); color:white; padding:10px 18px; border:none; border-radius:6px; cursor:pointer;"
                onclick="window.location.href='/admin/category-create'">
                Create
            </button>
        </div>
    </div>

    <!-- Products Table -->
    <div style="overflow-y:auto; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
        <table style="width:100%; border-collapse:collapse; background:white;">
            <thead style="background: var(--gold); color:white; text-align:left; position:sticky; top:0; z-index:2;">
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Parent</th>
                    <th>Products</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody id="categories-body">

                <!-- Example with image -->
                <tr class="category-row" data-category="food">
                    <td style="display:flex; align-items:center;">
                        <span class="arrow"></span>
                        <div
                            style="margin-left:8px; width:50px; height:50px; border-radius:10px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#fff;">
                            <img src="https://tb-static.uber.com/prod/image-proc/processed_images/948d51d7fdb3eafddba9ebe2ee26cfd0/fb86662148be855d931b37d6c1e5fcbe.jpeg"
                                alt="Food" style="width:100%; height:100%; object-fit:contain;">
                        </div>
                    </td>
                    <td>Food</td>
                    <td>-</td>
                    <td>1</td>
                    <td style="padding:12px; text-align:center;">
                        <button
                            style="background:#4CAF50; color:white; padding:6px 12px; border:none; border-radius:5px; cursor:pointer; margin-right:5px;"
                            onclick="window.location.href='/admin/category-create'">
                            Edit
                        </button>
                        <button
                            style="background:#F44336; color:white; padding:6px 12px; border:none; border-radius:5px; cursor:pointer;">Delete</button>
                    </td>
                </tr>
                <!-- Food Products -->
                <tr class="product-row food" style="display:none;">
                    <td colspan="10">
                        <table style="border-collapse:collapse; margin-left:70px;">
                            <tbody>
                                <tr>
                                    <td style="padding:12px;">
                                        <div
                                            style="width:50px; height:50px; border-radius:10px; display:flex; align-items:center; justify-content:center; background:#c9a643; color:white; font-weight:bold; font-size:20px;">
                                            7
                                        </div>
                                    </td>
                                    <td style="padding:12px;">7 Up</td>
                                    <td style="padding:12px;">2345659849</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <!-- Example without image (shows first letter) -->
                <tr class="category-row" data-category="drink">
                    <td style="display:flex; align-items:center;">
                        <span class="arrow"></span>
                        <div
                            style="margin-left:8px; width:50px; height:50px; border-radius:10px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#fff;">
                            <div
                                style="width:50px; height:50px; border-radius:10px; display:flex; align-items:center; justify-content:center; background:#c9a643; color:white; font-weight:bold; font-size:20px;">
                                D
                            </div>
                        </div>
                    </td>
                    <td>Drinks</td>
                    <td>-</td>
                    <td>0</td>
                    <td style="text-align:center;">
                        <button
                            style="background:#4CAF50; color:white; padding:6px 12px; border:none; border-radius:5px; cursor:pointer; margin-right:5px;">Edit</button>
                        <button
                            style="background:#F44336; color:white; padding:6px 12px; border:none; border-radius:5px; cursor:pointer;">Delete</button>
                    </td>
                </tr>
                <!-- Drinks Products -->
                <tr class="product-row drink" style="display:none;">
                    <td colspan="4" style="padding:0;">
                        <table style="border-collapse:collapse; margin-left:70px;">
                            <tbody>
                                <tr>
                                    <td style="padding:12px;">
                                        <div
                                            style="width:50px; height:50px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:20px;">

                                        </div>
                                    </td>
                                    <td colspan="2" style="text-align: center;">No product found</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <style>
        #categories-body .category-row:hover {
            background-color: #f1f7ff !important;
        }

        .arrow {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-right: 2px solid #555;
            border-bottom: 2px solid #555;
            transform: rotate(45deg);
            transition: transform 0.2s ease;
            margin-left: 10px;
            margin-right: 10px;
            cursor: pointer;
        }

        .category-row.active .arrow {
            transform: rotate(135deg);
        }
    </style>

    <!-- JS for search -->
    <script>
        const searchInput = document.getElementById('search');
        const tableBody = document.getElementById('categories-body');
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
                    `<td colspan="7" style="text-align:center; padding:12px; color:#888;">No category found</td>`;
                tableBody.appendChild(noRow);
            }
        });

        document.querySelectorAll('.arrow').forEach(arrow => {
            arrow.addEventListener('click', (event) => {
                event.stopPropagation(); // Prevent triggering row clicks
                const row = arrow.closest('.category-row');
                const category = row.dataset.category;
                const productsRow = document.querySelector(`.product-row.${category}`);
                const isActive = row.classList.contains('active');

                if (!isActive) {
                    productsRow.style.display = 'table-row';
                    row.classList.add('active');
                } else {
                    productsRow.style.display = 'none';
                    row.classList.remove('active');
                }
            });
        });
    </script>

@endsection
