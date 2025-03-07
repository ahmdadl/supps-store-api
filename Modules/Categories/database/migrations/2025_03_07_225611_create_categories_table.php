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
        Schema::create("categories", function (Blueprint $table) {
            $table->uid();
            $table->json("title");
            $table->json("description");
            $table->string("slug")->nullable();
            $table->boolean("is_main")->default(false);
            $table->json("image")->nullable();
            $table->activeState();
            $table->sortOrder();
            $table->metaTags();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("categories");
    }
};
