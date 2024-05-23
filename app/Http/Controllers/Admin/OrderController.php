<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Traits\HasImage;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class OrderController extends Controller
{
    use HasImage;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $orders = Order::with('user')->paginate(10);

        $search = $request->search;

        // ->with(['products' => function($query) use ($searchString){
        //     $query->where('name', 'like', '%'.$searchString.'%');
        // }])

        $orders = Order::with('user')->when($search, function($query) use($search){
            // $query = $query->orwhereDate('created_at', '=', $search);
            $query = $query->orwhere('name', 'like', '%'.$search.'%');
        })->paginate(10)->withQueryString();

        // $categories = Category::get();

        // $suppliers = Supplier::get();

        return view('admin.order.index', compact('orders','search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        // $image = $this->uploadImage($request, $path = 'public/products/', $name = 'image');

        if($order->status == OrderStatus::Pending){
            $order->update([
                'status' => OrderStatus::Verified,
            ]);

            $length = 8;
            $random = '';

            for($i = 0; $i < $length; $i++){
                $random .= rand(0,1) ? rand(0,9) : chr(rand(ord('a'), ord('z')));
            }

            $invoice = 'INV-'.Str::upper($random);

            $cart = Order::find($order->id);

            $transaction = Transaction::create([
                'invoice' => $invoice,
                'user_id' => $cart->user_id,
            ]);

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                ]);
                Product::whereId($cart->product_id)->decrement('quantity', $cart->quantity);
        }
        
        return back()->with('toast_success', 'Permintaan Barang Sudah Dikonfirmasi');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
