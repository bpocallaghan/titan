<?php

namespace Bpocallaghan\Titan\Models;

use Bpocallaghan\Sluggable\HasSlug;
use Bpocallaghan\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Bpocallaghan\Titan\Models\Traits\RecursiveParent;

/**
 * Class ProductCategory
 * @mixin \Eloquent
 */
class ProductCategory extends TitanCMSModel
{
    use SoftDeletes, HasSlug, RecursiveParent;

    protected $table = 'product_categories';

    protected $guarded = ['id'];

    /**
     * Validation rules for this model
     */
    static public $rules = [
        'name'      => 'required|min:3:max:255',
        'slug'      => 'nullable',
        'url'       => 'nullable',
        'parent_id' => 'nullable',
    ];

    /**
     * Get the url from db
     * If true given, we generate a new one,
     * This us usefull if parent_id updated, etc
     *
     * @return \Eloquent
     */
    public function updateUrl()
    {
        $this->url = '';
        $this->generateCompleteUrl($this);
        $this->url = $this->url;

        if (strlen($this->slug) > 1) {
            $this->url .= "/{$this->slug}";
        }

        $this->url = '/shop' . $this->url;

        return $this;
    }

    /**
     * Generate the new nav based on parent_id
     *
     * @param $nav
     * @return \Illuminate\Support\Collection|static
     */
    private function generateCompleteUrl($nav)
    {
        $row = self::find($nav->parent_id);

        if ($row) {
            if (strlen($row->slug) > 1) {
                $this->url = "/{$row->slug}" . ("{$this->url}");
            }

            return $this->generateCompleteUrl($row);
        }

        return $row;
    }

    /**
     * Get all the rows as an array (ready for dropdowns)
     *
     * @return array
     */
    public static function getAllList()
    {
        return self::orderBy('name')->get()->pluck('name_parent', 'id')->toArray();
    }

    public static function getMainList($addAll = false)
    {
        $items = self::where('parent_id', 0)->get();
        if ($addAll) {
            $items->push((object) ['id' => 'all', 'name_parent' => ' All']);
        }

        $items = $items->sortBy('name_parent')->pluck('name_parent', 'id')->toArray();

        return $items;
    }

    public function getNameParentAttribute()
    {
        return $this->name . ($this->parent_id > 0 ? " ({$this->parent->name})" : '');
    }

    /**
     * Get the products
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}