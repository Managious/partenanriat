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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->string('supplier_id',20)->primary();
            $table->string('supplier_name',50)->unique();
            $table->string('supplier_city',50)->nullable();
            $table->string('supplier_zone',50)->nullable();
            $table->string('supplier_type',20)->nullable();
            $table->string('supplier_address',100)->nullable();
            $table->string('supplier_email',50)->nullable();
            $table->string('supplier_phone',14)->nullable();
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
        Schema::dropIfExists('suppliers');
    }
};
