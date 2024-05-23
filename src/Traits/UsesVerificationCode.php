<?php

namespace Pderas\TwoFactor\Traits;

use Pderas\TwoFactor\Models\UserVerificationCode;
use Pderas\TwoFactor\Notifications\TwoFactorAuthenticationNotification;
use Carbon\Carbon;

trait UsesVerificationCode
{
    /**
     * Generates new verification code and sends notification to user
     *
     * @param  string $via Channel to send notification through
     * @return void
     */
    public function send2faNotification()
    {
        $this->createUserVerificationCode();
        $this->notify(new TwoFactorAuthenticationNotification());
    }

    /**
     * Creates verification code
     *
     * @return string
     */
    public function createUserVerificationCode()
    {
        UserVerificationCode::create([
            'user_id' => $this->id,
            'code'    => $this->generateVerificationCode(),
        ]);
    }

    /**
     * Get the user's verification codes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function verificationCodes()
    {
        return $this->hasMany(UserVerificationCode::class);
    }

    /**
     * Gets most recent verification code entry
     *
     * @return string|null
     */
    public function getLatestVerificationCodeAttribute()
    {
        $latest = $this->verificationCodes()
            ->latest()
            ->first();

        return $latest ? $latest->code : null;
    }

    /**
     * Gets most recent valid verification code
     *
     * @return string|null
     */
    public function getLatestValidCodeAttribute()
    {
        $latest = $this->verificationCodes()
            ->where('updated_at', '>=', Carbon::now()->subSeconds($this->expireTime()))
            ->latest()
            ->first();

        return $latest ? $latest->code : null;
    }

    /**
     * Verify provided code and update user if valid
     *
     * @param  string $code
     * @return boolean
     */
    public function verifyTwoFactorCode($code)
    {
        if ($this->verify2faCode($code)) {
            $this->update([config('2fa.verified_date_column') => now()]);
            return true;
        }

        return false;
    }

    /**
     * Verify provided code matches latest valid code
     *
     * @param  string $code
     * @return boolean
     */
    public function verify2faCode($code)
    {
        return $code == $this->latestValidCode;
    }

    /**
     * Generates random code of digits
     *
     * @return string
     */
    private function generateVerificationCode()
    {
        $length = $this->getLength();
        $min = pow(10, ($length - 1));  // eg 100000
        $max = pow(10, $length) - 1;    // eg 999999

        $this->code = strval(random_int($min, $max));
        return $this->code;
    }

    /**
     * Length of generated code
     *
     * @return int
     */
    private function getLength()
    {
        return config('2fa.length', 6);
    }

    /**
     * How long the code is valid
     *
     * @return int
     */
    private function expireTime()
    {
        return config('2fa.expire', 5 * 60);
    }
}
