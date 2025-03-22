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
        Schema::create("cart_items", function (Blueprint $table) {
            $table->uid();
            $table->foreignUlid("cart_id")->constrained()->cascadeOnDelete();
            $table->foreignUlid("product_id")->constrained()->cascadeOnDelete();
            $table->integer("quantity")->default(1);
            $table->json("totals")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("cart_items");
    }
};
