<?php

namespace App\Models;

use App\Traits\HasFormattedDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFormattedDates, SoftDeletes, HasFactory;


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
        'reply_to_message_id'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_forwarded' => 'boolean',
    ];


    /**
     * Accessors & Mutators
     */

    public function getMediaUrlAttribute($value)
    {
        // Ensure the media URL is always fully qualified
        return $value ? url($value) : null;
    }

    public function setMediaUrlAttribute($value)
    {
        // Optionally, handle storage path adjustments
        $this->attributes['media_url'] = $value ? basename($value) : null;
    }


    /**
     * Scopes
     */

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForwarded($query)
    {
        return $query->where('is_forwarded', true);
    }

    /**
     * Helper Methods
     */

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function markAsUnread()
    {
        $this->update(['is_read' => false]);
    }


    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function replyToMessage()
    {
        return $this->belongsTo(Message::class, 'reply_to_message_id');
    }

    public function forwardMessages()
    {
        return $this->hasMany(Message::class, 'reply_to_message_id');
    }

    /**
     * Get the media associated with the message.
     */
    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
