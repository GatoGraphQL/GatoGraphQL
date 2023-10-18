#!/bin/sh
echo "Creating taxonomy terms"
# Create terms for "post_format"
# @see https://wordpress.org/support/article/post-formats/#backwards-compatibility
wp term create post_format Aside --description="Some Post Format for Testing"
wp term create post_format Link --description="Another Post Format for Testing"
wp term create post_format Quote --description="Yet another Post Format for Testing"
