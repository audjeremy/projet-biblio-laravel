<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    if (!Schema::hasTable('orders')) {
      Schema::create('orders', function (Blueprint $t) {
        $t->id();
        $t->foreignId('user_id')->constrained()->cascadeOnDelete();
        $t->string('currency', 8)->default('CAD');
        $t->decimal('subtotal', 10, 2)->default(0);
        $t->decimal('discount', 10, 2)->default(0);
        $t->decimal('gst', 10, 2)->default(0);
        $t->decimal('qst', 10, 2)->default(0);
        $t->decimal('shipping', 10, 2)->default(0);
        $t->decimal('total', 10, 2)->default(0);
        $t->string('provider', 32)->nullable();                // 'stripe' | 'paypal'
        $t->string('provider_session_id', 128)->nullable();    // Stripe session / PayPal order id
        $t->string('provider_payment_intent', 128)->nullable();// Stripe PI / PayPal capture id
        $t->string('status', 32)->default('paid');             // paid/refunded/...
        $t->json('meta')->nullable();
        $t->timestamps();
      });
    }

    if (!Schema::hasTable('order_items')) {
      Schema::create('order_items', function (Blueprint $t) {
        $t->id();
        $t->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
        $t->foreignId('book_id')->nullable()->constrained('books')->nullOnDelete();
        $t->string('title');
        $t->string('author')->nullable();
        $t->integer('quantity')->default(1);
        $t->decimal('unit_price', 10, 2)->default(0);
        $t->decimal('line_total', 10, 2)->default(0);
        $t->timestamps();
      });
    }
  }

  public function down(): void {
    Schema::dropIfExists('order_items');
    Schema::dropIfExists('orders');
  }
};