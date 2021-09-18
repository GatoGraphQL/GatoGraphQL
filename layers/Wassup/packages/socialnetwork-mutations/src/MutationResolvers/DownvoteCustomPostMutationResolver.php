<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\UserMeta\Utils;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UpvoteCustomPostMutationResolver;

class DownvoteCustomPostMutationResolver extends AbstractDownvoteOrUndoDownvoteCustomPostMutationResolver
{
    public function validateErrors(array $form_data): ?array
    {
        $errors = parent::validateErrors($form_data);
        if (!$errors) {
            $vars = ApplicationState::getVars();
            $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
            $user_id = $vars['global-userstate']['current-user-id'];
            $target_id = $form_data['target_id'];

            // Check that the logged in user has not already recommended this post
            $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_DOWNVOTESPOSTS);
            if (in_array($target_id, $value)) {
                $errors[] = sprintf(
                    $this->translationAPI->__('You have already down-voted <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
                    $customPostTypeAPI->getTitle($target_id)
                );
            }
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($target_id, $form_data)
    {
        parent::additionals($target_id, $form_data);
        $this->hooksAPI->doAction('gd_downvotepost', $target_id, $form_data);
    }

    protected function getOppositeInstance()
    {
        return new GD_UndoUpvotePost();
    }

    protected function update($form_data): string | int
    {
        $vars = ApplicationState::getVars();
        $user_id = $vars['global-userstate']['current-user-id'];
        $target_id = $form_data['target_id'];

        // Update value
        Utils::addUserMeta($user_id, \GD_METAKEY_PROFILE_DOWNVOTESPOSTS, $target_id);
        \PoPSchema\CustomPostMeta\Utils::addCustomPostMeta($target_id, \GD_METAKEY_POST_DOWNVOTEDBY, $user_id);

        // Update the counter
        $count = \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($target_id, \GD_METAKEY_POST_DOWNVOTECOUNT, true);
        $count = $count ? $count : 0;
        \PoPSchema\CustomPostMeta\Utils::updateCustomPostMeta($target_id, \GD_METAKEY_POST_DOWNVOTECOUNT, ($count + 1), true);

        // Had the user already executed the opposite (Up-vote => Down-vote, etc), then undo it
        $opposite = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_UPVOTESPOSTS);
        if (in_array($target_id, $opposite)) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /**
             * @var UpvoteCustomPostMutationResolver
             */
            $opposite_instance = $instanceManager->getInstance(UpvoteCustomPostMutationResolver::class);
            $opposite_instance->executeMutation($form_data);
        }

        return parent::update($form_data);
    }
}
