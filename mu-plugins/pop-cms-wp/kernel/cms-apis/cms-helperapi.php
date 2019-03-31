<?php
namespace PoP\CMS\WP;

class HelperAPI extends \PoP\CMS\HelperAPI_Base
{

    public function addQueryArgs(array $key_values, string $url)
    {
        return add_query_arg($key_values, $url);
    }
    public function removeQueryArgs(array $keys, string $url)
    {
        return remove_query_arg($keys, $url);
    }

    public function escapeHTML(string $text) {

    	return esc_html($text);
    }
    public function escapeAttributes(string $text)
    {
        return esc_attr($text);
    }

    public function makeClickable(string $text) {

    	return make_clickable($text);
    }

    public function convertLinebreaksToHTML(string $text) {

    	return wpautop($text);
    }

    public function sanitizeUsername(string $username)
    {
        return sanitize_user($username);
    }
    public function sanitizeTitle(string $title)
    {
        return sanitize_title($title);
    }

    public function maybeAddTrailingSlash(string $text)
    {
        return trailingslashit($text);
    }
}

/**
 * Initialize
 */
new HelperAPI();
