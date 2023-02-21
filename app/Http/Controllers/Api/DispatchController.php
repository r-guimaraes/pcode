<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class DispatchController
{
    static function postToAPI(Order $order) {
        $items = [];
        foreach ($order->order_items as $oitem) {
            $item = Item::find($oitem->item_id);
            if ($item) {
                $i = ["orderqty" => $oitem->quantity, "itemid" => $item->external_id];
                array_push($items, $i);
            }
        }

        $formatted_date = Carbon::createFromFormat('Y-m-d', $order->delivery_date)->format('m-d-Y');
        $res = [
            "Orders" => [[
                "deliveryDate" => $formatted_date,
                "Address" => $order->shipping_address,
                "customer" => $order->customer_name,
                "Items" => $items
            ],]
        ];

        # $URL = $order->partner->uri;
        $URL = "https://morsumpartner.free.beeceptor.com/api/v1/orders";
        $response = Http::post($URL, json_encode($res));
        if ($response->ok() && $response->body()) {
            $order->status = 'ingested';
            $order->save();
        } else {
            $order->status = 'errored';
            $order->save();
            $res = ["error" => $response->reason()];
        }
        return $res;
    }
}
