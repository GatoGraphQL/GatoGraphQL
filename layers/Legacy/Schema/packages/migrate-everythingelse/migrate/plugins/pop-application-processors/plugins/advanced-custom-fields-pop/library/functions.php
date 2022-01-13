<?php

/**
 * Follow users / recommend posts: the information is redundant, saving each entry on both entities (user/user and user/post)
 * So that both (eg: "Who I am following" and "Who are my followers") can be queried and with pagination
 * Priority 0: it executes before saving the value on the db, so we can first get the previous value and compare to get a delta of additions/deletions
 */
\PoP\Root\App::addFilter('acf/update_value', 'gd_acf_userfunctionalities_duplicatedata', 0, 3);
function gd_acf_userfunctionalities_duplicatedata($value, $post_id, $field)
{
    $key = $field['name'];
    $userfunction_keys = array(
        GD_METAKEY_PROFILE_FOLLOWSUSERS,
    );
    $postfunction_keys = array(
        GD_METAKEY_PROFILE_RECOMMENDSPOSTS,
        GD_METAKEY_PROFILE_UPVOTESPOSTS,
        GD_METAKEY_PROFILE_DOWNVOTESPOSTS,
    );
    $termfunction_keys = array(
        GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS,
    );
    if (in_array($key, $userfunction_keys) || in_array($key, $postfunction_keys) || in_array($key, $termfunction_keys)) {
        $user_id = str_replace('user_', '', $post_id);

        // Using $new_value because, when no values selected in ACF, $value is null and the comparison fails
        $new_value = $value ? $value : array();

        // Calculate the delta of additions/deletions
        $current_value = \PoPSchema\UserMeta\Utils::getUserMeta($user_id, $key);
        $additions = array_diff($new_value, $current_value);
        $deletions = array_diff($current_value, $new_value);

        $value_metakey = $count_metakey = '';
        if (in_array($key, $userfunction_keys)) {
            // For each one of this (user/post), add the current $user_id as the one who follows/recommends them
            if ($key == GD_METAKEY_PROFILE_FOLLOWSUSERS) {
                $value_metakey = GD_METAKEY_PROFILE_FOLLOWEDBY;
                $count_metakey = GD_METAKEY_PROFILE_FOLLOWERSCOUNT;
            }
            foreach ($additions as $target_id) {
                \PoPSchema\UserMeta\Utils::addUserMeta($target_id, $value_metakey, $user_id);

                // Update the counter
                $count = \PoPSchema\UserMeta\Utils::getUserMeta($target_id, $count_metakey, true);
                $count = $count ? $count : 0;
                \PoPSchema\UserMeta\Utils::updateUserMeta($target_id, $count_metakey, ($count + 1), true);
            }
            foreach ($deletions as $target_id) {
                \PoPSchema\UserMeta\Utils::deleteUserMeta($target_id, $value_metakey, $user_id);

                // Update the counter
                $count = \PoPSchema\UserMeta\Utils::getUserMeta($target_id, $count_metakey, true);
                $count = $count ? $count : 0;
                \PoPSchema\UserMeta\Utils::updateUserMeta($target_id, $count_metakey, ($count - 1), true);
            }
        } elseif (in_array($key, $postfunction_keys)) {
            if ($key == GD_METAKEY_PROFILE_RECOMMENDSPOSTS) {
                $value_metakey = GD_METAKEY_POST_RECOMMENDEDBY;
                $count_metakey = GD_METAKEY_POST_RECOMMENDCOUNT;
            } elseif ($key == GD_METAKEY_PROFILE_UPVOTESPOSTS) {
                $value_metakey = GD_METAKEY_POST_UPVOTEDBY;
                $count_metakey = GD_METAKEY_POST_UPVOTECOUNT;
            } elseif ($key == GD_METAKEY_PROFILE_DOWNVOTESPOSTS) {
                $value_metakey = GD_METAKEY_POST_DOWNVOTEDBY;
                $count_metakey = GD_METAKEY_POST_DOWNVOTECOUNT;
            }
            foreach ($additions as $target_id) {
                \PoPSchema\CustomPostMeta\Utils::addCustomPostMeta($target_id, $value_metakey, $user_id);

                // Update the counter
                $count = \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($target_id, $count_metakey, true);
                $count = $count ? $count : 0;
                \PoPSchema\CustomPostMeta\Utils::updateCustomPostMeta($target_id, $count_metakey, ($count + 1), true);
            }
            foreach ($deletions as $target_id) {
                \PoPSchema\CustomPostMeta\Utils::deleteCustomPostMeta($target_id, $value_metakey, $user_id);

                // Update the counter
                $count = \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($target_id, $count_metakey, true);
                $count = $count ? $count : 0;
                \PoPSchema\CustomPostMeta\Utils::updateCustomPostMeta($target_id, $count_metakey, ($count - 1), true);
            }
        } elseif (in_array($key, $termfunction_keys)) {
            // For each one of this (user/post), add the current $user_id as the one who follows/recommends them
            if ($key == GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS) {
                $value_metakey = GD_METAKEY_TERM_SUBSCRIBEDBY;
                $count_metakey = GD_METAKEY_TERM_SUBSCRIBERSCOUNT;
            }
            foreach ($additions as $target_id) {
                \PoPSchema\TaxonomyMeta\Utils::addTermMeta($target_id, $value_metakey, $user_id);

                // Update the counter
                $count = \PoPSchema\TaxonomyMeta\Utils::getTermMeta($target_id, $count_metakey, true);
                $count = $count ? $count : 0;
                \PoPSchema\TaxonomyMeta\Utils::updateTermMeta($target_id, $count_metakey, ($count + 1), true);
            }
            foreach ($deletions as $target_id) {
                \PoPSchema\TaxonomyMeta\Utils::deleteTermMeta($target_id, $value_metakey, $user_id);

                // Update the counter
                $count = \PoPSchema\TaxonomyMeta\Utils::getTermMeta($target_id, $count_metakey, true);
                $count = $count ? $count : 0;
                \PoPSchema\TaxonomyMeta\Utils::updateTermMeta($target_id, $count_metakey, ($count - 1), true);
            }
        }
    }

    return $value;
}
