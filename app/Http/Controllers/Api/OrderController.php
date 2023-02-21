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
        $id = $order->save();
        foreach ($validated['items'] as $item) {
            $oitm = new OrderItem;
            $oitm->order_id = $id;
            $oitm->quantity = $item['qty'];

            if (Item::where('internal_id', $item["origin_id"])->exists()) {
                $itm = Item::where('internal_id', $item["origin_id"])->first();
                $oitm->item_id = $itm->id;
            } else {
                $itm = Item::create(['name' => 'New Item', 'internal_id' => $item["origin_id"], 'external_id' => $item["external_id"], 'partner_id' => $validated['partner_id'] ]);
                if ($itm) {
                    $oitm->item_id = $itm->id;
                }
            }

            $order->order_items()->save($oitm);
        }

        $order->ingested_at = Carbon::now();

        # $this->relay($order);

        return $this->relay($order);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return $order;
    }

    private function relay(Order $order) {
        $items = [];
        foreach ($order->order_items as $oitem) {
            $item = Item::find($oitem->item_id);
            if ($item) {
                $i = ["orderqty" => $oitem->quantity, "itemid" => $item->external_id];
                array_push($items, $i);
            }
        }
        $obj = [
            "Orders" => [[
                "deliveryDate" => "05-23-2023",
                "Address" => $order->shipping_address,
                "customer" => $order->customer_name,
                "Items" => $items
            ],]
        ];
        $response = Http::post("https://morsumpartner.free.beeceptor.com/api/v1/orders", json_encode($obj));
        if ($response->ok() && $response->body()) {
            $order->status = 'ingested';
            $order->save();
        } else {
            dump("Something went wrong!!");
            $order->status = 'errored';
            $order->save();
        }
        return $obj;
    }
}
