<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');

            // snapshot pour historiser même si le livre change après
            $table->string('title');
            $table->string('author')->nullable();

            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2); // prix au moment de l’achat
            $table->decimal('line_total', 10, 2); // quantity * unit_price

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
