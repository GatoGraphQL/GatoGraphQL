<?php
function addFilter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
{
    return add_filter($tag, $function_to_add, $priority, $accepted_args);
}

function removeFilter($tag, $function_to_remove, $priority = 10)
{
    return remove_filter($tag, $function_to_remove, $priority);
}

function getOption($option, $default = false)
{
    return get_option($option, $default);
}

function getBloginfo($show = '', $filter = 'raw')
{
    return get_bloginfo($show, $filter);
}

function getTheTitle($post = 0)
{
    return get_the_title($post);
}

function getPostType($post = null)
{
    return get_post_type($post);
}

function getPermalink($post = 0, $leavename = false)
{
    return get_permalink($post, $leavename);
}

function getCatName($cat_id)
{
    return get_cat_name($cat_id);
}

function homeUrl($path = '', $scheme = null)
{
    return home_url($path, $scheme);
}

function getUsers($args = array())
{
    return get_users($args);
}

function getTags($args = '')
{
    return get_tags($args);
}

function getPosts($args = null)
{
    return get_posts($args);
}

function getAvatar($id_or_email, $size = 96, $default = '', $alt = '', $args = null)
{
	return get_avatar($id_or_email, $size, $default, $alt, $args);
}

function getPostTypes($args = array(), $output = 'names', $operator = 'and')
{
    return get_post_types($args, $output, $operator);
}

function getPost($post = null, $output = OBJECT, $filter = 'raw')
{
    return get_post($post, $output, $filter);
}

function getAuthorPostsUrl($author_id, $author_nicename = '')
{
    return get_author_posts_url($author_id, $author_nicename);
}

function getComments($args = '')
{
    return get_comments($args);
}

function getTheAuthorMeta($field = '', $user_id = false)
{
    return get_the_author_meta($field, $user_id);
}

function getSiteUrl($blog_id = null, $path = '', $scheme = null)
{
    return get_site_url($blog_id, $path, $scheme);
}

function getHomePath()
{
    return get_home_path();
}
