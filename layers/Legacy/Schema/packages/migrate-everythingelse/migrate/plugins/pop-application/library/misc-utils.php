<?php

use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;

function getExcerptMore()
{
    return apply_filters('excerpt_more', ' ' . '[&hellip;]');
}
function getExcerptLength()
{
    return apply_filters('excerpt_length', 55);
}
function limitString($string, $length = null, $more = null, $bywords = false)
{
    if (!$length) {
        $length = \getExcerptLength();
    }
    // Similar to wp_trim_excerpt in wp-includes/formatting.php
    if (!$more) {
        $more = \getExcerptMore();
    }
    if (!$bywords) {
        // Comment Leo 11/07/2017: it works fine using mb_substr instead, so use that one
        $string = (strlen($string) > $length) ? mb_substr($string, 0, $length) . $more : $string;
    } else {
        // Abstracted from WordPress. New solution taken from https://stackoverflow.com/questions/965235/how-can-i-truncate-a-string-to-the-first-20-words-in-php
        // $string = wp_trim_words($string, $length, $more);
        if (str_word_count($string, 0) > $length) {
            $words = str_word_count($string, 2);
            $pos = array_keys($words);
            $string = substr($string, 0, $pos[$length]) . $more;
        }
    }

    return $string;
}

function maybeAddHttp($url)
{
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

// function gdGetCategories($post_id)
// {
//     $categories = array();
//     $postTypeAPI = PostTypeAPIFacade::getInstance();
//     $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
//     if ($customPostTypeAPI->getCustomPostType($post_id) == $postTypeAPI->getPostCustomPostType()) {
//         if ($cats = getTheCategory($post_id)) {
//             $cmstaxonomiesresolver = \PoPSchema\Taxonomies\ObjectPropertyResolverFactory::getInstance();
//             foreach ($cats as $cat) {
//                 $categories[] = $cmstaxonomiesresolver->getCategoryID($cat);
//             }
//         }
//     }

//     return \PoP\Root\App::getHookManager()->applyFilters('gdGetCategories', $categories, $post_id);
// }


function gdGetPostname($post_id, $format = 'title')
{
    $postname = \PoP\Root\App::getHookManager()->applyFilters('gd_postname', TranslationAPIFacade::getInstance()->__('Post', 'pop-coreprocessors'), $post_id, $format);

    // Lowercase
    if ($format == 'lc' || $format == 'plural-lc') {
        $postname = strtolower($postname);
    }

    return \PoP\Root\App::getHookManager()->applyFilters('gd_format_postname', $postname, $post_id, $format);
}

function gdGetCategoryname($cat_id, $format = 'title')
{
    $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
    $catname = \PoP\Root\App::getHookManager()->applyFilters('gd_catname', $postCategoryTypeAPI->getCategoryName($cat_id), $cat_id, $format);

    // Lowercase
    if ($format == 'lc' || $format == 'plural-lc') {
        $catname = strtolower($catname);
    }

    return \PoP\Root\App::getHookManager()->applyFilters('gd_format_catname', $catname, $cat_id, $format);
}

function gdGetPosticon($post_id)
{
    return \PoP\Root\App::getHookManager()->applyFilters('gd_posticon', '', $post_id);
}


function gdGetAvatar($user_id, $size)
{
    if (defined('POP_AVATAR_INITIALIZED')) {
        $pluginapi = PoP_Avatar_FunctionsAPIFactory::getInstance();
        $avatar = $pluginapi->getAvatar($user_id, $size);
        // It doesn't work with images from Facebook, so replace with regex
        // Solution: http://stackoverflow.com/questions/2120779/regex-php-isolate-src-attribute-from-img-tag
        preg_match('/src="([^"]*)"/i', $avatar, $array);
        $url = $array[1];

        return array('src' => $url, 'size' => $size);
    }
    return array();
}

function getReloadurlLinkattrs()
{

    // Allow PoP Service Workers to add its own parameter
    return \PoP\Root\App::getHookManager()->applyFilters('getReloadurlLinkattrs', 'data-reloadurl="true"');
}

function getUrlHost($url)
{

    // Get the domain of the URL, and return different results for different domains
    // Taken from https://stackoverflow.com/questions/276516/parsing-domain-from-url-in-php
    $parse = parse_url($url);
    return $parse['host']; // From 'http://google.com/pepe.html' it returns 'google.com'
}

// Taken from http://www.w3schools.com/php/php_form_url_email.asp
function isValidUrl($url)
{
    return preg_match("/\b(?:(?:https?):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url);
}

