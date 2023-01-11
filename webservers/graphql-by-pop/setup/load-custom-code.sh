#!/bin/sh
sed "s#require_once ABSPATH . 'wp-settings.php';#require_once(__DIR__ . '/../vendor/autoload.php'); require_once(__DIR__ . '/../code-src/initialize-pop-modules.php'); require_once  ABSPATH  .  'wp-settings.php' ;#g" /app/wordpress/wp-config.php > /app/wp-config.php.tmp
mv /app/wp-config.php.tmp /app/wordpress/wp-config.php
