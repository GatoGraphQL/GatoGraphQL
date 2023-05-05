#!/bin/bash
# This bash script generates file /languages/gato-graphql.pot,
# containing all the strings to translate.
# It must be executed on the root folder of the project.
# It required wp-pot-cli: https://github.com/wp-pot/wp-pot-cli
wp-pot --src 'src/**/*.php' --dest-file 'languages/gato-graphql.pot' --domain 'gato-graphql' --team 'Leo <leo@getpop.org>' --package 'GraphQL API'
