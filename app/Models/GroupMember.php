<?php

// app/Models/GroupMember.php
namespace App\Models;

use App\Traits\HasFormattedDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{

    use HasFormattedDates, HasFactory;
    protected $fillable = [
        'group_id',
        'user_id',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
