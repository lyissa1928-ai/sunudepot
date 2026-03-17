<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Aggregated Order Items (Pivot): Links multiple RequestItems to a single AggregatedOrder
     * Core of the Federation concept - tracks which campus request line is fulfilled by which PO line
     * Enables: "This item from Campus A RequestLine #5 is part of SupplierOrder #123"
     */
    public function up(): void
    {
        Schema::create('aggregated_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aggregated_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('request_item_id')->constrained()->onDelete('restrict');
            $table->integer('quantity_ordered');
            $table->integer('quantity_received')->default(0);
            $table->decimal('unit_price', 12, 2);
            $table->timestamps();
            $table->softDeletes();

            // Composite unique: prevent duplicate line items in same order
            $table->unique(['aggregated_order_id', 'request_item_id']);
            $table->index(['aggregated_order_id', 'request_item_id']);
            $table->index('request_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aggregated_order_items');
    }
};
