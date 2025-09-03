<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CartController extends Controller
{
    private function cart()
    {
        return session()->get('cart', []);
    }

    private function putCart(array $cart)
    {
        session()->put('cart', $cart);
    }

    public function index()
    {
        $cart = $this->cart();
        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
        return view('frontend.cart.index', compact('cart', 'subtotal'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1']
        ]);

        $qty = max((int)$request->input('quantity', 1), 1);

        $cart = $this->cart();

        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty'] += $qty;
        } else {
            $cart[$product->id] = [
                'id'    => $product->id,
                'name'  => $product->name,
                'price' => (float) $product->price,
                'qty'   => $qty,
                'image' => $product->image_url ? asset('storage/' . $product->image_url) : asset('images/placeholder.jpg'),
            ];
        }

        $this->putCart($cart);

        return redirect()->route('frontend.cart.index')
            ->with('status', 'Added to cart.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:1']
        ]);

        $cart = $this->cart();
        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty'] = $data['qty'];
            $this->putCart($cart);
        }

        return back()->with('status', 'Cart updated.');
    }

    public function remove(Product $product)
    {
        $cart = $this->cart();
        unset($cart[$product->id]);
        $this->putCart($cart);

        return back()->with('status', 'Item removed.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('status', 'Cart cleared.');
    }
}
