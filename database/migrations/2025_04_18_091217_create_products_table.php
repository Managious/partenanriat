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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name',100);
            $table->string('product_brand',50)->nullable();
            $table->string('product_category',50)->nullable();
            $table->string('product_subcategory',50)->nullable();
            $table->string('product_supplier',20);
            $table->float('product_cmup');
            $table->float('product_price');
            $table->string('product_location',20)->nullable();
            $table->string('product_ean_code',50)->nullable();
            $table->float('product_stock_1');
            $table->string('product_image_link')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
