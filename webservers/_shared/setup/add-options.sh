#!/bin/bash
echo Adding options for Gato GraphQL Integration tests

wp option add downstream_domains [\"$(wp option get siteurl)\"] --format=json
wp option add list_of_objects '[{"id":"nfpllg","category":"","desc":"","properties":[],"_created":1750156925,"_user_id":1,"_version":"2.0-beta"},{"id":"fufxfs","category":"","desc":"","properties":[],"_created":1750156913,"_user_id":1,"_version":"2.0-beta"}]' --format=json
