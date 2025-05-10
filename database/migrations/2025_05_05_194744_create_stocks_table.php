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
        Schema::create('stocks', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->index('product_id');
            $table->integer('stock');
            $table->unique(['product_id', 'warehouse_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['warehouse_id']);
            $table->dropUnique('stocks_product_id_warehouse_id_unique');
        });
        Schema::dropIfExists('stocks');
    }
};
