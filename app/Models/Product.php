<?php

namespace Bpocallaghan\Titan\Models;

use Bpocallaghan\Sluggable\HasSlug;
use Bpocallaghan\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Bpocallaghan\Titan\Models\TitanCMSModel;
use Bpocallaghan\Titan\Models\Traits\Photoable;

/**
 * Class Product
 * @mixin \Eloquent
 */
class Product extends TitanCMSModel
{
    use SoftDeletes, HasSlug, Photoable;

    protected $table = 'products';

    protected $guarded = ['id'];

    public static $IMAGE_BACKGROUND = true;

    public static $LARGE_SIZE = [1600, 1600];

    public static $THUMB_SIZE = [300, 300];

    /**
     * Validation rules for this model
     */
    static public $rules = [
        'name'         => 'required|min:3:max:255',
        'reference'    => 'nullable|max:240',
        'amount'       => 'required|numeric',
        'in_stock'     => 'nullable',
        'content'      => 'required',
        'category_id'  => 'required|exists:product_categories,id',
    ];

    /**
     * Get the summary text
     *
     * @return mixed
     */
    public function getSummaryAttribute()
    {
        return substr(strip_tags($this->attributes['content']), 0, 120) . '...';
    }

    /**
     * Get the Category
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    /**
     * Get Category with his Parent
     * @return string
     */
    public function getCategoryAndParentAttribute()
    {
        if ($this->category) {
            if ($this->category->parent) {
                return $this->category->name . " ({$this->category->parent->name})";
            }

            return $this->category->name;
        }

        return '';
    }

    /**
     * Lazy load relationships
     * @param $query
     * @return mixed
     */
    public function scopeWithAll($query)
    {
        $query->with('photos');
        $query->with('format');
        $query->with('category');

        return $query;
    }

    /**
     * Filter on active (must have photos)
     * @param $query
     * @return mixed
     */
    public function scopeIsActive($query)
    {
        $query->whereHas('photos');

        return $query;
    }

    /**
     * Get the Checkout many to many
     */
    public function checkouts()
    {
        return $this->belongsToMany(Checkout::class);
    }

    /**
     * Get the Transaction many to many
     */
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class);
    }

    protected function getSlugOptions()
    {
        return SlugOptions::create()->generateSlugFrom('name');
    }
}