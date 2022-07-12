<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\UserMeta\Utils;

class UnfollowUserMutationResolver extends AbstractFollowOrUnfollowUserMutationResolver
{
    public function validateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        parent::validateErrors($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return;
        }

        $user_id = App::getState('current-user-id');
        $target_id = $fieldDataAccessor->getValue('target_id');

        // Check that the logged in user does currently follow that user
        $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_FOLLOWSUSERS);
        if (!in_array($target_id, $value)) {
            // @todo Migrate from string to FeedbackItemProvider
        // $objectTypeFieldResolutionFeedbackStore->addError(
        //     new ObjectTypeFieldResolutionFeedback(
        //         new FeedbackItemResolution(
        //             MutationErrorFeedbackItemProvider::class,
        //             MutationErrorFeedbackItemProvider::E1,
        //         ),
        //         $fieldDataAccessor->getField(),
        //     )
        // );
            $errors[] = sprintf(
                $this->__('You were not following <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
                $this->getUserTypeAPI()->getUserDisplayName($target_id)
            );
        }
    }

    /**
     * Function to override
     */
    protected function additionals($target_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::additionals($target_id, $fieldDataAccessor);
        App::doAction('gd_unfollowuser', $target_id, $fieldDataAccessor);
    }

    // protected function updateValue($value, \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor) {
    //     // Remove the user from the list
    //     $target_id = $fieldDataAccessor->getValue('target_id');
    //     array_splice($value, array_search($target_id, $value), 1);
    // }
    /**
     * @throws AbstractException In case of error
     */
    protected function update(FieldDataAccessorInterface $fieldDataAccessor): string | int
    {
        $user_id = App::getState('current-user-id');
        $target_id = $fieldDataAccessor->getValue('target_id');

        // Update values
        Utils::deleteUserMeta($user_id, \GD_METAKEY_PROFILE_FOLLOWSUSERS, $target_id);
        Utils::deleteUserMeta($target_id, \GD_METAKEY_PROFILE_FOLLOWEDBY, $user_id);

        // Update the counter
        $count = Utils::getUserMeta($target_id, \GD_METAKEY_PROFILE_FOLLOWERSCOUNT, true);
        $count = $count ? $count : 0;
        Utils::updateUserMeta($target_id, \GD_METAKEY_PROFILE_FOLLOWERSCOUNT, ($count - 1), true);

        return parent::update($fieldDataAccessor);
    }
}
