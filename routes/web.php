<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;
use App\Models\Message;
use App\Models\Customer;

/*
|--------------------------------------------------------------------------
| Login & Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    if (
        $request->email === 'admin@inkthread.com' &&
        $request->password === 'Roopkishor'
    ) {
        Session::put('admin', true);
        return redirect('/');
    }
    return back()->with('error', 'Invalid Credentials');
});

Route::get('/logout', function () {
    Session::forget('admin');
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| Protected Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware('admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/', function () {
        $totalProducts = Product::count();
        $totalMessages = collect(
            DB::connection('mongodb')
                ->getDatabase()
                ->selectCollection('contactforms')
                ->find()
                ->toArray()
        )->count();

        $totalOrders = Order::count();
        $totalCustomers = Order::distinct('customerEmail')->count();
        $pendingOrders = Order::where('status', 'Pending')->count();
        $deliveredOrders = Order::where('status', 'Delivered')->count();
        $totalRevenue = Order::where('status', 'Delivered')->sum('productPrice');

        return view(
            'admin.dashboard',
            compact(
                'totalProducts',
                'totalMessages',
                'totalOrders',
                'totalCustomers',
                'pendingOrders',
                'deliveredOrders',
                'totalRevenue'
            )
        );
    });

    /*
    |--------------------------------------------------------------------------
    | Products Management (CRUD)
    |--------------------------------------------------------------------------
    */
    Route::get('/products', function () {
        $products = Product::all();
        return view('admin.products', compact('products'));
    });

    Route::get('/products/create', function () {
        return view('admin.create-product');
    });

    // 1. FIX: Store Product Route with File Upload
    Route::post('/products/store', function (Request $request) {
        $request->validate([
            'title'       => 'required|string',
            'description' => 'required|string',
            'price'       => 'required|numeric',
            'category'    => 'required|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $imagePath = '/no-image.jpeg';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $imagePath = '/uploads/' . $filename;
        }

        $featuredStatus = $request->has('featured') ? 'true' : 'false';

        Product::create([
            'title'       => trim($request->title),
            'description' => trim($request->description),
            'price'       => (float) $request->price,
            'category'    => (string) $request->category,
            'image'       => (string) $imagePath,
            'discount'    => (int) ($request->discount ?? 0),
            'featured'    => $featuredStatus,
        ]);

        return redirect('/products')->with('success', 'Product Added Successfully');
    });

    Route::get('/products/edit/{id}', function ($id) {
        $product = Product::find($id);
        return view('admin.edit-product', compact('product'));
    });

    // 2. FIX: Update Product Route with Safe Image Overwrite
    Route::put('/products/update/{id}', function (Request $request, $id) {
        $product = Product::findOrFail($id);

        $request->validate([
            'title'       => 'required|string',
            'description' => 'required|string',
            'price'       => 'required|numeric',
            'category'    => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        // Agar array format me data save ho gya tha pehle toh use safe string nikaalein
        $currentImage = is_array($product->image) ? ($product->image[0] ?? '/no-image.jpeg') : $product->image;
        $imagePath = $currentImage;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $imagePath = '/uploads/' . $filename;

            // Purani image ko memory se clean karna (optional but helpful)
            if (!empty($currentImage) && $currentImage != '/no-image.jpeg' && file_exists(public_path($currentImage))) {
                @unlink(public_path($currentImage));
            }
        }

        $featuredStatus = $request->has('featured') ? 'true' : 'false';

        $product->update([
            'title'       => trim($request->title),
            'description' => trim($request->description),
            'price'       => (float) $request->price,
            'category'    => (string) $request->category,
            'image'       => (string) $imagePath,
            'discount'    => (int) ($request->discount ?? 0),
            'featured'    => $featuredStatus,
        ]);

        return redirect('/products')->with('success', 'Product Updated Successfully');
    });

    Route::get('/products/import', function () {
        return view('admin.import-product'); // helper view integration
    });

    Route::post('/products/import', function (Request $request) {
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt'
        ]);

        $file = fopen($request->file('csv')->getRealPath(), 'r');
        fgetcsv($file); // Skip Header

        $imported = 0;
        while (($row = fgetcsv($file)) !== false) {
            if (empty($row) || count($row) < 7 || empty(trim($row[0] ?? ''))) {
                continue;
            }

            Product::create([
                'title'       => trim($row[0] ?? ''),
                'description' => trim($row[1] ?? ''),
                'price'       => (float) ($row[2] ?? 0),
                'category'    => trim($row[3] ?? ''),
                'image'       => trim($row[4] ?? ''),
                'discount'    => (int) ($row[5] ?? 0),
                'featured'    => filter_var(trim($row[6] ?? 'false'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            ]);
            $imported++;
        }
        fclose($file);

        return redirect('/products')->with('success', $imported . ' Products Imported Successfully');
    });

    Route::delete('/products/delete/{id}', function ($id) {
        Product::find($id)?->delete();
        return redirect('/products')->with('success', 'Product Deleted');
    });

    Route::delete('/products/bulk-delete', function (Request $request) {
        if (!empty($request->selected_products)) {
            Product::whereIn('_id', $request->selected_products)->delete();
        }
        return redirect('/products')->with('success', 'Selected Products Deleted');
    });

    /*
    |--------------------------------------------------------------------------
    | Orders & Status Handling
    |--------------------------------------------------------------------------
    */
    Route::get('/orders', function () {
        $orders = Order::latest()->get();
        return view('admin.orders', compact('orders'));
    });

    Route::post('/orders/status/{id}', function ($id) {
        $order = Order::find($id);
        if (!$order) {
            return back()->with('error', 'Order Not Found');
        }
        $order->status = request('status');
        $order->save();

        return back()->with('success', 'Order Status Updated');
    });

    /*
    |--------------------------------------------------------------------------
    | Messages & Enquiries
    |--------------------------------------------------------------------------
    */
    Route::get('/messages', function () {
        $messages = collect(
            DB::connection('mongodb')
                ->getDatabase()
                ->selectCollection('contactforms')
                ->find()
                ->toArray()
        );
        return view('admin.messages', compact('messages'));
    });

    Route::delete('/messages/delete/{id}', function ($id) {
        Message::find($id)?->delete();
        return redirect('/messages')->with('success', 'Message Deleted');
    });

    /*
    |--------------------------------------------------------------------------
    | Customers & Miscs
    |--------------------------------------------------------------------------
    */
    Route::get('/customers', function () {
        $customers = collect(
            DB::connection('mongodb')
                ->getDatabase()
                ->selectCollection('users')
                ->find()
                ->toArray()
    );
        return view('admin.customers', compact('customers'));
    });

    Route::view('/discounts', 'admin.discounts');
    Route::view('/settings', 'admin.settings');
});