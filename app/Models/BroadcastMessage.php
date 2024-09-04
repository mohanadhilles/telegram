<?php

// app/Models/BroadcastMessage.php
namespace App\Models;

use App\Traits\HasFormattedDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BroadcastMessage extends Model
{
    use SoftDeletes, HasFormattedDates, HasFactory;

    protected $fillable = [
        'sender_id',
        'content',
        'media_type',
        'media_url',
    ];


    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipients()
    {
        return $this->hasMany(BroadcastRecipient::class);
    }
}
