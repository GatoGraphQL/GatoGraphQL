<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\UserMeta\Utils;

class FollowUserMutationResolver extends AbstractFollowOrUnfollowUserMutationResolver
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

        if ($user_id == $target_id) {
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
            $errors = [];
            $errors[] = $this->__('You can\'t follow yourself!', 'pop-coreprocessors');
        } else {
            // Check that the logged in user does not currently follow that user
            $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_FOLLOWSUSERS);
            if (in_array($target_id, $value)) {
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
                    $this->__('You are already following <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
                    $this->getUserTypeAPI()->getUserDisplayName($target_id)
                );
            }
        }
    }

    /**
     * Function to override
     */
    protected function additionals($target_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::additionals($target_id, $fieldDataAccessor);
        App::doAction('gd_followuser', $target_id, $fieldDataAccessor);
    }

    // protected function updateValue($value, \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor) {
    //     // Add the user to follow to the list
    //     $target_id = $fieldDataAccessor->getValue('target_id');
    //     $value[] = $target_id;
    // }
    /**
     * @throws AbstractException In case of error
     */
    protected function update(FieldDataAccessorInterface $fieldDataAccessor): string|int
    {
        $user_id = App::getState('current-user-id');
        $target_id = $fieldDataAccessor->getValue('target_id');

        // Comment Leo 02/10/2015: added redundant values, so that we can query for both "Who are my followers" and "Who I am following"
        // and make both searchable and with pagination
        // Update values
        Utils::addUserMeta($user_id, \GD_METAKEY_PROFILE_FOLLOWSUSERS, $target_id);
        Utils::addUserMeta($target_id, \GD_METAKEY_PROFILE_FOLLOWEDBY, $user_id);

        // Update the counter
        $count = Utils::getUserMeta($target_id, \GD_METAKEY_PROFILE_FOLLOWERSCOUNT, true);
        $count = $count ? $count : 0;
        Utils::updateUserMeta($target_id, \GD_METAKEY_PROFILE_FOLLOWERSCOUNT, ($count + 1), true);

        return parent::update($fieldDataAccessor);
    }
}
