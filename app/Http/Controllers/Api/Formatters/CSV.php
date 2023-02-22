<?php

namespace App\Http\Controllers\Api\Formatters;

use Carbon\Carbon;

class CSV implements FormatRequest {
    public function formatBody($date_str, $address, $customer, $items)
    {
        $formatted_date = $this->formatDate($date_str);
        $addr_parts = explode(",", $address);
        $state = explode(" ", $addr_parts[2])[1];
        $postal_code = explode(" ", $addr_parts[2])[2];
        $default = [
            [
                'delivery date' => $formatted_date,
                'street address' => $addr_parts[0],
                'city' => $addr_parts[1],
                'state' => $state,
                'postal code' => $postal_code,
                'customer' => $customer,
                'items id' => $items[0]['itemid'],
                'items quantity' => $items[0]['orderqty'],
            ]
        ];
        $headers = "delivery date,street address,city,state,postal code,customer,items id,items quantity\n";
        foreach ($default as $d) {
            $headers .= "{$d['delivery date']},{$d['street address']},{$d['city']},{$d['state']},{$d['postal code']},{$d['customer']},{$d['items id']},{$d['items quantity']}\n";
        }
        $i = 0;
        foreach ($items as $item) {
            if ($i > 0) {
                $headers .= ",,,,,,{$item['itemid']},{$item['orderqty']}\n";
            }
            $i++;
        }

        return $headers;
    }

    /**
     * @return mixed
     */
    public function formatDate($date_str)
    {
        return Carbon::createFromFormat('Y-m-d', $date_str)->format('d/m/Y');
    }
}
