<?php
/**
 * Receipt Template for POS System
 * This file generates the HTML receipt that will be printed
 */

// Check if required data is provided
if (!isset($sale_data) || !is_array($sale_data)) {
    die('Invalid receipt data');
}

// Extract data from the sale_data array
extract($sale_data);

// Set timezone and format date
date_default_timezone_set('Asia/Manila');
$date = date('Y-m-d H:i:s');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #<?php echo $receipt_number; ?></title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            margin: 0;
            padding: 5mm;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .store-name {
            font-weight: bold;
            font-size: 16px;
            margin: 0;
        }
        .store-address, .store-contact {
            font-size: 10px;
            margin: 5px 0;
        }
        .receipt-title {
            text-align: center;
            font-weight: bold;
            margin: 10px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 0;
        }
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 11px;
        }
        .receipt-info .label {
            font-weight: bold;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .items th {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding: 3px 0;
        }
        .items td {
            padding: 3px 0;
            vertical-align: top;
        }
        .items .qty {
            width: 15%;
            text-align: center;
        }
        .items .desc {
            width: 50%;
        }
        .items .price, .items .total {
            width: 20%;
            text-align: right;
        }
        .totals {
            width: 100%;
            margin: 10px 0;
        }
        .totals td {
            padding: 3px 0;
            text-align: right;
        }
        .totals .label {
            text-align: left;
            font-weight: bold;
        }
        .totals .amount {
            text-align: right;
            padding-left: 10px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        .barcode {
            text-align: center;
            margin: 10px 0;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <p class="store-name"><?php echo htmlspecialchars($store_name); ?></p>
        <p class="store-address"><?php echo htmlspecialchars($store_address); ?></p>
        <p class="store-contact"><?php echo htmlspecialchars($store_contact); ?></p>
        <p class="store-tin">TIN: <?php echo htmlspecialchars($store_tin); ?></p>
    </div>
    
    <div class="receipt-title">OFFICIAL RECEIPT</div>
    
    <div class="receipt-info">
        <div class="label">Receipt #:</div>
        <div class="value"><?php echo $receipt_number; ?></div>
    </div>
    
    <div class="receipt-info">
        <div class="label">Date:</div>
        <div class="value"><?php echo $date; ?></div>
    </div>
    
    <div class="receipt-info">
        <div class="label">Cashier:</div>
        <div class="value"><?php echo htmlspecialchars($cashier_name); ?></div>
    </div>
    
    <?php if (!empty($customer_name)): ?>
    <div class="receipt-info">
        <div class="label">Customer:</div>
        <div class="value"><?php echo htmlspecialchars($customer_name); ?></div>
    </div>
    <?php endif; ?>
    
    <table class="items">
        <thead>
            <tr>
                <th class="qty">Qty</th>
                <th class="desc">Description</th>
                <th class="price">Price</th>
                <th class="total">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td class="qty"><?php echo $item['quantity']; ?></td>
                <td class="desc"><?php echo htmlspecialchars($item['name']); ?></td>
                <td class="price">₱<?php echo number_format($item['price'], 2); ?></td>
                <td class="total">₱<?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <table class="totals">
        <tr>
            <td class="label">Subtotal:</td>
            <td class="amount">₱<?php echo number_format($subtotal, 2); ?></td>
        </tr>
        <?php if ($discount > 0): ?>
        <tr>
            <td class="label">Discount (<?php echo $discount_percent; ?>%):</td>
            <td class="amount">-₱<?php echo number_format($discount, 2); ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td class="label">Tax (12%):</td>
            <td class="amount">₱<?php echo number_format($tax, 2); ?></td>
        </tr>
        <tr>
            <td class="label"><strong>Total:</strong></td>
            <td class="amount"><strong>₱<?php echo number_format($total, 2); ?></strong></td>
        </tr>
        <tr>
            <td class="label">Amount Tendered:</td>
            <td class="amount">₱<?php echo number_format($amount_tendered, 2); ?></td>
        </tr>
        <tr>
            <td class="label">Change:</td>
            <td class="amount">₱<?php echo number_format($change, 2); ?></td>
        </tr>
    </table>
    
    <div class="barcode">
        <div id="barcode"></div>
        <div>#<?php echo $receipt_number; ?></div>
    </div>
    
    <div class="footer">
        <p>Thank you for shopping with us!</p>
        <p>This receipt serves as your official receipt</p>
        <p>VAT Registered: <?php echo $store_tin; ?></p>
        <p>Date Issued: <?php echo $date; ?></p>
        <p>*** END OF RECEIPT ***</p>
    </div>
    
    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button class="print-btn" onclick="window.print()">Print Receipt</button>
        <button class="print-btn" onclick="window.close()" style="margin-left: 10px;">Close</button>
    </div>
    
    <!-- Include barcode library -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        // Generate barcode
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof JsBarcode !== 'undefined') {
                JsBarcode("#barcode", "<?php echo $receipt_number; ?>", {
                    format: "CODE128",
                    lineColor: "#000",
                    width: 2,
                    height: 40,
                    displayValue: false
                });
            }
            
            // Auto-print if opened in a new window
            if (window.opener) {
                window.print();
                
                // Close the window after printing or after a delay
                window.onafterprint = function() {
                    setTimeout(function() {
                        window.close();
                    }, 1000);
                };
            }
        });
    </script>
</body>
</html>
