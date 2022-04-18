#!/bin/sh
echo "Creating users"
# Create users
wp user create subscriber subscriber@test.com --role=subscriber --user_pass=11111111 --first_name=Subscriber --last_name=Bennett --path=/app/wordpress
wp user create contributor contributor@test.com --role=contributor --user_pass=11111111 --first_name=Contributor --last_name=Johnson --path=/app/wordpress
wp user create author author@test.com --role=author --user_pass=11111111 --first_name=Author --last_name=Marquez --path=/app/wordpress
wp user create editor editor@test.com --role=editor --user_pass=11111111 --first_name=Editor --last_name=Smith --path=/app/wordpress
