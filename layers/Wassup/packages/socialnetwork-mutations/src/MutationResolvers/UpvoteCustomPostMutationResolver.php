<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\Root\App;
use PoPCMSSchema\UserMeta\Utils;

class UpvoteCustomPostMutationResolver extends AbstractUpvoteOrUndoUpvoteCustomPostMutationResolver
{
    private ?DownvoteCustomPostMutationResolver $downvoteCustomPostMutationResolver = null;

    final public function setDownvoteCustomPostMutationResolver(DownvoteCustomPostMutationResolver $downvoteCustomPostMutationResolver): void
    {
        $this->downvoteCustomPostMutationResolver = $downvoteCustomPostMutationResolver;
    }
    final protected function getDownvoteCustomPostMutationResolver(): DownvoteCustomPostMutationResolver
    {
        return $this->downvoteCustomPostMutationResolver ??= $this->instanceManager->getInstance(DownvoteCustomPostMutationResolver::class);
    }

    public function validateErrors(array $form_data): array
    {
        $errors = parent::validateErrors($form_data);
        if (!$errors) {
            $user_id = App::getState('current-user-id');
            $target_id = $form_data['target_id'];

            // Check that the logged in user has not already recommended this post
            $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_UPVOTESPOSTS);
            if (in_array($target_id, $value)) {
                $errors[] = sprintf(
                    $this->__('You have already up-voted <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
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
        App::doAction('gd_upvotepost', $target_id, $form_data);
    }

    /**
     * @param array<string,mixed> $form_data
     * @throws \PoP\Root\Exception\AbstractException In case of error
     */
    protected function update(array $form_data): string | int
    {
        $user_id = App::getState('current-user-id');
        $target_id = $form_data['target_id'];

        // Update value
        Utils::addUserMeta($user_id, \GD_METAKEY_PROFILE_UPVOTESPOSTS, $target_id);
        \PoPCMSSchema\CustomPostMeta\Utils::addCustomPostMeta($target_id, \GD_METAKEY_POST_UPVOTEDBY, $user_id);

        // Update the counter
        $count = \PoPCMSSchema\CustomPostMeta\Utils::getCustomPostMeta($target_id, \GD_METAKEY_POST_UPVOTECOUNT, true);
        $count = $count ? $count : 0;
        \PoPCMSSchema\CustomPostMeta\Utils::updateCustomPostMeta($target_id, \GD_METAKEY_POST_UPVOTECOUNT, ($count + 1), true);

        // Had the user already executed the opposite (Up-vote => Down-vote, etc), then undo it
        $opposite = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_DOWNVOTESPOSTS);
        if (in_array($target_id, $opposite)) {
            $this->getDownvoteCustomPostMutationResolver()->executeMutation($form_data);
        }

        return parent::update($form_data);
    }
}
