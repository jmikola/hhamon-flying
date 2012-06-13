# hhamon-flying

A silly [Silex][1] application to track [hhamon][2]'s flight status.

## Setup

### Install Dependencies

    $ composer.phar install

### Configuration

The `src/` directory includes a `config.php.dist` file, which should be copied
to `config.php` and populated with your Twitter API credentials.

Additionally, the cache directory and TTL options can be customized.

### Cache Directory

Create the cache directory (`cache/` by default) and ensure it is writable by
your web server.

  [1]: http://silex.sensiolabs.org/
  [2]: http://twitter.com/hhamon
