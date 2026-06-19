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
| Login
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
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/', function () {

        $products = Product::all();

        $totalProducts = Product::count();

        $totalMessages = collect(
            DB::connection('mongodb')
                ->getDatabase()
                ->selectCollection('contactforms')
                ->find()
                ->toArray()
        )->count();

        return view(
            'admin.dashboard',
            compact(
                'products',
                'totalProducts',
                'totalMessages'
            )
        );
    });

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */

    Route::get('/products', function () {

        $products = Product::all();

        return view('admin.products', compact('products'));
    });

    Route::get('/products/create', function () {

        return view('admin.create-product');
    });

    /*
    |--------------------------------------------------------------------------
    | Store Product
    |--------------------------------------------------------------------------
    */

    Route::post('/products/store', function (Request $request) {

        Product::create([

            'title'       => $request->title,
            'description' => $request->description,
            'price'       => (int) $request->price,
            'category'    => $request->category,
            'image'       => $request->image,
            'discount'    => (int) $request->discount,
            'featured'    => $request->has('featured'),

        ]);

        return redirect('/products')
            ->with('success', 'Product Added Successfully');
    });

    /*
    |--------------------------------------------------------------------------
    | Import CSV
    |--------------------------------------------------------------------------
    */

    Route::post('/products/import', function (Request $request) {

        $request->validate([
            'csv' => 'required|file|mimes:csv,txt'
        ]);

        $file = fopen(
            $request->file('csv')->getRealPath(),
            'r'
        );

        // Skip Header
        fgetcsv($file);

        $imported = 0;

        while (($row = fgetcsv($file)) !== false) {

            if (
                empty($row) ||
                count($row) < 7 ||
                empty(trim($row[0] ?? ''))
            ) {
                continue;
            }

            Product::create([

                'title'       => trim($row[0] ?? ''),
                'description' => trim($row[1] ?? ''),
                'price'       => (int) ($row[2] ?? 0),
                'category'    => trim($row[3] ?? ''),
                'image'       => trim($row[4] ?? ''),
                'discount'    => (int) ($row[5] ?? 0),

                'featured' => filter_var(
                    trim($row[6] ?? 'false'),
                    FILTER_VALIDATE_BOOLEAN
                ),

            ]);

            $imported++;
        }

        fclose($file);

        return redirect('/products')
            ->with(
                'success',
                $imported . ' Products Imported Successfully'
            );
    });

    /*
    |--------------------------------------------------------------------------
    | Delete Product
    |--------------------------------------------------------------------------
    */

    Route::delete('/products/delete/{id}', function ($id) {

        Product::find($id)?->delete();

        return redirect('/products')
            ->with('success', 'Product Deleted');
    });

    /*
    |--------------------------------------------------------------------------
    | Bulk Delete Products
    |--------------------------------------------------------------------------
    */

    Route::delete('/products/bulk-delete', function (Request $request) {

        if (!empty($request->selected_products)) {

            Product::whereIn(
                '_id',
                $request->selected_products
            )->delete();
        }

        return redirect('/products')
            ->with('success', 'Selected Products Deleted');
    });

    /*
    |--------------------------------------------------------------------------
    | Messages
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

        return view(
            'admin.messages',
            compact('messages')
        );
    });

    /*
    |--------------------------------------------------------------------------
    | Delete Message
    |--------------------------------------------------------------------------
    */

    Route::delete('/messages/delete/{id}', function ($id) {

        Message::find($id)?->delete();

        return redirect('/messages')
            ->with('success', 'Message Deleted');
    });

    /*
    |--------------------------------------------------------------------------
    | Other Pages
    |--------------------------------------------------------------------------
    */

    Route::view('/orders', 'admin.orders');
   Route::get('/customers', function () {

    $user = DB::connection('mongodb')
        ->getDatabase()
        ->selectCollection('users')
        ->findOne();

    dd($user);

});

    Route::view('/discounts', 'admin.discounts');

    Route::view('/settings', 'admin.settings');



    Route::get('/', function () {

        $totalProducts = Product::count();

        $totalMessages = Message::count();

        $totalOrders = Order::count();

        $totalCustomers = Order::distinct('customerEmail')->count();

        $pendingOrders = Order::where(
            'status',
            'Pending'
        )->count();

        $deliveredOrders = Order::where(
            'status',
            'Delivered'
        )->count();

        $totalRevenue = Order::where(
            'status',
            'Delivered'
        )->sum('productPrice');

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


    Route::get('/products/edit/{id}', function ($id) {

        $product = App\Models\Product::find($id);

        return view('admin.edit-product', compact('product'));
    });




    Route::put('/products/update/{id}', function (Request $request, $id) {

        $product = App\Models\Product::find($id);

        $product->update($request->all());

        return redirect('/products')
            ->with('success', 'Product Updated Successfully');
    });
});
