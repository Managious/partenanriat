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
        Schema::create('clients', function (Blueprint $table) {
             $table->id();
             $table->string('client_name', 50)->unique();
             $table->string('client_city', 50)->nullable();
             $table->string('client_zone', 50)->nullable();
             $table->string('client_type', 20)->nullable();
             $table->string('client_address', 100)->nullable();
             $table->string('client_email', 50)->nullable();
             $table->string('client_phone', 14)->nullable();
            $table->unsignedBigInteger('partner_id')->nullable();
             $table->integer('client_discount')->default(0);
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
        Schema::dropIfExists('clients');
    }
};
