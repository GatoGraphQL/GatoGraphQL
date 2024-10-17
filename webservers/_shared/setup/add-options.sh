#!/bin/bash
echo Adding options for Gato GraphQL Integration tests

wp option add downstream_domains [\"$(wp option get siteurl)\"] --format=json
