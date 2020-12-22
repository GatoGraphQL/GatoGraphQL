<?php
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * Change Author permalink from 'author' to 'u'
 */
function getAuthorBase() {
    return HooksAPIFacade::getInstance()->applyFilters('author-base', '');
}

// change author/username base to users/userID
HooksAPIFacade::getInstance()->addAction('init', 'changeAuthorPermalinks');
function changeAuthorPermalinks()
{

    if ($authorBase = getAuthorBase()) {
        // Comment Leo 25/04/2016: Split the logic below into 2, to be executed in "init" and "PoP:system-build",
        // because flush_rules() would execute a massive update in the DB with each single request, which takes performance waaaay down and kills the server,
        // as I found out on the AWS servers
        global $wp_rewrite;
        $wp_rewrite->author_base = $authorBase;
        // $wp_rewrite->flush_rules();
    }
}

HooksAPIFacade::getInstance()->addFilter('query_vars', 'usersQueryVars');
function usersQueryVars($vars)
{

    if ($authorBase = getAuthorBase()) {
        // add lid to the valid list of variables
        $new_vars = array($authorBase);
        $vars = $new_vars + $vars;
    }
    return $vars;
}
HooksAPIFacade::getInstance()->addFilter('generate_rewrite_rules', 'userRewriteRules');
function userRewriteRules($wp_rewrite)
{
    if ($authorBase = getAuthorBase()) {
        $newrules = array();
        $new_rules[$authorBase.'/(\d*)$'] = 'index.php?author=$matches[1]';
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }
}
