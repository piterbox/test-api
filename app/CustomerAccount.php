<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class CustomerAccount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'number', 'holder', 'bsb', 'balance', 'available', 'type', 'customer_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getTransactionsByDaysAgo($count)
    {
        return $this->transactions()->whereBetween('date', [Carbon::now(), Carbon::now()->subDays($count)]);
    }


}
