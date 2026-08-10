#!/bin/bash
echo Adding options for Gato GraphQL Integration tests

wp option add downstream_domains [\"$(wp option get siteurl)\"] --format=json
wp option add list_of_objects '[{"id":"nfpllg","category":"","desc":"","properties":[],"_created":1750156925,"_user_id":1,"_version":"2.0-beta"},{"id":"fufxfs","category":"","desc":"","properties":[],"_created":1750156913,"_user_id":1,"_version":"2.0-beta"}]' --format=json

# Options are somebody else's content, and content holds quotes: what is read
# out of them travels through a JSON response, and what is printed about them
# travels through a shell. A value with none in it proves neither.
wp option add value_with_quotes 'It'\''s a "quoted" value'
wp option add list_of_values_with_quotes '["It'\''s the first","She said \"the second\""]' --format=json
wp option add object_with_quotes '{"label":"It'\''s an object","quote":"She said \"hello\""}' --format=json
