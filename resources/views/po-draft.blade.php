<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order</title>
    <style>
        :root {
            --gold: #a47e3c;
            --dark: #3c2a21;
            --beige: #bba27e;
            --light: #fffaf3;
            --shadow: rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: var(--light);
            color: var(--dark);
        }

        .container {
            max-width: 950px;
            margin: 40px auto;
            background: var(--beige);
            padding: 40px 50px;
            border-radius: 20px;
            box-shadow: 0 15px 25px var(--shadow);
            transition: transform 0.3s ease;
        }

        .container:hover {
            transform: scale(1.01);
        }

        h1,
        h2,
        h3,
        h4 {
            margin: 0;
            font-weight: 600;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 35px;
        }

        .company-info h2 {
            color: var(--gold);
            font-size: 2rem;
        }

        .company-info p {
            margin: 4px 0;
            color: var(--dark);
        }

        .po-info h3 {
            font-size: 1.6rem;
            color: var(--dark);
        }

        .po-info p {
            margin: 4px 0;
        }

        .supplier {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            background: var(--light);
            padding: 25px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px var(--shadow);
            transition: transform 0.3s ease;
        }

        .supplier:hover {
            transform: translateY(-2px);
        }

        .supplier div h4 {
            color: var(--gold);
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px var(--shadow);
        }

        table th,
        table td {
            padding: 14px 18px;
            text-align: left;
        }

        table th {
            background: var(--gold);
            color: var(--light);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        table tbody tr:nth-child(even) {
            background: #fffaf3;
        }

        table tbody tr:nth-child(odd) {
            background: #f7f2eb;
        }

        table tbody tr:hover {
            background: #f1e5c5;
            transition: background 0.3s ease;
        }

        table td.right {
            text-align: right;
        }

        .totals {
            max-width: 400px;
            margin-left: auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px var(--shadow);
            background: var(--light);
            transition: transform 0.3s ease;
        }

        .totals:hover {
            transform: translateY(-2px);
        }

        .totals table {
            width: 100%;
            border: none;
        }

        .totals table td {
            padding: 12px 18px;
            border: none;
        }

        .totals table tr.total td {
            font-weight: bold;
            border-top: 3px solid var(--gold);
            color: var(--gold);
            font-size: 1.15rem;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 0.95rem;
            color: var(--dark);
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, var(--gold), var(--dark));
            color: var(--light);
            padding: 14px 30px;
            border: none;
            border-radius: 50px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px var(--shadow);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px var(--shadow);
        }

        @media (max-width: 768px) {
            .header,
            .supplier {
                flex-direction: column;
                text-align: left;
            }

            .po-info {
                margin-top: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h2>Khmer Angkor Co., Ltd</h2>
                <p>123 Street Name, Phnom Penh, Cambodia</p>
                <p>Email: info@khmerangkor.com</p>
                <p>Phone: +855 12 345 678</p>
            </div>
            <div class="po-info">
                <h3>Purchase Order</h3>
                <p>PO Number: <strong>#PO-00123</strong></p>
                <p>Date: <strong>2025-12-16</strong></p>
                <p>Due Date: <strong>2025-12-30</strong></p>
            </div>
        </div>

        <!-- Supplier -->
        <div class="supplier">
            <div>
                <h4>Supplier:</h4>
                <p>ABC Supplier Co., Ltd</p>
                <p>456 Supplier Street, Phnom Penh</p>
                <p>Email: supplier@abc.com</p>
                <p>Phone: +855 98 765 432</p>
            </div>
            <div>
                <h4>Ship To:</h4>
                <p>Khmer Angkor Warehouse</p>
                <p>789 Warehouse Rd, Phnom Penh</p>
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Description</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Unit Price ($)</th>
                    <th>Total ($)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Product A</td>
                    <td>pcs</td>
                    <td>10</td>
                    <td class="right">5.00</td>
                    <td class="right">50.00</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Product B</td>
                    <td>pcs</td>
                    <td>20</td>
                    <td class="right">7.50</td>
                    <td class="right">150.00</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Product C</td>
                    <td>pcs</td>
                    <td>5</td>
                    <td class="right">12.00</td>
                    <td class="right">60.00</td>
                </tr>
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td class="right">$260.00</td>
                </tr>
                <tr>
                    <td>Tax (10%):</td>
                    <td class="right">$26.00</td>
                </tr>
                <tr class="total">
                    <td>Total:</td>
                    <td class="right">$286.00</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>© 2025 Khmer Angkor Co., Ltd. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
