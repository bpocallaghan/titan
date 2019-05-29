<?php

namespace Bpocallaghan\Titan\Models;

use Bpocallaghan\Sluggable\HasSlug;
use Bpocallaghan\Titan\Models\TitanCMSModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Continent
 * @mixin \Eloquent
 */
class Continent extends TitanCMSModel
{
    use SoftDeletes, HasSlug;

    protected $table = 'continents';

    protected $guarded = ['id'];

    /**
     * Validation rules for this model
     */
    static public $rules = [
        'name'       => 'required|min:3:max:255',
        'zoom_level' => 'nullable',
        'latitude'   => 'nullable',
        'longitude'  => 'nullable',
    ];

    /**
     * Get the provinces
     */
    public function countries()
    {
        return $this->hasMany(Country::class);
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
