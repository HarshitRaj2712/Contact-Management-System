<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'subject_type',
        'subject_id',
        'action',
        'changes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
