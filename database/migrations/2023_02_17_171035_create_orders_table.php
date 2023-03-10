<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->set('status', ['created', 'errored', 'ingested', 'relayed']);
            $table->dateTime('ingested_at')->nullable();
            $table->dateTime('relayed_at')->nullable();
            $table->date('delivery_date');
            $table->string('customer_name');
            $table->string('origin_order_id');
            $table->text('shipping_address');
            $table->foreignIdFor(\App\Models\Partner::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
