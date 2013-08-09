<?php

use Symfony\Component\HttpFoundation\Response;

$app->get('/', function() use ($app) {
    $params['is_flying'] = false;
    $tweet = $app['tweet.inspector']->getRecentTweetAboutFlying();

    if ($tweet) {
        $oembed = $app['tweet.inspector']->getOembedForTweet($tweet, 500, 'center');
        $params['oembed_html'] = $oembed->html;
        $params['is_flying'] = true;
    }

    $response = new Response($app['twig']->render('index.html.twig', $params));
    $response->setTtl($app['http_cache.ttl']);

    return $response;
});
