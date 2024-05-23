<?php

namespace Pderas\TwoFactor\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Pderas\TwoFactor\Http\Requests\TwoFactor\VerifyCodeRequest;
use App\Providers\RouteServiceProvider;
use Inertia\Inertia;

class TwoFactorAuthenticationController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('throttle:1,0.3')->only('ResendTwoFactorCode');
        $this->middleware("throttle:{$this->maxVerifyAttempts()},1")->only('VerifyTwoFactorCode');
    }

    /**
     * Show page to enter 2FA code
     *
     * @return \Inertia\Response
     */
    public function twoFactorPage()
    {
        return Inertia::render('Auth/TwoFactorPage');
    }

    /**
     * Verify code
     * @param \Pderas\TwoFactor\Http\Requests\TwoFactor\VerifyCodeRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyTwoFactorCode(VerifyCodeRequest $request)
    {
        $user = $request->user();

        if ($user->verifyTwoFactorCode($request->code)) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return response()->vuex([
            'toast' => [
                'title'   => 'Error',
                'message' => 'Incorrect verification code.'
            ]
        ], 422);
    }

    /**
     * Resend the verification notification.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendTwoFactorCode()
    {
        try {
            request()->user()->send2faNotification();
        } catch (\Throwable $e) {
            \Log::error($e);
            return response()->vuex([
                'toast' => [
                    'title'   => 'Error',
                    'message' => 'Unable to resend verification code.'
                ]
            ], 500);
        }

        return response()->vuex([
            'toast' => [
                'title'   => 'Success',
                'message' => 'Verification code resent.'
            ]
        ]);
    }

    /**
     * Maximum number of attempts to verify code
     *
     * @return int
     */
    protected function maxVerifyAttempts()
    {
        return config('2fa.attempts', 6);
    }
}
