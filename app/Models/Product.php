<?php

namespace App\Models;

use App\Http\Resources\CategoryResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $table = "products";

    protected $fillable = ['title', 'description', 'short_description', 'price', 'sale_price', 'image', 'image_mime', 'image_size', 'category_id', 'manufacturer_name', 'hidden', 'featured', 'created_by', 'updated_by'];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
