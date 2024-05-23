<?php

namespace Pderas\TwoFactor\Http\Middleware;

use Closure;

class TwoFactorAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('2fa.enabled') && !$this->isVerified($request)) {
            return redirect()->route('2FA/TwoFactorPage');
        }
        return $next($request);
    }

    /**
     * Check if user has been 2fa verified
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isVerified($request)
    {
        return $request->user()?->is2faVerified() ?? false;
    }
}
