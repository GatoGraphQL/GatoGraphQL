<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoPCMSSchema\UserMeta\Utils;

class UnfollowUserMutationResolver extends AbstractFollowOrUnfollowUserMutationResolver
{
    public function validateErrors(array $form_data): array
    {
        $errors = parent::validateErrors($form_data);
        if (!$errors) {
            $user_id = App::getState('current-user-id');
            $target_id = $form_data['target_id'];

            // Check that the logged in user does currently follow that user
            $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_FOLLOWSUSERS);
            if (!in_array($target_id, $value)) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = sprintf(
                    $this->__('You were not following <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
                    $this->getUserTypeAPI()->getUserDisplayName($target_id)
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
        App::doAction('gd_unfollowuser', $target_id, $form_data);
    }

    // protected function updateValue($value, $form_data) {
    //     // Remove the user from the list
    //     $target_id = $form_data['target_id'];
    //     array_splice($value, array_search($target_id, $value), 1);
    // }
    /**
     * @param array<string,mixed> $form_data
     * @throws AbstractException In case of error
     */
    protected function update(array $form_data): string | int
    {
        $user_id = App::getState('current-user-id');
        $target_id = $form_data['target_id'];

        // Update values
        Utils::deleteUserMeta($user_id, \GD_METAKEY_PROFILE_FOLLOWSUSERS, $target_id);
        Utils::deleteUserMeta($target_id, \GD_METAKEY_PROFILE_FOLLOWEDBY, $user_id);

        // Update the counter
        $count = Utils::getUserMeta($target_id, \GD_METAKEY_PROFILE_FOLLOWERSCOUNT, true);
        $count = $count ? $count : 0;
        Utils::updateUserMeta($target_id, \GD_METAKEY_PROFILE_FOLLOWERSCOUNT, ($count - 1), true);

        return parent::update($form_data);
    }
}
