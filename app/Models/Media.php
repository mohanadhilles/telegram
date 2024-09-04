<?php

namespace App\Models;

use App\Traits\HasFormattedDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory, SoftDeletes,HasFormattedDates;
    protected $fillable = ['type', 'url', 'code', 'description'];


    // Other model properties and methods

    /**
     * Get the messages associated with the media.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }


}
