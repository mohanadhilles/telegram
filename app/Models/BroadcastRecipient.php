<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BroadcastRecipient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['broadcast_message_id', 'user_id', 'is_read'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(BroadcastMessage::class);
    }

}
