<?php

namespace App\Http\Controllers\Api\Formatters;

use App\Models\Item;

interface FormatRequest
{
    /*
     * @param string $date - expected delivery date
     * @param string $address - delivery address
     * @param string $customer - customer name
     * @param Item[] $items - order list of items
     *
     * @return mixed - formatted data structured to proper exchange type
     * */
    public function formatBody($date_str, $address, $customer, Item $items);

    public function formatDate($date_str);
}
