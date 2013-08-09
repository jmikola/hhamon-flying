<?php

namespace HugoFlying\Silex\Provider;

use HugoFlying\TweetInspector;
use Silex\Application;
use Silex\ServiceProviderInterface;

class TweetInspectorServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['tweet.inspector'] = $app->share(function () use ($app) {
            return new TweetInspector(
                $app['tmhoauth'],
                $app['tweet.inspector.ttl']
            );
        });
    }

    public function boot(Application $app)
    {
    }
}
