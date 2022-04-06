<?php

declare(strict_types=1);

namespace GraphQLAPI\PHPUnitWPFakerSchema\UsersWP\TypeAPIs;

use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\UsersWP\TypeAPIs\UserTypeAPI as UpstreamUserTypeAPI;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use WP_User;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserTypeAPI extends UpstreamUserTypeAPI
{
    public function getUsers($query = array(), array $options = []): array
    {
        // Convert the parameters
        $query = $this->convertUsersQuery($query, $options);

        // // Limit users which have an email appearing on the input
        // // WordPress does not allow to search by many email addresses, only 1!
        // // Then we implement a hack to allow for it:
        // // 1. Set field "search", as expected
        // // 2. Add a hook which will modify the SQL query
        // // 3. Execute query
        // // 4. Remove hook
        // $filterByEmails = $this->filterByEmails($query);
        // if ($filterByEmails) {
        //     App::addAction('pre_user_query', $this->enableMultipleEmails(...));
        // }

        // Execute the query
        // $ret = get_users($query);
        $faker = \Brain\faker();
        $wpFaker = $faker->wp();
        // $ret = get_users($query);
        $ret = $wpFaker->users(3);

        if (($options[QueryOptions::RETURN_TYPE] ?? null) === ReturnTypes::IDS) {
            $ret = array_map(
                fn (WP_User $user) => $user->ID,
                $ret
            );
        }

        // // Remove the hook
        // if ($filterByEmails) {
        //     App::removeAction('pre_user_query', $this->enableMultipleEmails(...));
        // }
        return $ret;
    }
}
