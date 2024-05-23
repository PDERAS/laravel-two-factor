<?php

namespace Pderas\TwoFactor\Models;

use Illuminate\Database\Eloquent\Model;

class UserVerificationCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'code',
    ];
}
