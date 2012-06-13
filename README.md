# hhamon-flying

A silly [Silex][1] application to track [hhamon][2]'s flight status.

## Setup

### Install Dependencies

    $ composer.phar install

### Cache Directory

Ensure the `cache/` directory is writable by your web server.

### Configuration

The `src/` directory includes a `config.php.dist` file, which should be copied
to `config.php` and populated with your Twitter API credentials.

  [1]: http://silex.sensiolabs.org/
  [2]: http://twitter.com/hhamon
