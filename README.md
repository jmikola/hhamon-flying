# hhamon-flying

A silly [Silex][1] application to track [hhamon][2]'s flight status.

## Setup

### Install Dependencies

    $ composer.phar install

### Configuration

The `src/` directory includes a `config.php.dist` file, which should be copied
to `config.php` and populated with your Twitter API credentials.

Additional options, such as cache directories and TTL options, can also be
customized.

### Cache Directory

By default, the application will use `hhamon-flying/` within the system's
temporary directory. This path, which must be writable, may be customized via
the `http_cache.cache_dir` and `twig.cache_dir` configuration options.

### Web Server

The application can be started using:

    $ php -S localhost:8080 -t web web/index.php

Instructions for other web server configurations are outlined in the
[Silex documentation][8].

  [1]: http://silex.sensiolabs.org/
  [2]: http://twitter.com/hhamon
  [8]: http://silex.sensiolabs.org/doc/web_servers.html
