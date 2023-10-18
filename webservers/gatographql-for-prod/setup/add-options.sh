#!/bin/sh
echo Adding options for Gato GraphQL Integration tests

SITE_DOMAIN=$(wp option get siteurl)

wp option add downstream_domains [\"$(echo $SITE_DOMAIN)\"] --format=json
