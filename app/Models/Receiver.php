<?php

namespace App\Models;

use App\Traits\HasFormattedDates;
use MongoDB\Laravel\Eloquent\Model;

class Receiver extends Model
{

    use HasFormattedDates;
    protected $connection = 'mongodb';

    protected $table = 'receivers';

    protected $fillable = [
        'name',
        'photo',
        'phone',
        'email'
    ];
}
