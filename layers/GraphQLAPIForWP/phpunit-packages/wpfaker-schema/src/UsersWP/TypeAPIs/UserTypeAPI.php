<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\UsersWP\TypeAPIs;

use GraphQLAPI\WPFakerSchema\App;
use PoPCMSSchema\UsersWP\TypeAPIs\UserTypeAPI as UpstreamUserTypeAPI;
use WP_User;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserTypeAPI extends UpstreamUserTypeAPI
{
    protected function getUsersByCMS(array $query): array
    {
        /**
         * If providing the IDs to retrieve, re-generate exactly those objects.
         * Otherwise, get random ones.
         */
        $ids = $query['include'] ?? null;
        if (!empty($ids)) {
            $ids = is_string($ids) ? explode(',', $ids) : $ids;
            /** @var array $ids */
            $users = array_map(
                fn (string|int $id) => App::getWPFaker()->user(['id' => (int) trim($id)]),
                $ids
            );
        } else {
            $users = App::getWPFaker()->users($query['number'] ?? 10);
        }

        /**
         * Retrieve the IDs of the objects?
         */
        if (($query['fields'] ?? null) === 'ID') {
            $users = array_map(
                fn (WP_User $user) => $user->ID,
                $users
            );
        }
        return $users;
    }
}
