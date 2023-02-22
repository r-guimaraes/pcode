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
            /*
             * Send error to external service and error logs, like Sentry, AirBrake, RollBar, etc
             * and / or warn someone by e-mail
             * */
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

        $partner_bash_path = $order->partner->uri;
        $partner_bash_path = "Orders";
        $uploaded = Storage::disk('sftp')->put("${partner_bash_path}/order.csv", $res);

        if ($uploaded) {
            $order->status = 'relayed';
            $order->relayed_at = Carbon::now();
            $order->save();
        } else {
            $order->status = 'errored';
            $order->save();
            /*
             * Send error to external service and error logs, like Sentry, AirBrake, RollBar, etc
             * and / or warn someone by e-mail
             * */
            $res = ["error" => "Error Relaying CSV Order File"];
        }
        return $res;
    }
}
