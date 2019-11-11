<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Transaction extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'date', 'amount', 'text', 'balance', 'tags'
    ];

    public function account()
    {
        return $this->belongsTo(CustomerAccount::class);
    }
}

