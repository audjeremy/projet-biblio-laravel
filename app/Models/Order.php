<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model {
  protected $fillable = [
    'user_id','currency','subtotal','discount','gst','qst','shipping','total',
    'provider','provider_session_id','provider_payment_intent','status','meta'
  ];
  protected $casts = ['meta' => 'array'];
  public function items(): HasMany { return $this->hasMany(OrderItem::class); }
  public function user(): BelongsTo { return $this->belongsTo(User::class); }
}

// app/Models/OrderItem.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model {
  protected $fillable = ['order_id','book_id','title','author','quantity','unit_price','line_total'];
  public function order(): BelongsTo { return $this->belongsTo(Order::class); }
  public function book(): BelongsTo { return $this->belongsTo(Book::class); }
}