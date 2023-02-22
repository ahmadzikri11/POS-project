<?php

namespace App\Http\Livewire\Kasir;

use App\Models\OrderProduct;
use Livewire\Component;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Order;

class Index extends Component
{
    public $product_id;
    public $pembayaran;

    protected $rules = [
        'product_id' => 'required|unique:transactions'

    ];

    public function submit(){

        $this->validate();

        $transaction = Transaction::create([
            'product_id' => $this->product_id,
            'qty' => 1
        ]);
        $transaction ->total = $transaction->product->price;
        $transaction->save();

        session()->flash('message', 'Produk Berhasil Ditambah');
    }

    public function increment($id)
    {
        // dd($id);
        $transaction = Transaction::find($id);
        $transaction -> update([
            'qty' => $transaction->qty + 1,
            'total' => $transaction->product->price * ($transaction->qty + 1)
        ]);
        session()->flash('message', ' Qty Produk Berhasil Ditambah');
        return redirect()->to('/kasir');
    }

    public function decrement($id)
    {
        // dd($id);
        $transaction = Transaction::find($id);
        $transaction -> update([
            'qty' => $transaction->qty - 1,
            'total' => $transaction->product->price * ($transaction->qty - 1)
        ]);
        session()->flash('message', ' Qty Produk Berhasil Dikurangi');
        return redirect()->to('/kasir');
    }

    public function deleteTransaction($id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete();
        session()->flash('message', 'Transaction Berhasil Di Hapus');
    }

    public function save()
    {
        $order = Order::create([
            'no_order' => 'OD-'.date('Ymd').rand(1111,9999),
            'nama_kasir' => auth()->user()->name
        ]);

        $transaction = Transaction::get();
        foreach ($transaction as $key => $value) {
            $product = array(
                'order_id' => $order->id,
                'product_id' => $value->product_id,
                'qty' => $value->qty,
                'total' => $value->total,
                'created_at' => \Carbon\carbon::now(),
                'total' => \Carbon\carbon::now()
            );
            $orderProduct = OrderProduct::insert($product);

            $deletTransaction = Transaction::where('id', $value->id)->delete();
        }
        // return redirect()->to('/invoice');
        session()->flash('message', 'Transaction Berhasil Di Simpan');
    }

    public function render()
    {
        return view('livewire.kasir.index',[
            'products' => Product::orderBY('name_product','asc')->get(),
            'transactions' => Transaction::get()
        ]);
    }
}
