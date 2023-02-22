<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Formatters\API;
use App\Http\Controllers\Api\Formatters\CSV;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

class RelayController
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

        $api_format = new API();
        $casted_date = (string) $order->delivery_date;
        $res = $api_format->formatBody($casted_date, $order->shipping_address, $order->customer_name, $items);

        # $URL = $order->partner->uri;
        $URL = "https://morsumpartner.free.beeceptor.com/api/v1/orders";
        $response = Http::post($URL, json_encode($res));
        if ($response->ok() && $response->body()) {
            $order->status = 'relayed';
            $order->save();
        } else {
            $order->status = 'errored';
            $order->save();
            $res = ["error" => $response->reason()];
        }
        return $res;
    }

    static function sendCSV(Order $order) {
        $items = [];
        foreach ($order->order_items as $oitem) {
            $item = Item::find($oitem->item_id);
            if ($item) {
                $i = ["orderqty" => $oitem->quantity, "itemid" => $item->external_id];
                array_push($items, $i);
            }
        }
        $csv_format = new CSV();
        $res = $csv_format->formatBody((string) $order->delivery_date, $order->shipping_address, $order->customer_name, $items);
        return $res;
    }
}
