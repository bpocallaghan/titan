<?php

namespace Bpocallaghan\Titan\Models;

use Bpocallaghan\Sluggable\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;
use Bpocallaghan\Titan\Models\TitanCMSModel;

class Province extends TitanCMSModel
{
    use SoftDeletes, HasSlug;

    protected $table = 'provinces';

    protected $guarded = ['id'];

    /**
     * Validation rules for this model
     */
    static public $rules = [
        'name'       => 'required|min:3:max:255',
        'country_id' => 'required|exists:countries,id',
    ];

    /**
     * Get the Country
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
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
