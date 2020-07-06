<?php

namespace App;

use App\Scopes\BuyerScope;

class Buyer extends User
{
    protected static function boot()
    {
        parent::boot(); // mains it has parent model that name is user
        static::addGlobalScope(new BuyerScope());
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
