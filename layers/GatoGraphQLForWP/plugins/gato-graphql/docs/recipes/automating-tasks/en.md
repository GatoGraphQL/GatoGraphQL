# Automating tasks

By having a persisted query act as a webhook
    passing vars with postId or what
Have an external system trigger an action on the WordPress site

Internal GraphQL Server



Add to recipe: "Using WP-Cron"
    also: https://wordpress.org/plugins/wp-crontrol/

Can be triggered using plugin "Code Snippets"
    https://wordpress.org/plugins/code-snippets/
eg:
    add_action('post_updated', ...)
Add to recipe!!!



Add to Recipe:
    Add hook `do_action('gato_graphql_persisted_query', $persistedID)`
        Then can be triggered by wp-cron!
        Read:
            https://developer.wordpress.org/plugins/cron/scheduling-wp-cron-events/
            https://wordpress.org/plugins/wp-crontrol/#how%20do%20i%20create%20a%20new%20cron%20event%3F
