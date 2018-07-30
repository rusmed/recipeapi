<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {

            if ($request->header('api_token')) {
                $user = User::where('token', $request->header('api_token'))->first();

                if (!is_object($user)) {
                    return null;
                }

                $lifetime = env('TOKEN_LIFETIME');
                $expired = time() - $lifetime;

                return $expired > strtotime($user->token_expired) ? null : $user;
            }

            return null;

        });
    }
}
