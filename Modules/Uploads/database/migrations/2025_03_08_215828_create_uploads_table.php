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
        Schema::create("uploads", function (Blueprint $table) {
            $table->uid();
            $table->string("name");
            $table->string("path");
            $table->string("type")->nullable();
            $table->unsignedBigInteger("size");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("uploads");
    }
};
