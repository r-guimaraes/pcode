<?php

namespace App\Http\Controllers\Api\Formatters;

use Carbon\Carbon;

class API implements FormatRequest {

    public function formatBody($date_str, $address, $customer, $items)
    {
        $formatted_date = $this->formatDate($date_str);
        return [
            "Orders" => [[
                "deliveryDate" => $formatted_date,
                "Address" => $address,
                "customer" => $customer,
                "Items" => $items
            ],]
        ];
    }

    /**
     * @return mixed
     */
    public function formatDate($date_str)
    {
        return Carbon::createFromFormat('Y-m-d', $date_str)->format('m-d-Y');
    }
}
