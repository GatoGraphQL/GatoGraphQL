#!/bin/sh
echo "Creating users"
# Create users
wp user create subscriber subscriber@test.com --role=subscriber --user_pass=11111111 --first_name=Subscriber --last_name=Bennett --user_registered="1982-06-29-17-48-23" --path=/app/wordpress
wp user create contributor contributor@test.com --role=contributor --user_pass=11111111 --first_name=Contributor --last_name=Johnson --user_registered="1982-06-29-17-48-24" --path=/app/wordpress
wp user create author author@test.com --role=author --user_pass=11111111 --first_name=Author --last_name=Marquez --user_registered="1982-06-29-17-48-25" --path=/app/wordpress
wp user create editor editor@test.com --role=editor --user_pass=11111111 --first_name=Editor --last_name=Smith --user_registered="1982-06-29-17-48-26" --path=/app/wordpress
