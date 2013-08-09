<?php

namespace HugoFlying\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use tmhOAuth;

class TmhOAuthServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['tmhoauth'] = $app->share(function () use ($app) {
            return new tmhOAuth($app['tmhoauth.config']);
        });
    }

    public function boot(Application $app)
    {
    }
}
