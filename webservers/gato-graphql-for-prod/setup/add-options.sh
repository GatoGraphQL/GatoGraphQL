#!/bin/sh
echo Adding options for Gato GraphQL Integration tests

SITE_DOMAIN=$(wp option get siteurl --path=/app/wordpress)

wp option add downstream_domains [\"$(echo $SITE_DOMAIN)\"] --format=json --path=/app/wordpress
