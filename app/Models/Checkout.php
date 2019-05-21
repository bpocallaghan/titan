<?php

namespace Bpocallaghan\Titan\Models;

use App\User;
use Bpocallaghan\Titan\Models\TitanCMSModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Checkout
 * @mixin \Eloquent
 */
class Checkout extends TitanCMSModel
{
    use SoftDeletes;

    protected $table = 'checkouts';

    protected $guarded = ['id'];

    /**
     * Get the Product many to many
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transaction
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}