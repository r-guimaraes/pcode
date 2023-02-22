<?php

namespace Tests\Feature\Http\Controllers\Api\Formatters;
use App\Http\Controllers\Api\Formatters\API;
use Tests\TestCase;
use Carbon\Carbon;

class APITest extends TestCase
{

    public function test_date_formatting(): void
    {
        $sent_date_format = "22-02-2023";
        $expected_date_format = "02-22-2023";
        $date = Carbon::createFromFormat('d-m-Y', $sent_date_format)->format('Y-m-d');

        $csv_formatter = new API();
        $this->assertEquals($expected_date_format, $csv_formatter->formatDate($date));
    }

}
