<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\Order;
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
        $obj = [
            "Orders" => [[
                "deliveryDate" => "05-23-2023",
                "Address" => $order->shipping_address,
                "customer" => $order->customer_name,
                "Items" => $items
            ],]
        ];

        $URL = "https://morsumpartner.free.beeceptor.com/api/v1/orders";
        # $URL = $order->partner->uri;
        dump("Posting to ${URL}");
        $response = Http::post($URL, json_encode($obj));
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
