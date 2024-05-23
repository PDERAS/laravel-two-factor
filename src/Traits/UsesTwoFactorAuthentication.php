<?php

namespace Pderas\TwoFactor\Traits;

use Carbon\Carbon;

trait UsesTwoFactorAuthentication
{
    use UsesVerificationCode;

    /**
     * Boot the UsesTwoFactorAuthentication trait for a model.
     *
     * @return void
     */
    public function initializeUsesTwoFactorAuthentication()
    {
        $date_column = config('2fa.verified_date_column');

        // Add verified_date_column property to the model's fillable array
        $this->fillable(array_merge($this->getFillable(), [$date_column]));

        // Cast verified_date_column to a datetime
        $this->mergeCasts([$date_column => 'datetime']);

    }

    /**
     * Check whether user requires two factor authentication
     *
     * @return bool
     */
    public function requiresTwoFactorAuth()
    {
        return config('2fa.enabled') && $this->verificationExpired();
    }

    /**
     * Check whether user has been verified for two factor authentication
     *
     * @return bool
     */
    public function is2faVerified()
    {
        return !config('2fa.enabled') || !$this->verificationExpired();
    }

    /**
     * Check whether user's last login was within the verification time frame
     *
     * @return bool
     */
    private function verificationExpired()
    {
        $last_login = $this->{config('2fa.verified_date_column')};

        if (!$last_login) {
            return true;
        }

        // Check if the current time is past the expiry time
        $expiry_time = $last_login->clone()->addHours(config('2fa.verified_length'));
        return now()->gte($expiry_time);
    }
}
