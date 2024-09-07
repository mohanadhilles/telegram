<?php

namespace App\Models;

use App\Traits\HasFormattedDates;
use MongoDB\Laravel\Eloquent\Model;

class Chat extends Model
{

    use HasFormattedDates;
    /**
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var string
     */
    protected $table = 'chats';
//    protected $primaryKey = '_id';


    /**
     * @var string[]
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'group_id',
        'media_id',
        'content',
        'recipient_token',
        'media_type',
        'media_url',
        'is_read',
        'is_forwarded',
        'reply_to_message_id',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true;

    public function receiver()
    {
        return $this->belongsTo(Receiver::class, 'receiver_id','receiver_id');
    }
}
