<?php

namespace Tests\Feature\Http\Controllers\Api\Formatters;
use App\Http\Controllers\Api\Formatters\CSV;
use Tests\TestCase;
use Carbon\Carbon;

class CSVTest extends TestCase
{

    public function test_date_formatting(): void
    {
        $sent_date_format = "22-02-2023";
        $expected_date_format = "22/02/2023";
        $date = Carbon::createFromFormat('d-m-Y', $sent_date_format)->format('Y-m-d');

        $csv_formatter = new CSV();
        $this->assertEquals($expected_date_format, $csv_formatter->formatDate($date));
    }

    public function test_structure_formatting(): void
    {
        $items = [
            ["orderqty" => 5, "itemid" => '123444'],
            ["orderqty" => 16, "itemid" => '5566677'],
            ["orderqty" => 7, "itemid" => '1123'],
        ];
        $csv_formatter = new CSV();
        $cvs_data = $csv_formatter->formatBody("2023-02-22", "123 Main Lane Street, Atlanta, GA 30333", "Mr Gomes", $items);
        $csv_expected_structure  = "delivery date,street address,city,state,postal code,customer,items id,items quantity\n";
        $csv_expected_structure .= "22/02/2023,123 Main Lane Street, Atlanta,GA,30333,Mr Gomes,123444,5\n,,,,,,5566677,16\n,,,,,,1123,7\n";

        $this->assertEquals($cvs_data, $csv_expected_structure);
    }

}
