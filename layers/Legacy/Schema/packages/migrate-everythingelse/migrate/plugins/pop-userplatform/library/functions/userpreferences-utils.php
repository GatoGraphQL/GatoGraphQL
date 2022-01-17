<?php
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class PoP_UserPlatform_UserPreferencesUtils
{
    public static function getPreferenceonUsers($value, $include = array(), $exclude = array())
    {

        // Keep only the users with the corresponding preference on
        $query = array(
            'meta-query' => [
                [
                    'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_USERPREFERENCES),
                    'value' => $value,
                    'compare' => 'IN',
                ],
            ],
            // 'fields' => 'ID',
        );

        // Search only within an array of users?
        // Notice that both 'include' and 'exclude' cannot go together in the query, so if both are provided, the logic to exclude is done after getting the results
        if ($include) {
            $query['include'] = $include;
        } elseif ($exclude) {
            $query['exclude-ids'] = $exclude;
        }

        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $users = $userTypeAPI->getUsers($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

        // Exclude users?
        if ($include && $exclude) {
            $users = array_diff($users, $exclude);
        }

        return $users;
    }
}
