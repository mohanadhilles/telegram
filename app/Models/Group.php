<?php

// app/Models/Group.php
namespace App\Models;

use App\Traits\HasFormattedDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes, HasFormattedDates, HasFactory;

    protected $fillable = [
        'name',
        'admin_id',
        'description',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function members()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
