# catGif-wordpress-plugin
Plugin that allows users to send cat gifs as comments.

When the user post their comment with the "Send Cat Gif" the pluging searches in giphy for
a cat gift and the content of the comment.
## Usage

Put the plugin repo folder in your `/opt/lampp/htdocs/wordpress/wp-content/plugins/` directory and activate in your WordPress Plugin settings.

## Installation for the giphy php client

install composer

instructions
*https://www.suvashsumon.xyz/post/install-xampp-composer-on-ubuntu/*

In the plugin folder it is the giphy-php-client repository.
inside this folder execute

```bash
composer require giphy/giphy-php-client
```
repository  *https://github.com/Giphy/giphy-php-client*


For documentation about hooks and functions use reference https://developer.wordpress.org/reference/hooks/.

