<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 2FA Enabled
    |--------------------------------------------------------------------------
    |
    | This option controls whether or not 2FA is enabled. If it is enabled,
    | users will be required to verify their identity via 2FA when logging in.
    | If it is disabled, users will be able to log in without 2FA.
    |
    */
    'enabled' => env('2FA_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | 2FA Verified Length
    |--------------------------------------------------------------------------
    |
    | This option controls the length of time (in hours) that a user is considered
    | verified for 2FA. If a user logs in within this time period, they will not
    | be required to verify their identity again.
    |
    */
    'verified_length' => 24,

    /*
    |--------------------------------------------------------------------------
    | 2FA Verified Column
    |--------------------------------------------------------------------------
    |
    | This option controls the name of the column that is used to store the
    | user's last login date. This column is used to determine whether or not
    | the user needs to be re-verified for 2FA.
    |
    */
    'verified_date_column' => 'last_login_at',

    /*
    |--------------------------------------------------------------------------
    | 2FA Attempts
    |--------------------------------------------------------------------------
    |
    | This option controls the maximum number of times a user can attempt to
    | verify their identity via 2FA before they are locked out of their account.
    |
    */
    'attempts' => 6,

    /*
    |--------------------------------------------------------------------------
    | 2FA Code Length
    |--------------------------------------------------------------------------
    |
    | This option controls the length of the verification code that is sent to
    | the user when they attempt to log in. The code will be a random string
    | of digits with the length specified here.
    |
    */
    'length' => 6,

    /*
    |--------------------------------------------------------------------------
    | 2FA Code Expiration
    |--------------------------------------------------------------------------
    |
    | This option controls the length of time that a verification code is valid
    | for. If the user attempts to verify their identity with a code that has
    | expired, they will be required to request a new code.
    |
    */
    'expire' => 60 * 5,

    /*
    |--------------------------------------------------------------------------
    | 2FA Notification
    |--------------------------------------------------------------------------
    |
    | This option controls the notification class that is used to send the
    | verification code to the user. You can create your own notification
    | class and replace the default one here.
    |
    */
    'notification' => Pderas\TwoFactor\Notifications\TwoFactorAuthenticationNotification::class,

    /*
    |--------------------------------------------------------------------------
    | 2FA Middleware
    |--------------------------------------------------------------------------
    |
    | This option controls the middleware that is applied to the 2FA routes.
    | You can change this to any middleware you want, but it is recommended
    | that you use the 'web' middleware to ensure that the user is logged in.
    |
    */
    'middleware' => 'web',
];
