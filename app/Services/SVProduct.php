<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductAssign;
use App\Models\Store;
use Illuminate\Support\Facades\Storage;

class SVProduct
{
    public function getProductWithPagination($params)
    {
        $search = $params['search'] ?? null;
        $limit  = $params['limit'] ?? 10;
        $user   = request()->user();

        $query = Product::with([
            'image',
            'assigns' => fn($q) => $q->where('store_id', $user->store_id),
        ])
        ->select('id','name','barcode','unit')
        ->whereNull('deleted_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('barcode', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        $paginate = $query->orderBy('_id', 'desc')->paginate($limit);

        $items = $paginate->items();

        foreach ($items as &$item) {
            $item->image_url = @$item->image->url ? url($item->image->url) : null;
            $assign          = $item->assigns->first();
            $item->cost      = $assign->cost ?? 0;
            $item->price     = $assign->price ?? 0;
            $item->threshold = $assign->threshold. ' ' . $item->unit;
            $item->in_stock  = $assign->quantity. ' ' . $item->unit;
            unset($item->unit, $item->image, $item->assigns);
        }

        return [
            'products' => $items,
            'meta'     => [
                'count'        => $paginate->count(),
                'per_page'     => $paginate->perPage(),
                'current_page' => $paginate->currentPage(),
                'total_pages'  => $paginate->lastPage(),
                'total'        => $paginate->total(),
            ],
        ];
    }

    public function create(array $params)
    {
        $product = Product::create([
            'name'        => $params['name'],
            'barcode'     => $params['barcode'] ?? null,
            'description' => $params['description'] ?? null,
            'unit'        => $params['unit'] ?? 'pcs',
        ]);

        $this->upsertProductAssigns($product, $params['assigns'] ?? []);

        $this->upsertProductImage($product, $params['image_url'] ?? null);

        return $product;
    }

    public function upsertProductAssigns($product, $assigns)
    {
        foreach ($assigns as $assign) {
            ProductAssign::updateOrCreate(
                [
                    'store_id'   => $assign['store_id'],
                    'product_id' => $product->id,
                ],
                [
                    'quantity'   => $assign['quantity'] ?? 0,
                    'threshold'  => $assign['threshold'] ?? 0,
                    'price'      => $assign['price'] ?? 0,
                    'cost'       => $assign['cost'] ?? 0,
                ]
            );
        }
    }

    public function upsertProductImage($product, $imageUrl)
    {
        Image::updateOrCreate(
            [
                'object_id'  => $product->_id,
                'collection' => 'products',
            ],
            [
                'url' => $imageUrl,
            ]
        );
    }

    public function getById($id)
    {
        $product = Product::with([
            'image',
            'assigns',
        ])
        ->select('id','name','barcode','description','unit')
        ->where('id', $id)
        ->whereNull('deleted_at')
        ->first();

        if (!$product) {
            throw new \Exception('Product not found');
        }

        $product->image_url = @$product->image->url ? url($product->image->url) : null;
        unset($product->image);

        $assigns = [];
        foreach ($product->assigns as $assign) {
            $assigns[] = [
                'store_id'   => $assign->store_id,
                'quantity'   => $assign->quantity. ' ' . $product->unit,
                'threshold'  => $assign->threshold. ' ' . $product->unit,
                'price'      => $assign->price,
                'cost'       => $assign->cost,
            ];
        }
        unset($product->assigns);
        $product->assigns = $assigns;

        return $product;
    }

    public function update($id, array $params)
    {
        $product = Product::where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$product) {
            throw new \Exception('Product not found');
        }

        $product->update([
            'name'        => $params['name'],
            'barcode'     => $params['barcode'] ?? null,
            'description' => $params['description'] ?? null,
            'unit'        => $params['unit'] ?? 'pcs',
        ]);

        $this->upsertProductAssigns($product, $params['assigns'] ?? []);

        $this->upsertProductImage($product, $params['image_url'] ?? null);

        return $product;
    }

    public function delete($id)
    {
        $product = Product::where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$product) {
            throw new \Exception('Product not found');
        }

        // Soft Delete Product
        $product->deleted_at = now();
        $product->save();

