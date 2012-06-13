<?php

use HugoFlying\Silex\Provider\TweetInspectorServiceProvider;
use Kud\Silex\Provider\TmhOAuthServiceProvider;
use Silex\Application;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Response;

$loader = require_once __DIR__.'/../vendor/autoload.php';
$loader->add('HugoFlying', __DIR__);

$app = new Application();

require_once __DIR__.'/config.php';

$app->register(new HttpCacheServiceProvider());

$app->register(new TmhOAuthServiceProvider());

$app->register(new TweetInspectorServiceProvider());

$app->register(new TwigServiceProvider(), array(
    'twig.options' => array('cache' => $app['twig.cache_dir']),
    'twig.path'    => __DIR__.'/views',
));

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $error = 404 == $code ? $e->getMessage() : null;

    return new Response($app['twig']->render('error.html.twig', array('error' => $error)), $code);
});

require_once __DIR__.'/controllers.php';

return $app;
