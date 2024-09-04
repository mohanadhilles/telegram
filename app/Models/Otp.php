<?php

namespace App\Models;

use App\Traits\HasFormattedDates;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{

    use HasFormattedDates;
    protected $fillable = ['mobile_number', 'otp', 'expires_at'];

    public $timestamps = true;

}
