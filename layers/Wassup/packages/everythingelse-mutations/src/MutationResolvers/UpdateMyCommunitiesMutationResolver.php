<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\MutationResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class UpdateMyCommunitiesMutationResolver extends AbstractMutationResolver
{
    /**
     * @return mixed
     */
    public function execute(array $form_data)
    {
        $user_id = $form_data['user_id'];

        $previous_communities = gdUreGetCommunities($user_id);
        $communities = $form_data['communities'];
        // $maybe_new_communities = array_diff($communities, $previous_communities);
        $new_communities = array();

        $status = \PoPSchema\UserMeta\Utils::getUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);

        // Check all the $maybe_new_communities and double check they are not banned
        foreach ($communities as $maybe_new_community) {
            // Empty metavalue => it's new
            $community_status = gdUreFindCommunityMetavalues($maybe_new_community, $status);
            if (empty($community_status)) {
                $new_communities[] = $maybe_new_community;
            }
        }

        // Set the new communities
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES, $communities);

        // Set the privileges/tags for the new communities
        gdUreUserAddnewcommunities($user_id, $new_communities);

        // Keep the previous values as to analyse which communities are new and send an email only to those
        $operationlog = array(
            'previous-communities' => $previous_communities,
            'new-communities' => $new_communities,
            'communities' => $communities,
        );

        // Allow to send an email before the update: get the current communities, so we know which ones are new
        HooksAPIFacade::getInstance()->doAction('gd_update_mycommunities:update', $user_id, $form_data, $operationlog);

        return $user_id;
        // Update: either updated or no banned communities (even if nothing changed, tell the user update was successful)
        // return $update || empty($banned_communities);
    }

    public function validateErrors(array $form_data): ?array
    {
        $errors = [];
        $user_id = $form_data['user_id'];

        // Validate the Community doesn't belong to itself as a member
        if (in_array($user_id, $form_data['communities'])) {
            $errors[] = TranslationAPIFacade::getInstance()->__('You are not allowed to be a member of yourself!', 'ure-pop');
        }
        return $errors;
    }

    public function validateWarnings(array $form_data): ?array
    {
        $warnings = [];
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $user_id = $form_data['user_id'];
        $status = \PoPSchema\UserMeta\Utils::getUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);
        $communities = $form_data['communities'];
        $banned_communities = array();

        // Check all the $maybe_new_communities and double check they are not banned
        foreach ($communities as $maybe_new_community) {
            $community_status = gdUreFindCommunityMetavalues($maybe_new_community, $status);
            // Check if this user was banned by the community
            if (!empty($community_status) && in_array(GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_REJECTED, $community_status)) {
                $banned_communities[] = $maybe_new_community;
            }
        }

        // If there are banned communities, show the corresponding error to the user
        if ($banned_communities) {
            $banned_communities_html = array();
            foreach ($banned_communities as $banned_community) {
                $banned_communities_html[] = sprintf(
                    '<a href="%s">%s</a>',
                    $cmsusersapi->getUserURL($banned_community),
                    $cmsusersapi->getUserDisplayName($banned_community)
                );
            }
            $warnings[] = sprintf(
                TranslationAPIFacade::getInstance()->__('The following Community(ies) will not be active, since they claim you are not their member: %s.', 'ure-pop'),
                implode(', ', $banned_communities_html)
            );
        }

        return $warnings;
    }
}

