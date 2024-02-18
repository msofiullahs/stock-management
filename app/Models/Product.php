<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $appends = [
        'thumbnail',
        'label',
        'available_stock',
        'stock_out',
        'stock_in',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_suppliers');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'product_id', 'id');
    }

    // public function categoryObjects()
    // {
    //     return $this->belongsToMany(Category::class, 'product_categories')->select('categories.id');
    // }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    protected function getThumbnailAttribute()
    {
        if (!empty($this->getMedia('images')->first())) {
            return $this->getMedia('images')->first()->getUrl('preview');
        }
        return url('assets/no-img.jpg');
    }

    protected function label(): Attribute
    {
        return new Attribute(
            get: fn () => $this->product_code.' - '.$this->product_name
        );
    }

    public function getAvailableStockAttribute()
    {
        $in = $this->hasMany(Stock::class, 'product_id', 'id')->where('stock_type', 'in')->sum('numb_of_stock');
        $out = $this->hasMany(Stock::class, 'product_id', 'id')->where('stock_type', 'out')->sum('numb_of_stock');
        $total = $in - $out;

        return $total;
    }

    public function getStockOutAttribute()
    {
        $out = $this->hasMany(Stock::class, 'product_id', 'id')->where('stock_type', 'out')->sum('numb_of_stock');

        return $out;
    }

    public function getStockInAttribute()
    {
        $in = $this->hasMany(Stock::class, 'product_id', 'id')->where('stock_type', 'in')->sum('numb_of_stock');

        return $in;
    }

    public function inStocks()
    {
        return $this->hasMany(Stock::class, 'product_id', 'id')->where('stock_type', 'in');
    }

    public function outStocks()
    {
        return $this->hasMany(Stock::class, 'product_id', 'id')->where('stock_type', 'out');
    }

    public function stockDate()
    {
        return $this->hasMany(Stock::class, 'product_id', 'id')->groupBy(DB::raw('Date(tms_booking.created_at)'));
    }
}
