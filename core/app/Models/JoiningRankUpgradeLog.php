<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoiningRankUpgradeLog extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id', 'rank'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
