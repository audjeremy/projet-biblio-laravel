<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'category',
        'year',
        'summary',
        'price',
        'discount', // ← discount ajouté
    ];

    /** Prix remisé = price - discount (ne descend jamais sous 0) */
    public function getDiscountedPriceAttribute(): float
    {
        $price    = (float) $this->price;
        $discount = (float) ($this->discount ?? 0);
        return max(0, round($price - $discount, 2));
    }

    /** Indique si le livre est en promo */
    public function getIsOnSaleAttribute(): bool
    {
        return (float) ($this->discount ?? 0) > 0 && $this->discounted_price < (float) $this->price;
    }

    /** % d’économie (arrondi) */
    public function getSavePercentAttribute(): ?int
    {
        if (!$this->is_on_sale || (float)$this->price <= 0) return null;
        return (int) round(($this->discount / $this->price) * 100);
    }
}
