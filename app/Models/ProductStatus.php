<?php

namespace Bpocallaghan\Titan\Models;

use Bpocallaghan\Sluggable\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProductStatus
 * @mixin \Eloquent
 */
class ProductStatus extends TitanCMSModel
{
    use SoftDeletes, HasSlug;

    protected $table = 'product_statuses';

    protected $guarded = ['id'];
    
    /**
     * Validation rules for this model
     */
    static public $rules = [
    	'name' => 'required|min:3:max:255',
    	'category' => 'required|min:3:max:255',
    ];

    public function getBadgeAttribute()
    {
        return "<span class='label label-{$this->category}'>{$this->name}</span>";
        //return "<span class='label label-{$this->category} badge badge-{$this->category}'>{$this->name}</span>";
    }
    
    /**
     * Get all the rows as an array (ready for dropdowns)
     *
     * @return array
     */
    public static function getAllList()
    {
    	return self::orderBy('name')->get()->pluck('name', 'id')->toArray();
    }
}