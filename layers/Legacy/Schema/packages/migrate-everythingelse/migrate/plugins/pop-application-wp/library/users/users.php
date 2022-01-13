<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Avoid problem of duplicates when filtering Profiles
 */

HooksAPIFacade::getInstance()->addAction('pre_user_query', 'preUserQueryAvoidDuplicates');
function preUserQueryAvoidDuplicates($user_query)
{
    $user_query->query_fields = 'SQL_CALC_FOUND_ROWS DISTINCT ' . $user_query->query_fields;
}
HooksAPIFacade::getInstance()->addFilter('found_users_query', 'foundUsersQueryAvoidDuplicates');
function foundUsersQueryAvoidDuplicates($sql)
{
    return 'SELECT FOUND_ROWS()';
}
