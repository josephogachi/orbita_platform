<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logistics CBM Export</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* This hides buttons when saving the actual PDF */
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8 text-sm" onload="window.print()">
    
    <div class="max-w-6xl mx-auto bg-white p-10 shadow-lg rounded-xl">
        
        <div class="flex justify-between items-center mb-8 border-b pb-6">
            <div>
                <h1 class="text-3xl font-black text-blue-900 uppercase tracking-widest">Orbita Kenya</h1>
                <p class="text-gray-500 font-bold mt-1">Logistics & CBM Report</p>
            </div>
            <div class="text-right">
                <p class="text-gray-600 font-bold">Date: {{ date('M d, Y') }}</p>
                <button onclick="window.print()" class="no-print mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700">
                    Save as PDF
                </button>
            </div>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-blue-900 text-white uppercase text-xs">
                    <th class="p-3 rounded-tl-lg">Product / SKU</th>
                    <th class="p-3 text-center">Pcs/Box</th>
                    <th class="p-3 text-center">CBM (Box)</th>
                    <th class="p-3 text-center">CBM (Piece)</th>
                    <th class="p-3 text-right">Cost/Box</th>
                    <th class="p-3 text-right rounded-tr-lg">Cost/Piece</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="p-3">
                        <span class="font-bold text-gray-900 block">{{ $product->product_name }}</span>
                        <span class="text-xs text-gray-400">{{ $product->sku ?? 'No SKU' }}</span>
                    </td>
                    <td class="p-3 text-center font-bold text-gray-600">{{ $product->pcs_per_carton }}</td>
                    <td class="p-3 text-center text-gray-600">{{ $product->cbm_per_carton ?? '-' }}</td>
                    <td class="p-3 text-center font-bold text-blue-600">{{ $product->cbm_per_piece ?? '-' }}</td>
                    <td class="p-3 text-right text-gray-500">KES {{ number_format($product->shipping_cost_per_carton, 2) }}</td>
                    <td class="p-3 text-right font-bold text-green-600">KES {{ number_format($product->shipping_cost_per_piece, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center text-gray-400 text-xs">
            Generated securely from the Orbita Kenya Inventory System.
        </div>
    </div>

</body>
</html>