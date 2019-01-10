<?php

namespace Bpocallaghan\Titan\Models;

use Illuminate\Database\Eloquent\Model;
use Bpocallaghan\Titan\Models\TitanCMSModel;

/**
 * Class Setting
 * @mixin \Eloquent
 */
class Settings extends TitanCMSModel
{

    protected $table = 'settings';

    protected $guarded = ['id'];

    /**
     * Validation rules for this model
     */
    static public $rules = [
        'name'        => 'required|min:3:max:255',
        'slogan'      => 'nullable',
        'description' => 'required|min:3:max:2000',
        'keywords'    => 'nullable',
        'author'      => 'nullable',

        // contact
        'email'       => 'nullable',
        'cellphone'   => 'nullable',
        'telephone'   => 'nullable',
        'address'     => 'nullable',
        'po_box'      => 'nullable',

        // social media
        'facebook'    => 'nullable',
        'twitter'     => 'nullable',
        'googleplus'  => 'nullable',
        'linkedin'    => 'nullable',
        'youtube'     => 'nullable',
        'instagram'   => 'nullable',

        // google maps
        'zoom_level'  => 'nullable',
        'latitude'    => 'nullable',
        'longitude'   => 'nullable',
    ];

    static public $messages = [];
}