<?php

$app->get('/', function() use ($app) {
    $params['is_flying'] = false;
    $tweet = $app['tweet.inspector']->getRecentTweetAboutFlying();

    if ($tweet) {
        $oembed = $app['tweet.inspector']->getOembedForTweet($tweet, 550, 'center');
        $params['oembed_html'] = $oembed->html;
        $params['is_flying'] = true;
    }

    return $app['twig']->render('index.html.twig', $params);
});
