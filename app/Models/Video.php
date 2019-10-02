<?php

namespace Bpocallaghan\Titan\Models;


use Bpocallaghan\Titan\Models\Traits\ImageThumb;
use Bpocallaghan\Titan\Models\Traits\ModifyBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Photo
 * @mixin \Eloquent
 */
class Video extends TitanCMSModel
{
    use SoftDeletes, ImageThumb;

    protected $table = 'videos';

    protected $guarded = ['id'];

    public static $LARGE_SIZE = [1024, 593];

    public static $THUMB_SIZE = [800, 450];

    static public $rules = [
        'name'          => 'required',
        'link'          => 'required',
        'content'       => 'nullable',
        'photo'         => 'nullable|image|max:6000|mimes:jpg,jpeg,png,bmp',
        'is_cover'      => 'nullable',
        'videoable_id'  => 'required',
        'videoable_type'=> 'required',
    ];


    public function videoable()
    {
        return $this->morphTo();
    }

}