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
            $table->bigInteger('invoice_number');
            $table->string('invoice_nature',20);
            $table->dateTime('invoice_date');
            $table->string('invoice_sale_point',50);
            $table->string('ordered_by',100)->nullable();
            $table->dateTime('ordered_on')->nullable();
            $table->string('invoiced_by',100);
            $table->dateTime('invoiced_on');
            $table->string('delivered_by',100)->nullable();
            $table->dateTime('delivered_on')->nullable();
            $table->string('payment_type',20)->nullable();
            $table->float('payment_amount')->nullable();
            $table->string('client_id',20);
            $table->string('product_id',20);
            $table->float('product_sale_price');
            $table->float('product_discount_1');

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
