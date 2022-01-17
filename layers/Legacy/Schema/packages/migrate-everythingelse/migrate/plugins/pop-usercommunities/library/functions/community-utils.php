<?php
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class URE_CommunityUtils
{
    public static function addDataloadqueryargsCommunitymembers(&$ret, $community_id): void
    {
        // It must fulfil 2 conditions: the user must've said he/she's a member of this community,
        // And the Community must've accepted it by leaving the Show As Member privilege on
        $ret['meta-query'][] = [
            'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_URE_METAKEY_PROFILE_COMMUNITIES),
            'value' => $community_id,
            'compare' => 'IN'
        ];
        $ret['meta-query'][] = [
            'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS),
            'value' => gdUreGetCommunityMetavalue($community_id, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE),
            'compare' => 'IN'
        ];
    }

    public static function getCommunityMembers($community_id): array
    {
        $query = array(
            'limit' => -1,/*'number' => '',*/ // Bring all the results
        );
        self::addDataloadqueryargsCommunitymembers($query, $community_id);

        $userTypeAPI = UserTypeAPIFacade::getInstance();
        return $userTypeAPI->getUsers($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
    }
}
