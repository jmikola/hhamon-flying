<?php

namespace HugoFlying;

use Themattharris\TmhOAuth;
use Themattharris\TmhUtilities;

class TweetInspector
{
    const CACHE_TWEETS = 'tweet_inspector.tweets';
    const CACHE_OEMBED = 'tweet_inspector.oembed';

    private $client;
    private $pattern;
    private $transliterator;
    private $ttl;
    private $utils;

    private $words = array(
        'aboard', 'a bord',
        'aero',
        'airplane', 'avion',
        'airline', 'aerienne',
        'baggage', 'luggage', 'bagage',
        'fly', 'en vol', 'volant', 'voler',
        'flight', 'mon vol',
        'landed', 'a atterri',
        'landing', 'atterrissage', 'debarquement',
        'leg room',
        'takeoff', 'taking off', 'decollage',
    );

    public function __construct(TmhOAuth $client, TmhUtilities $utils, $ttl)
    {
        $this->client = $client;
        $this->utils = $utils;
        $this->ttl = $ttl;

        $words = array_map(function($word) { return preg_quote($word, '/'); }, $this->words);
        $this->pattern = '/'.implode('|', $words).'/';

        $this->transliterator = \Transliterator::create('ASCII-Latin', \Transliterator::FORWARD);
    }

    public function getRecentTweetAboutFlying()
    {
        $tweets = $this->getRecentTweets();

        foreach ($tweets as $tweet) {
            if ($this->isTweetAboutFlying($tweet)) {
                return $tweet;
            }
        }

        return null;
    }

    public function getOembedForTweet($tweet, $maxWidth = null, $align = null)
    {
        $key = static::CACHE_OEMBED.'.'.md5($tweet->id_str.$maxWidth.$align);
        $oembed = apc_fetch($key, $success);

        if ($success) {
            return $oembed;
        }

        $params = array(
            'id' => $tweet->id_str,
            'hide_media' => false,
            'hide_thread' => true,
            'lang' => 'en',
            'omit_script' => false,
        );

        if (isset($maxWidth)) {
            $params['maxwidth'] = (int) $maxWidth;
        }

        if (isset($align)) {
            $params['align'] = $align;
        }

        $this->client->request('GET', $this->client->url('1/statuses/oembed'), $params);

        $response = $this->client->response;

        if (200 != $response['code']) {
            throw new \RuntimeException(sprintf('Twitter API returned HTTP %s. %s (errno: %s)', $response['code'], $response['error'], $response['errno']));
        }

        $oembed = json_decode($response['response']);

        apc_store($key, $oembed);

        return $oembed;
    }

    private function isTweetAboutFlying($tweet)
    {
        $text = $this->transliterator->transliterate($tweet->text);

        if (preg_match($this->pattern, $text)) {
            return true;
        }

        return false;
    }

    private function getRecentTweets()
    {
        $tweets = apc_fetch(static::CACHE_TWEETS, $success);

        if ($success) {
            return $tweets;
        }

        $this->client->request('GET', $this->client->url('1/statuses/user_timeline'), array(
            'screen_name' => 'hhamon',
            'trim_user' => true,
            'exclude_replies' => true,
            'include_rts' => false,
            'include_entities' => true,
            'count' => 25,
            //'since_id' => 
        ));

        $response = $this->client->response;

        if (200 != $response['code']) {
            throw new \RuntimeException(sprintf('Twitter API returned HTTP %s. %s (errno: %s)', $response['code'], $response['error'], $response['errno']));
        }

        $tweets = json_decode($response['response']);

        apc_store(static::CACHE_TWEETS, $tweets, $this->ttl);

        return $tweets;
    }
}
