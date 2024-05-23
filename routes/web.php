<?php

use Illuminate\Support\Facades\Route;
use Pderas\TwoFactor\Http\Controllers\TwoFactorAuthenticationController;

Route::middleware(config('2fa.middleware'))->prefix('/2fa')->name('2FA/')->group(function () {
    Route::get('/', [TwoFactorAuthenticationController::class, 'twoFactorPage'])->name('TwoFactorPage');
    Route::post('/', [TwoFactorAuthenticationController::class, 'verifyTwoFactorCode'])->name('VerifyTwoFactorCode');
    Route::post('/resend', [TwoFactorAuthenticationController::class, 'resendTwoFactorCode'])->name('ResendTwoFactorCode');
});