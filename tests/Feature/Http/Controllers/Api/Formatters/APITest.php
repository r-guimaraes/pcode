<?php

namespace Tests\Feature\Http\Controllers\Api\Formatters;
use App\Http\Controllers\Api\Formatters\API;
use Tests\TestCase;
use Carbon\Carbon;

class APITest extends TestCase
{
    protected static API $api_formatter;
    protected static string $expected_date_format;

    protected function setUp(): void
    {
        parent::setUp();
        self::$api_formatter = new API();
        self::$expected_date_format = "02-22-2023";
    }

    public function test_date_formatting(): void
    {
        $sent_date_format = "22-02-2023";
        $date = Carbon::createFromFormat('d-m-Y', $sent_date_format)->format('Y-m-d');

        $this->assertEquals(self::$expected_date_format, self::$api_formatter->formatDate($date));
    }

    public function test_structure_formatting(): void
    {
        $items = [
            ["orderqty" => 40, "itemid" => '0398921927788329761'],
            ["orderqty" => 70, "itemid" => '1289221927788aaaa38'],
            ["orderqty" => 88, "itemid" => '1284392192778832908'],
        ];
        $api_data = self::$api_formatter->formatBody("2023-02-22", "123 Street Name Here, HomeTown, GA 30334", "Robert", $items);
        $endpoint_expected_structure = [
            "Orders" => [[
                "deliveryDate" => "02-22-2023",
                "Address" => "123 Street Name Here, HomeTown, GA 30334",
                "customer" => "Robert",
                "Items" => [
                    ["orderqty" => 40, "itemid" => '0398921927788329761'],
                    ["orderqty" => 70, "itemid" => '1289221927788aaaa38'],
                    ["orderqty" => 88, "itemid" => '1284392192778832908']
                ]
            ], ]
        ];

        $this->assertEquals($api_data, $endpoint_expected_structure);
    }

}
