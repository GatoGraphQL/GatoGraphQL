<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\UserMeta\Utils;

class DownvoteCustomPostMutationResolver extends AbstractDownvoteOrUndoDownvoteCustomPostMutationResolver
{
    private ?UpvoteCustomPostMutationResolver $upvoteCustomPostMutationResolver = null;

    final public function setUpvoteCustomPostMutationResolver(UpvoteCustomPostMutationResolver $upvoteCustomPostMutationResolver): void
    {
        $this->upvoteCustomPostMutationResolver = $upvoteCustomPostMutationResolver;
    }
    final protected function getUpvoteCustomPostMutationResolver(): UpvoteCustomPostMutationResolver
    {
        return $this->upvoteCustomPostMutationResolver ??= $this->instanceManager->getInstance(UpvoteCustomPostMutationResolver::class);
    }

    public function validateErrors(array $form_data): array
    {
        $errors = parent::validateErrors($form_data);
        if (!$errors) {
            $vars = ApplicationState::getVars();
            $user_id = $vars['current-user-id'];
            $target_id = $form_data['target_id'];

            // Check that the logged in user has not already recommended this post
            $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_DOWNVOTESPOSTS);
            if (in_array($target_id, $value)) {
                $errors[] = sprintf(
                    $this->__('You have already down-voted <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
                    $this->getCustomPostTypeAPI()->getTitle($target_id)
                );
            }
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($target_id, $form_data): void
    {
        parent::additionals($target_id, $form_data);
        $this->getHooksAPI()->doAction('gd_downvotepost', $target_id, $form_data);
    }

    protected function update($form_data): string | int
    {
        $vars = ApplicationState::getVars();
        $user_id = $vars['current-user-id'];
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
            $this->getUpvoteCustomPostMutationResolver()->executeMutation($form_data);
        }

        return parent::update($form_data);
    }
}
