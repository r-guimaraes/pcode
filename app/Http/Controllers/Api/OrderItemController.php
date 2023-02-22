<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;

class OrderItemController extends Controller {
    /**
     * @param OrderItem $order_items
     * @return array array
     */
    public static function getItems(Collection $order_items): array
    {
        $items = [];
        foreach ($order_items as $order_item) {
            $item = Item::find($order_item->item_id);
            if ($item) {
                $itm = ["orderqty" => $order_item->quantity, "itemid" => $item->external_id];
                array_push($items, $itm);
            }
        }

        return $items;
    }
}
