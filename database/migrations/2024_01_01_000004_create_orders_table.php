<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address');
            $table->decimal('total', 10, 2);
            $table->enum('status', ['new', 'processing', 'delivered', 'cancelled'])->default('new');
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->string('product_name'); // зберігаємо назву на момент замовлення
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
