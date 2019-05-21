<?php

namespace Bpocallaghan\Titan\Models;

use Bpocallaghan\Titan\Models\TitanCMSModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Testimonial
 * @mixin \Eloquent
 */
class Testimonial extends TitanCMSModel
{
    use SoftDeletes;

    protected $table = 'testimonials';

    protected $guarded = ['id'];

    /**
     * Validation rules for this model
     */
    static public $rules = [
        'customer'    => 'required|min:3:max:255',
        'description' => 'required',
    ];
}