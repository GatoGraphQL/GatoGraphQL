#!/bin/sh
echo "Creating users"
# Create users
wp user create blogger blogger@test.com --role=editor --user_pass=11111111 --first_name=Blogger --last_name=Davenport --user_registered="1982-06-29-17-48-20"
wp user create subscriber subscriber@test.com --role=subscriber --user_pass=11111111 --first_name=Subscriber --last_name=Bennett --user_registered="1982-06-29-17-48-23"
wp user create contributor contributor@test.com --role=contributor --user_pass=11111111 --first_name=Contributor --last_name=Johnson --user_registered="1982-06-29-17-48-24"
wp user create author author@test.com --role=author --user_pass=11111111 --first_name=Author --last_name=Marquez --user_registered="1982-06-29-17-48-25"
wp user create editor editor@test.com --role=editor --user_pass=11111111 --first_name=Editor --last_name=Smith --user_registered="1982-06-29-17-48-26"

# To test meta queries
wp user meta update 2 locale "es_ES"
wp user meta update 3 locale "es_AR"
