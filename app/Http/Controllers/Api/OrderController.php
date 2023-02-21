<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'orders' => Order::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) #RedirectResponse
    {
        $validated = $request->validate([
            'delivery_date' => 'required',
            'address' => 'required',
            'customer' => 'required',
            'id' => 'required',
            'partner_id' => 'required',
            'items' => 'required',
        ]);
        $order = new Order(['status' => 'created']);
        $order->ingested_at = Carbon::now();
        $date = Carbon::createFromFormat('d-m-Y', $validated['delivery_date'])->format('Y-m-d');
        $order->delivery_date = $date;
        $order->origin_order_id = $validated['id'];
        $order->customer_name = $validated['customer'];
        $order->shipping_address = $validated['address'];
        $order->partner_id = $validated['partner_id'];
        $id = $order->save();

        foreach ($validated['items'] as $item) {
            $oitm = new OrderItem;
            $oitm->quantity = $item['qty'];
            $oitm->order_id = $id;
            $oitm->item_id = $item['itemid'];
            $order->order_items()->save($oitm);
        }

        # $order->save();

        return 'ok';
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return $order;
    }
}
