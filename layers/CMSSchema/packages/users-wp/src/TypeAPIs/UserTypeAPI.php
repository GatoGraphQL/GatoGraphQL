<?php

declare(strict_types=1);

namespace PoPCMSSchema\UsersWP\TypeAPIs;

use PoP\Root\App;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Users\Constants\UserOrderBy;
use PoPCMSSchema\Users\TypeAPIs\AbstractUserTypeAPI;
use WP_User;
use WP_User_Query;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserTypeAPI extends AbstractUserTypeAPI
{
    public const HOOK_QUERY = __CLASS__ . ':query';
    public const HOOK_ORDERBY_QUERY_ARG_VALUE = __CLASS__ . ':orderby-query-arg-value';

    /**
     * Indicates if the passed object is of type User
     */
    public function isInstanceOfUserType(object $object): bool
    {
        return $object instanceof WP_User;
    }

    protected function getUserBy(string $property, string | int $userID): ?object
    {
        $user = get_user_by($property, $userID);
        if ($user === false) {
            return null;
        }
        return $user;
    }

    public function getUserById(string | int $userID): ?object
    {
        return $this->getUserBy('id', $userID);
    }

    public function getUserByEmail(string $email): ?object
    {
        return $this->getUserBy('email', $email);
    }

    public function getUserByLogin(string $login): ?object
    {
        return $this->getUserBy('login', $login);
    }

    public function getUserCount(array $query = [], array $options = []): int
    {
        // Convert the parameters
        $options[QueryOptions::RETURN_TYPE] = ReturnTypes::IDS;
        $query = $this->convertUsersQuery($query, $options);

        // All results, no offset
        $query['number'] = -1;
        unset($query['offset']);

        // Limit users which have an email appearing on the input
        // WordPress does not allow to search by many email addresses, only 1!
        // Then we implement a hack to allow for it:
        // 1. Set field "search", as expected
        // 2. Add a hook which will modify the SQL query
        // 3. Execute query
        // 4. Remove hook
        $filterByEmails = $this->filterByEmails($query);
        if ($filterByEmails) {
            add_action('pre_user_query', [$this, 'enableMultipleEmails']);
        }

        // Execute the query. Original solution from:
        // @see https://developer.wordpress.org/reference/functions/get_users/#source
        // Only difference: use `total_count` => true, `get_total` instead of `get_results`
        $args                = \wp_parse_args($query);
        $args['count_total'] = true;
        $user_search = new \WP_User_Query($args);
        $ret = (int) $user_search->get_total();

        // Remove the hook
        if ($filterByEmails) {
            remove_action('pre_user_query', [$this, 'enableMultipleEmails']);
        }
        return $ret;
    }
    public function getUsers($query = array(), array $options = []): array
    {
        // Convert the parameters
        $query = $this->convertUsersQuery($query, $options);

        // Limit users which have an email appearing on the input
        // WordPress does not allow to search by many email addresses, only 1!
        // Then we implement a hack to allow for it:
        // 1. Set field "search", as expected
        // 2. Add a hook which will modify the SQL query
        // 3. Execute query
        // 4. Remove hook
        $filterByEmails = $this->filterByEmails($query);
        if ($filterByEmails) {
            add_action('pre_user_query', [$this, 'enableMultipleEmails']);
        }

        // Execute the query
        $ret = get_users($query);

        // Remove the hook
        if ($filterByEmails) {
            remove_action('pre_user_query', [$this, 'enableMultipleEmails']);
        }
        return $ret;
    }
    /**
     * Limit users which have an email appearing on the input
     * WordPress does not allow to search by many email addresses, only 1!
     * Then we implement a hack to allow for it:
     * 1. Set field "search", as expected
     * 2. Add a hook which will modify the SQL query
     * 3. Execute query
     * 4. Remove hook
     *
     * @param mixed[] $query
     *
     * @see https://developer.wordpress.org/reference/classes/wp_user_query/#search-parameters
     */
    protected function filterByEmails(array &$query): bool
    {
        if (isset($query['emails'])) {
            $emails = $query['emails'];
            // This works for either 1 or many emails
            $query['search'] = implode(',', $emails);
            $query['search_columns'] = ['user_email'];
            // But if there's more than 1 email, we must modify the SQL query with a hook
            if (count($emails) > 1) {
                return true;
            }
        }
        return false;
    }

    protected function convertUsersQuery(array $query, array $options = []): array
    {
        if (($options[QueryOptions::RETURN_TYPE] ?? null) === ReturnTypes::IDS) {
            $query['fields'] = 'ID';
        }

        // Convert parameters
        if (isset($query['name'])) {
            $query['meta_query'][] = [
                'key' => 'nickname',
                'value' => $query['name'],
                'compare' => 'LIKE',
            ];
            unset($query['name']);
        }
        if (isset($query['username'])) {
            $query['login'] = $query['username'];
            unset($query['username']);
        }
        /**
         * Watch out: "search" and "emails" can't be set at the same time,
         * because they both use the same "search" field in the query.
         */
        if (isset($query['search']) && !($query['emails'] ?? null)) {
            // Search: Attach "*" before/after the term, to support searching partial strings
            $query['search'] = sprintf(
                '*%s*',
                $query['search']
            );
        }
        if (isset($query['include']) && is_array($query['include'])) {
            // It can be an array or a string
            $query['include'] = implode(',', $query['include']);
        }
        if (isset($query['exclude-ids'])) {
            $query['exclude'] = $query['exclude-ids'];
            unset($query['exclude-ids']);
        }
        if (isset($query['order'])) {
            $query['order'] = \esc_sql($query['order']);
        }
        if (isset($query['orderby'])) {
            // Maybe replace the provided value
            $query['orderby'] = \esc_sql($this->getOrderByQueryArgValue($query['orderby']));
        }
        if (isset($query['offset'])) {
            // Same param name, so do nothing
        }
        if (isset($query['limit'])) {
            $limit = (int) $query['limit'];
            $query['number'] = $limit;
            unset($query['limit']);
        }

        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }
    protected function getOrderByQueryArgValue(string $orderBy): string
    {
        $orderBy = match ($orderBy) {
            UserOrderBy::ID => 'ID',
            UserOrderBy::NAME => 'name',
            UserOrderBy::USERNAME => 'login',
            UserOrderBy::DISPLAY_NAME => 'display_name',
            UserOrderBy::REGISTRATION_DATE => 'registered',
            default => $orderBy,
        };
        return App::applyFilters(
            self::HOOK_ORDERBY_QUERY_ARG_VALUE,
            $orderBy
        );
    }
    /**
     * Modify the SQL query, replacing searching for a single email
     * (with SQL operation "=") to multiple ones (with SQL operation "IN")
     */
    public function enableMultipleEmails(WP_User_Query $query): void
    {
        $qv =& $query->query_vars;
        if (isset($qv['search'])) {
            $search = trim($qv['search']);
            // Validate it has no wildcards, it's email (because there's a "@")
            // and there's more than one (because there's ",")
            $leading_wild = (ltrim($search, '*') != $search);
            $trailing_wild = (rtrim($search, '*') != $search);
            if (!$leading_wild && !$trailing_wild && false !== strpos($search, '@') && false !== strpos($search, ',')) {
                // Replace the query
                $emails = explode(',', $search);
                $searches = [sprintf("user_email IN (%s)", "'" . implode("','", $emails) . "'")];
                $replace = $this->get_search_sql($search, ['user_email'], false);
                $replacement = ' AND (' . implode(' OR ', $searches) . ')';
                $query->query_where = str_replace($replace, $replacement, $query->query_where);
            }
        }
    }

    /**
     * Function copied from WordPress source, because it is protected!
     *
     * @source wp-includes/class-wp-user-query.php
     *
     * Used internally to generate an SQL string for searching across multiple columns
     *
     * @since 3.1.0
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param string $string
     * @param array  $cols
     * @param bool   $wild   Whether to allow wildcard searches. Default is false for Network Admin, true for single site.
     *                       Single site allows leading and trailing wildcards, Network Admin only trailing.
     * @return string
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function get_search_sql($string, $cols, $wild = false)
    {
        global $wpdb;

        $searches      = array();
        // Avoid PHPStan errors:
        //   Result of || is always false
        // $leading_wild  = ( 'leading' === $wild || 'both' === $wild ) ? '%' : '';
        // $trailing_wild = ( 'trailing' === $wild || 'both' === $wild ) ? '%' : '';
        $leading_wild  = '';
        $trailing_wild = '';
        $like          = $leading_wild . $wpdb->esc_like($string) . $trailing_wild;

        foreach ($cols as $col) {
            if ('ID' === $col) {
                $searches[] = $wpdb->prepare("$col = %s", $string);
            } else {
                $searches[] = $wpdb->prepare("$col LIKE %s", $like);
            }
        }

        return ' AND (' . implode(' OR ', $searches) . ')';
    }

    protected function getUserProperty(string $property, string | int | object $userObjectOrID): ?string
    {
        if (is_object($userObjectOrID)) {
            /** @var WP_User */
            $user = $userObjectOrID;
            return $user->$property;
        }
        return get_the_author_meta($property, $userObjectOrID);
    }
    public function getUserDisplayName(string | int | object $userObjectOrID): ?string
    {
        return $this->getUserProperty('display_name', $userObjectOrID);
    }
    public function getUserEmail(string | int | object $userObjectOrID): ?string
    {
        return $this->getUserProperty('user_email', $userObjectOrID);
    }
    public function getUserFirstname(string | int | object $userObjectOrID): ?string
    {
        return $this->getUserProperty('user_firstname', $userObjectOrID);
    }
    public function getUserLastname(string | int | object $userObjectOrID): ?string
    {
        return $this->getUserProperty('user_lastname', $userObjectOrID);
    }
    public function getUserLogin(string | int | object $userObjectOrID): ?string
    {
        return $this->getUserProperty('user_login', $userObjectOrID);
    }
    public function getUserDescription(string | int | object $userObjectOrID): ?string
    {
        return $this->getUserProperty('description', $userObjectOrID);
    }
    public function getUserWebsiteUrl(string | int | object $userObjectOrID): ?string
    {
        return $this->getUserProperty('user_url', $userObjectOrID);
    }
    public function getUserSlug(string | int | object $userObjectOrID): ?string
    {
        return $this->getUserProperty('user_nicename', $userObjectOrID);
    }
    public function getUserId(object $user): string | int
    {
        return $user->ID;
    }

    public function getUserURL(string | int | object $userObjectOrID): ?string
    {
        if (is_object($userObjectOrID)) {
            /** @var WP_User */
            $user = $userObjectOrID;
            $userID = $user->ID;
        } else {
            $userID = $userObjectOrID;
        }
        return get_author_posts_url($userID);
    }
}
