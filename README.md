# catGif-wordpress-plugin
Plugin that allows users to send cat gifs as comments.

Allows users to send a cat gif that corresponds to a given comment in a blog.

## Usage
Put the plugin repo folder in your `/opt/lampp/htdocs/wordpress/wp-content/plugins/catGif` directory and activate in your WordPress Plugin settings.

## Installation for the giphy php client

install composer

instructions
*https://www.suvashsumon.xyz/post/install-xampp-composer-on-ubuntu/*

In the plugin folder it's the giphy php client repository.
inside the giphy php client folder execute

```bash
composer require giphy/giphy-php-client
```
repository  *https://github.com/Giphy/giphy-php-client*


For documentation about hooks and functions use reference https://developer.wordpress.org/reference/hooks/.

