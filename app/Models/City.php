<?php

namespace Bpocallaghan\Titan\Models;

use Bpocallaghan\Sluggable\HasSlug;
use Bpocallaghan\Titan\Models\TitanCMSModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends TitanCMSModel
{
    use SoftDeletes, HasSlug;

    protected $table = 'cities';

    protected $guarded = ['id'];

    /**
     * Validation rules for this model
     */
    static public $rules = [
        'name'       => 'required|min:3:max:255',
        'province_id' => 'required|exists:provinces,id',
    ];

    /**
     * Get the province
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get all the rows as an array (ready for dropdowns)
     *
     * @return array
     */
    public static function getAllLists()
    {
        return self::orderBy('name')->get()->pluck('name', 'id')->toArray();
    }
}
