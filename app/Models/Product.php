<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $appends = [
        'thumbnail'
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
}
