<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyThread extends Model
{

    use HasFactory;
    protected $fillable = ['message_id', 'reply_message_id'];

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    public function replyMessage()
    {
        return $this->belongsTo(Message::class, 'reply_message_id');
    }

}
