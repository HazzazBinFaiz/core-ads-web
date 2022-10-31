<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentCommissionLog extends Model
{
    const UPDATED_AT = null;
    
    protected $fillable = [
        'user_id', 'amount', 'quantity'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
