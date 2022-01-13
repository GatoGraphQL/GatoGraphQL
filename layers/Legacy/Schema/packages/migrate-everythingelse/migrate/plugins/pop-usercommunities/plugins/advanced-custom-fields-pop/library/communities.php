<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * ACF plugin Functions
 *
 * Documentation: how ACF goes parallel to the user meta
 * Since we can't set ACF to have a format nice to read for the repeater field, so that it is compatible with getUserMeta and so we can filter by the selected values, so we decided to have both formats in the DB: ACF and the website custom format, thus creating redundancy. Then, whenever saving ACF from the back-end, it will then also save the custom format, and whenever saving the custom format from the front-end, it will trigger to also save ACF
 *
 * Structure of the meta saved: it goes in each user (not in the community) and the format is community:status (privilege or tag)
 * It goes in the user so that we can search for it using getUsers and meta_query values, and so that we can paginate it (if adding on the community, then doing getUserMeta returns all results, so it won't be paginaged, and we also cannot search for it).
 * By having the format community:status, we can still use getUserMeta to filter all members that fulfil a requirement (eg: all approved users)
 */

// Store an array in different non-unique rows
$cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
if ($cmsapplicationapi->isAdminPanel()) {
    // Only execute in the back-end: when editing the Communities values using ACF,
    // then we gotta transform and also save these values for the template-manager needed format
    HooksAPIFacade::getInstance()->addFilter('acf/update_value', 'gdUreAcfCommunitymembershipUpdateCustomformat', 10, 3);
}
function gdUreAcfCommunitymembershipUpdateCustomformat($value, $post_id, $field)
{
    $key = $field['name'];
    // if (in_array($key, gd_ure_acf_get_communitymembership_keys()) && $value) {
    if ($key == GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSHIP) {
        $subfields = $field['sub_fields'];
        $keys = array();
        foreach ($subfields as $subfield) {
            $keys[$subfield['name']] = $subfield['key'];
        }

        $customformat_status = $customformat_privileges = $customformat_tags = array();
        if ($value) {
            foreach ($value as $index => $entry) {
                $community = $entry[$keys[GD_URE_METAKEY_PROFILE_COMMUNITIES_COMMUNITY]];
                $status = $entry[$keys[GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS]];
                $privileges = $entry[$keys[GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES]];
                $tags = $entry[$keys[GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS]];

                // If the user clicked on '- Select -' in ACF it gives a value empty, so filter out this case using array_filter
                if (!is_array($privileges)) {
                    $privileges = array($privileges);
                }
                $privileges = array_filter($privileges);
                if (!is_array($tags)) {
                    $tags = array($tags);
                }
                $tags = array_filter($tags);

                // If the privileges or tags are empty, add 'empty' value
                // This is so that we can filter by this value on My Members' filter
                $customformat_status[] = gdUreGetCommunityMetavalue($community, $status);
                if ($privileges) {
                    foreach ($privileges as $privilege) {
                        $customformat_privileges[] = gdUreGetCommunityMetavalue($community, $privilege);
                    }
                } else {
                    $customformat_privileges[] = gdUreGetCommunityMetavalue($community, GD_METAVALUE_NONE);
                }
                if ($tags) {
                    foreach ($tags as $tag) {
                        $customformat_tags[] = gdUreGetCommunityMetavalue($community, $tag);
                    }
                } else {
                    $customformat_tags[] = gdUreGetCommunityMetavalue($community, GD_METAVALUE_NONE);
                }
            }
        }
        
        $user_id = str_replace('user_', '', $post_id);
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS, $customformat_status);
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES, $customformat_privileges);
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS, $customformat_tags);
    }

    return $value;
}

// These will be executed in the front-end, not in the back-end:
// Transform from the custom format into ACF
HooksAPIFacade::getInstance()->addAction('ure:user:add_new_communities', 'gdUreAcfUserAddnewcommunities', 10, 2);
function gdUreAcfUserAddnewcommunities($user_id, $communities)
{

    // Default values for when adding a new user to the community
    $status = GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE;
    $privileges = array(
        GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERPRIVILEGES_CONTRIBUTECONTENT
    );
    $tags = array(
        GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERTAGS_MEMBER
    );

    gdUreAcfUserUpdatecommunitiesmembership($user_id, $communities, $status, $privileges, $tags);
}

HooksAPIFacade::getInstance()->addAction('GD_EditMembership:update', 'gdUreAcfUserCommunitymembershipUpdate', 10, 5);
function gdUreAcfUserCommunitymembershipUpdate($user_id, $community, $status, $privileges, $tags)
{
    gdUreAcfUserUpdatecommunitiesmembership($user_id, array($community), $status, $privileges, $tags);
}
function gdUreAcfUserUpdatecommunitiesmembership($user_id, $communities, $status, $privileges, $tags)
{
    $acf_user_id = 'user_'.$user_id;

    // Taken from http://www.advancedcustomfields.com/resources/update_field/
    $value = get_field(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSHIP, $acf_user_id);

    $update = true;
    if (!$value) {
        $update = false;
        $value = array();
    }

    // The entries for the current community might or might not exist: this function will be called both when the user is first created
    // (entries will be new) or when editing the user membership
    $found = array();
    foreach ($value as $index => $entry) {
        if (in_array($entry[GD_URE_METAKEY_PROFILE_COMMUNITIES_COMMUNITY]['ID'], $communities)) {
            array_unshift($found, $index);
            continue;
        }
    }

    // Remove the current value(s) for this one community (should be only 1 value, however anyone on the back-end configuring through ACF could add the entry twice by mistake)
    foreach ($found as $found_index) {
        array_splice($value, $found_index, 1);
    }
    
    // Add again the settings for these communities
    foreach ($communities as $community) {
        $value[] = array(
            GD_URE_METAKEY_PROFILE_COMMUNITIES_COMMUNITY => array(
                'ID' => $community
            ),
            // GD_URE_METAKEY_PROFILE_COMMUNITIES_COMMUNITY => gd_acf_get_formatteduser_from_id($community),
            GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS => $status,
            GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES => $privileges,
            GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS => $tags,
        );
    }

    // When creating a new user: if it is empty, we gotta create the value first
    // Taken from advanced-custom-fields/core/input.php function save_post( $post_id = 0 )
    // And called in function acfSavePost( $post_id = 0 ) {
    // HooksAPIFacade::getInstance()->doAction('acf/save_post', $post_id);
    // }
    if ($update) {
        update_field(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSHIP, $value, $acf_user_id);
    } else {
        $field = acf_get_field(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSHIP);
        acf_update_value($value, $acf_user_id, $field);
    }
}
