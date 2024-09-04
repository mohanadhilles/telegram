<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForwardedMessage extends Model
{

    use HasFactory;
    protected $fillable = ['original_message_id', 'forwarded_to_user_id'];

    public function originalMessage()
    {
        return $this->belongsTo(Message::class, 'original_message_id');
    }

    public function forwardedToUser()
    {
        return $this->belongsTo(User::class, 'forwarded_to_user_id');
    }

    /**
     * Helper Methods
     */

    public function isForwardedTo($userId)
    {
        return $this->forwarded_to_user_id === $userId;
    }

}
