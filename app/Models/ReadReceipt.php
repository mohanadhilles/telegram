<?php

// app/Models/ReadReceipt.php
namespace App\Models;

use App\Traits\HasFormattedDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReadReceipt extends Model
{
    use SoftDeletes, HasFormattedDates, HasFactory;

    protected $fillable = [
        'message_id',
        'user_id',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
