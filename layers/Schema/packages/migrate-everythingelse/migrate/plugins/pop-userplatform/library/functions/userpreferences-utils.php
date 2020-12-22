<?php
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class PoP_UserPlatform_UserPreferencesUtils
{
    public static function getPreferenceonUsers($value, $include = array(), $exclude = array())
    {

        // Keep only the users with the corresponding preference on
        $query = array(
            'meta-query' => [
                [
                    'key' => \PoPSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_USERPREFERENCES),
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
            $query['exclude'] = $exclude;
        }

        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $users = $cmsusersapi->getUsers($query, ['return-type' => ReturnTypes::IDS]);

        // Exclude users?
        if ($include && $exclude) {
            $users = array_diff($users, $exclude);
        }

        return $users;
    }
}
