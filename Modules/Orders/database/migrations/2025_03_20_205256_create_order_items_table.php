<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("order_items", function (Blueprint $table) {
            $table->uid();
            $table->foreignId("order_id")->constrained();
            $table->foreignId("product_id")->constrained();
            $table->json("product_title");
            $table->string("product_sku", 255)->nullable();
            $table->integer("quantity");
            $table->json("totals");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("order_items");
    }
};
