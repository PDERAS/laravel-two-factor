<?php

namespace Pderas\TwoFactor\Traits;

use Illuminate\Support\Facades\Auth;

trait ChecksTwoFactorVerified
{
    /**
     * Load whether user has been two factor verified
     *
     * @return bool
     */
    public function verified()
    {
        return Auth::check() ? Auth::user()->is2faVerified() : false;
    }
}