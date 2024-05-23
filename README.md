# A Two Factor Authentication Package for Laravel
This package provides a customizable way to email two factor authentication codes

# Installation
Install via composer 
```
composer require pderas/laravel-two-factor
```

## Publish files
### Publish config and view files
```
php artisan vendor:publish --tag=two-factor
```

### (Optional) Publish Migrations
```
php artisan vendor:publish --tag=two-factor-migrations
```

## Run Migrations
```
php artisan migrate
```

# Usage
Modify your User model to include this trait
```php
use Pderas\TwoFactor\Traits\UsesTwoFactorAuthentication;

class User extends Authenticatable
{
    use UsesTwoFactorAuthentication;
}
```

Add this to `App\Http\Kernel.php` to use as route middleware
```php
protected $middlewareAliases = [
    'two_factor' => \Pderas\TwoFactor\Http\Middleware\TwoFactorAuthentication::class,
];
```

Then add this middleware to any routes that need to be protected by 2FA
```php
Route::middleware(['auth', 'two_factor'])->group(function () {
    // ...
}
```

Add/edit the `authenticated` method of `LoginController`
```php
protected function authenticated(Request $request, $user)
{
    if ($user->requiresTwoFactorAuth()) {
        $user->send2faNotification();
        return redirect()->route('2FA/TwoFactorPage');
    }

    return redirect($this->redirectTo);
}
```

### Note: _The following is based off of the PDERAS vuex/vue 2 pattern_

Add to `UserModuleLoader`
```php
use Pderas\TwoFactor\Traits\ChecksTwoFactorVerified;

class UserModuleLoader extends VuexLoader
{
    use ChecksTwoFactorVerified;
    // ...
}
```

Add `verified` attribute to user vuex module `user.js`
```js
const getDefaultState = () => ({
    // ...
    verified: false,
});
```

Lazy load the user's `verified` attribute in `PopulateVuexStore` middleware
```php
public function handle($request, Closure $next)
{
    if (Auth::check()) {
        Vuex::lazyLoad('user', [
            'verified',
        ]);
    }
}
```

Ensure navigation is hidden when user is logged in, but not verified in `MainLayout.vue`
```js
<template>
<div>
    <div>
    <template v-if="verified">
        <div v-if="isSideNavLayout" class="w-full lg:w-64">
            <AdminSideNav />
        </div>
        <div v-else class="w-full bg-white sticky top-0 z-10">
            <div class="mx-auto container px-4">
                <SideNav />
            </div>
        </div>
    </template>
    </div>
    <!-- -->
</div>
</template>
    
<script>
export default {
    computed: {
        ...mapState('user', [
            'verified',
        ]),
    }
}
```

## Disabling
A useful env variable can be added to disable this functionality locally
```env
2FA_ENABLED=false
```