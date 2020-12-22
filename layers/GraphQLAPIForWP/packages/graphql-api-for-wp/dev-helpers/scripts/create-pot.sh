#!/bin/bash
# This bash script generates file /languages/graphql-api.pot,
# containing all the strings to translate.
# It must be executed on the root folder of the project.
# It required wp-pot-cli: https://github.com/wp-pot/wp-pot-cli
wp-pot --src 'src/**/*.php' --dest-file 'languages/graphql-api.pot' --domain 'graphql-api' --team 'Leo <leo@getpop.org>' --package 'GraphQL API'
