<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Récap
            $table->string('currency', 8)->default('CAD');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('gst', 10, 2)->default(0);
            $table->decimal('qst', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            // Paiement (Stripe/PayPal)
            $table->string('provider')->index(); // 'stripe' | 'paypal'
            $table->string('provider_session_id')->nullable()->index(); // ex Stripe Checkout Session
            $table->string('provider_payment_intent')->nullable()->index(); // ex Stripe PaymentIntent

            // Statut
            $table->string('status')->default('paid'); // paid|pending|failed|refunded

            // Optionnel: garder un snapshot JSON
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