        // Soft Delete Product Assigns
        ProductAssign::where('product_id', $product->id)
            ->whereNull('deleted_at')
            ->update(['deleted_at' => now()]);

        // Soft Delete Product Image
        Image::where('object_id', $product->_id)
            ->where('collection', 'products')
            ->whereNull('deleted_at')
            ->update(['deleted_at' => now()]);
    }

    public function export()
    {
        $csvData = [
            [
                'Store Name',
                'Product Name',
                'Category Name',
                'Barcode',
                'Cost',
                'Price',
                'Quantity',
                'Threshold',
                'Unit',
                'Description'
            ]
        ];

        // Convert to CSV string
        $fp = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($fp, $row);
        }
        rewind($fp);
        $csv = stream_get_contents($fp);
        fclose($fp);

        $filePath = 'exports/Import Product Template'. '.csv';
        $fullPath = public_path('storage/' . $filePath);

        // Ensure the directory exists
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($fullPath, $csv);

        return 'storage/' . $filePath;
    }

    public function import($filePath)
    {
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new \Exception('Could not open the file!');
        }

        $header = fgetcsv($handle);
        if ($header === false) {
            throw new \Exception('Could not read the header row!');
        }

        $requiredColumns = [
            'Store Name',
            'Product Name',
            'Category Name',
            'Barcode',
            'Cost',
            'Price',
            'Quantity',
            'Threshold',
            'Unit',
            'Description'
        ];

        // Validate header
        foreach ($requiredColumns as $col) {
            if (!in_array($col, $header)) {
                throw new \Exception("Missing required column: $col");
            }
        }

        $products = [];
        $assigns = [];
        $errors = [];
        $excelBarcodes = [];
        $rowNumber = 2; // Start from 2 because header is row 1

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            // Validate Store Name exists in DB
            $store = Store::where('name', $data['Store Name'])->first();
            if (!$store) {
                $errors[] = "Row {$rowNumber}: Store Name (cell '{$data['Store Name']}') does not exist.";
                $rowNumber++;
                continue;
            }

            // Validate Barcode uniqueness in Excel
            $barcode = $data['Barcode'] ?? null;
            if ($barcode) {
                if (in_array($barcode, $excelBarcodes)) {
                    $errors[] = "Row {$rowNumber}: Barcode (cell '{$barcode}') is duplicate in Excel.";
                    $rowNumber++;
                    continue;
                }
                $excelBarcodes[] = $barcode;

                // Validate Barcode uniqueness in DB
                if (Product::where('barcode', $barcode)->exists()) {
                    $errors[] = "Row {$rowNumber}: Barcode (cell '{$barcode}') already exists in database.";
                    $rowNumber++;
                    continue;
                }
            }

            $products[] = [
                'name'        => $data['Product Name'],
                'barcode'     => $barcode,
                'description' => $data['Description'] ?? null,
                'unit'        => $data['Unit'] ?? 'pcs',
                'created_at'  => now(),
                'updated_at'  => now(),
            ];

            $assigns[] = [
                'store_name'  => $data['Store Name'],
                'quantity'    => (int)($data['Quantity'] ?? 0),
                'threshold'   => (int)($data['Threshold'] ?? 0),
                'price'       => (float)($data['Price'] ?? 0),
                'cost'        => (float)($data['Cost'] ?? 0),
            ];

            $rowNumber++;
        }

        fclose($handle);

        $importedCount = 0;

        // Batch insert products
        if (!empty($products)) {
            // Insert products and get their IDs
            $insertedIds = [];
            foreach ($products as $productData) {
                $product = Product::create($productData);
                $insertedIds[] = $product->id;
            }

            // Batch insert assigns
            foreach ($assigns as $idx => $assignData) {
                $store = Store::where('name', $assignData['store_name'])->first();
                if ($store && isset($insertedIds[$idx])) {
                    ProductAssign::create([
                        'store_id'   => $store->id,
                        'product_id' => $insertedIds[$idx],
                        'quantity'   => $assignData['quantity'],
                        'threshold'  => $assignData['threshold'],
                        'price'      => $assignData['price'],
                        'cost'       => $assignData['cost'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $importedCount++;
                }
            }
        }

        return [
            'imported_count' => $importedCount,
            'errors' => $errors,
        ];
    }
}
