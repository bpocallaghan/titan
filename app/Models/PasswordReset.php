<?php

namespace Bpocallaghan\Titan\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordReset extends Model
{
    protected $table = 'password_resets';

    protected $guarded = ['id'];

    public function setUpdatedAtAttribute($value)
    {
        //
    }
}