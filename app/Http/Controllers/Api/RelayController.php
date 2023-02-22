<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Formatters\API;
use App\Http\Controllers\Api\Formatters\CSV;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class RelayController
{
    static function postToAPI(Order $order) {
        $api_format = new API();
        $items = OrderItemController::getItems($order->order_items);
        $casted_date = (string) $order->delivery_date;
        $res = $api_format->formatBody($casted_date, $order->shipping_address, $order->customer_name, $items);

        # $URL = $order->partner->uri;
        $URL = "https://morsumpartner.free.beeceptor.com/api/v1/orders";
        $response = Http::post($URL, json_encode($res));
        if ($response->ok() && $response->body()) {
            $order->status = 'relayed';
            $order->relayed_at = Carbon::now();
            $order->save();
        } else {
            $order->status = 'errored';
            $order->save();
            $res = ["error" => $response->reason()];
        }
        return $res;
    }

    static function sendCSV(Order $order) {
        $csv_format = new CSV();
        $items = OrderItemController::getItems($order->order_items);
        $res = $csv_format->formatBody((string) $order->delivery_date, $order->shipping_address, $order->customer_name, $items);

        $created = Storage::disk('local')->put("order.csv", $res);
        if ($created) {
            $order->status = 'relayed';
            $order->relayed_at = Carbon::now();
            $order->save();
        } else {
            $order->status = 'errored';
            $order->save();
            $res = ["error" => "Error Generating CSV Order file"];
        }
        return $res;
    }
}
