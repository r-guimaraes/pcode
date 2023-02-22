<?php

namespace App\Http\Controllers\Api\Formatters;

use Carbon\Carbon;

class CSV implements FormatRequest {
    public function formatBody($date_str, $address, $customer, $items)
    {
        $formatted_date = $this->formatDate($date_str);
        return [
            "delivery date" => $formatted_date,
            "street address" => $address,
            "customer" => $customer,
            "items id" => $items,
            "items quantuty" => $items
        ];
    }

    /**
     * @return mixed
     */
    public function formatDate($date_str)
    {
        return Carbon::createFromFormat('Y-m-d', $date_str)->format('d/m/Y');
    }
}
