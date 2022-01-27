<?php

/**
 * Avoid problem of duplicates when filtering Profiles
 */

\PoP\Root\App::addAction('pre_user_query', 'preUserQueryAvoidDuplicates');
function preUserQueryAvoidDuplicates($user_query)
{
    $user_query->query_fields = 'SQL_CALC_FOUND_ROWS DISTINCT ' . $user_query->query_fields;
}
\PoP\Root\App::addFilter('found_users_query', 'foundUsersQueryAvoidDuplicates');
function foundUsersQueryAvoidDuplicates($sql)
{
    return 'SELECT FOUND_ROWS()';
}
