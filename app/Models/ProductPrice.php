<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProductPrice extends Model
{
    use HasFactory;

    protected $appends = [
        'curr_price'
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    protected function currPrice(): Attribute
    {
        $thousand = config('app.thousand_separator');
        $decimal = config('app.decimal_separator');
        return new Attribute(
            get: fn () => config('app.currency').' '.number_format($this->price, 2, $decimal, $thousand)
        );
    }
}
