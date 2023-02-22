<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Nette\NotImplementedException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $orders = Order::all('id','created_at', 'status', 'origin_order_id');
        return response()->json([
            'total' => $orders->count(),
            'orders' => $orders
        ]);
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
//            'items.item_id' => 'required',
//            'items.external_item_id' => 'required',
//            'items.qty' => 'required',
        ]);

        $order = new Order(['status' => 'created']);
        $date = Carbon::createFromFormat('d-m-Y', $validated['delivery_date'])->format('Y-m-d');
        $order->delivery_date = $date;
        $order->origin_order_id = $validated['id'];
        $order->customer_name = $validated['customer'];
        $order->shipping_address = $validated['address'];
        $order->partner_id = $validated['partner_id'];
        $saved = $order->save();
        if ($saved) {
            foreach ($validated['items'] as $item) {
                $oitm = new OrderItem(['quantity' => $item['qty']]);
                if (Item::where(['internal_id' => $item["origin_id"], 'partner_id' => $validated['partner_id']])->exists()) {
                    $itm = Item::where(['internal_id' => $item["origin_id"], 'partner_id' => $validated['partner_id']])->first();
                    $oitm->item_id = $itm->id;
                } else {
                    $itm = Item::create(['name' => "New Item from Order", 'internal_id' => $item["origin_id"], 'external_id' => $item["external_id"], 'partner_id' => $validated['partner_id'] ]);
                    if ($itm) {
                        $oitm->item_id = $itm->id;
                    }
                }
                $order->order_items()->save($oitm);
            }

            $order->ingested_at = Carbon::now();
            return \response()->json([
                'result' => $this->relay($order)
            ]);
        }

        return \response()->json([ 'error' => true, 500 ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order) {
        return Order::with('order_items')->find($order->id);
    }

    private function relay(Order $order) {
        $obj = [];
        switch ($order->partner->exchange_type) {
            case 'API':
                $obj = RelayController::postToAPI($order);
                break;
            case 'CSV':
                $obj = RelayController::sendCSV($order);
                break;
            default:
                throw new NotImplementedException("Come back later!");
        }

        return $obj;
    }
}
