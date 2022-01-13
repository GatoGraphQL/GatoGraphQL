<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

function gdUreGetCommunities($user_id): array
{
    return \PoPSchema\UserMeta\Utils::getUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES) ?? [];
}

function gdUreGetActivecontributingcontentcommunitymembers($community)
{
    $userTypeAPI = UserTypeAPIFacade::getInstance();
    // Taken from https://codex.wordpress.org/Class_Reference/WP_Meta_Query

    // It must fulfil 2 conditions: the user must've said he/she's a member of this community,
    // And the Community must've accepted it by leaving the Contribute Content privilege on
    $query = array(
        // 'fields' => 'ID',
        'meta-query' => [
            'relation' => 'AND',
            [
                'key' => \PoPSchema\UserMeta\Utils::getMetaKey(GD_URE_METAKEY_PROFILE_COMMUNITIES),
                'value' => $community,
                'compare' => 'IN'
            ],
            [
                'key' => \PoPSchema\UserMeta\Utils::getMetaKey(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS),
                'value' => gdUreGetCommunityMetavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE),
                'compare' => 'IN'
            ],
            [
                'key' => \PoPSchema\UserMeta\Utils::getMetaKey(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES),
                'value' => gdUreGetCommunityMetavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERPRIVILEGES_CONTRIBUTECONTENT),
                'compare' => 'IN'
            ],
        ]
    );

    return $userTypeAPI->getUsers($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
}

function gdUreGetCommunityMetavalueContributecontent($user_id)
{
    return gdUreGetCommunityMetavalue($user_id, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERPRIVILEGES_CONTRIBUTECONTENT);
}

function gdUreGetCommunityMetavalueCurrentcommunity($value)
{
    $community = \PoP\Root\App::getState('current-user-id');
    return gdUreGetCommunityMetavalue($community, $value);
}

function gdUreGetCommunityMetavalue($user_id, $value)
{
    return $user_id.':'.$value;
}

function gdUreUserAddnewcommunities($user_id, $communities)
{

    // Make sure there are communities
    if (!$communities) {
        return;
    }

    $status = \PoPSchema\UserMeta\Utils::getUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);
    $privileges = \PoPSchema\UserMeta\Utils::getUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES);
    $tags = \PoPSchema\UserMeta\Utils::getUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS);

    // When creating a new user account, these will be empty, so get the default ones
    if (!$status) {
        $status = $privileges = $tags = array();
    }

    // For each community, add the default privileges/tags (only if not existing already, so as to not override values already set by the community)
    // This also works because we've set GD_METAVALUE_NONE if they are empty, so that it will never have no value in the DB
    foreach ($communities as $community) {
        $status[] = gdUreGetCommunityMetavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE);

        // Add the default privileges for this one community
        $privileges[] = gdUreGetCommunityMetavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERPRIVILEGES_CONTRIBUTECONTENT);

        // Add the default tags for this one community
        $tags[] = gdUreGetCommunityMetavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERTAGS_MEMBER);
    }

    // Update the DB
    \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS, $status);
    \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES, $privileges);
    \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS, $tags);

    // Allow ACF to also save the value in the DB
    \PoP\Root\App::getHookManager()->doAction('ure:user:add_new_communities', $user_id, $communities);
}

function gdUreFindCommunityMetavalues($community, $values, $extract_metavalue = true)
{
    $ret = array();

    // Filter to retrieve only the status for the given community
    if ($values) {
        foreach ($values as $value) {
            $parts = explode(':', $value);

            // Found a record for this community?
            if ($community == $parts[0]) {
                // Found!
                if ($extract_metavalue) {
                    $ret[] = $parts[1];
                } else {
                    $ret[] = $value;
                }
            }
        }
    }

    return $ret;
}

function gdUreGetCommunitiesStatusActive($user_id): array
{
    // Filter the community roles where the user is accepted as a member
    if ($community_status = \PoPSchema\UserMeta\Utils::getUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS)) {
        $statusactive_communities = array_values(array_filter(array_map('gdUreGetCommunitiesStatusActiveFilter', $community_status)));

        // Get the communities the user says he/she belongs to
        $userchosen_communities = \PoPSchema\UserMeta\Utils::getUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES) ?? [];

        // Return the intersection of these 2
        return array_values(array_intersect($statusactive_communities, $userchosen_communities));
    }

    return array();
}

function gdUreGetCommunitiesStatusActiveFilter($value)
{
    $parts = explode(':', $value);
    $community = $parts[0];
    $status = $parts[1];

    if ($status == GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE) {
        return $community;
    }

    return false;
}

function gdUreCommunityMembershipstatusFilterbycurrentcommunity($values)
{
    return gdUreCommunityMembershipstatusFilterbycommunity($values, \PoP\Root\App::getState('current-user-id'));
}

function gdUreCommunityMembershipstatusFilterbycommunity($values, $community)
{
    $ret = array();
    foreach ($values as $value) {
        $parts = explode(':', $value);
        $usercommunity = $parts[0];
        if ($community == $usercommunity) {
            $status = $parts[1]; // Status can be the privilege or tag
            $ret[] = $status;
        }
    }

    return $ret;
}

function gdUreEditMembershipUrl($user_id, $inline = false)
{
    $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();

    $nonce = gdCreateNonce(GD_NONCE_EDITMEMBERSHIPURL, $user_id);
    $url = GeneralUtils::addQueryArgs([
        POP_INPUTNAME_NONCE => $nonce,
        \PoPSchema\Users\Constants\InputNames::USER_ID => $user_id,
    ], RouteUtils::getRouteURL(POP_USERCOMMUNITIES_ROUTE_EDITMEMBERSHIP));

    return $url;
}
