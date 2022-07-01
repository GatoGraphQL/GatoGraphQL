<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoPCMSSchema\UserMeta\Utils;

class FollowUserMutationResolver extends AbstractFollowOrUnfollowUserMutationResolver
{
    public function validateErrors(MutationDataProviderInterface $mutationDataProvider): array
    {
        $errors = parent::validateErrors($mutationDataProvider);
        if (!$errors) {
            $user_id = App::getState('current-user-id');
            $target_id = $mutationDataProvider->get('target_id');

            if ($user_id == $target_id) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = $this->__('You can\'t follow yourself!', 'pop-coreprocessors');
            } else {
                // Check that the logged in user does not currently follow that user
                $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_FOLLOWSUSERS);
                if (in_array($target_id, $value)) {
                    // @todo Migrate from string to FeedbackItemProvider
                    // $errors[] = new FeedbackItemResolution(
                    //     MutationErrorFeedbackItemProvider::class,
                    //     MutationErrorFeedbackItemProvider::E1,
                    // );
                    $errors[] = sprintf(
                        $this->__('You are already following <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
                        $this->getUserTypeAPI()->getUserDisplayName($target_id)
                    );
                }
            }
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($target_id, MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::additionals($target_id, $mutationDataProvider);
        App::doAction('gd_followuser', $target_id, $mutationDataProvider);
    }

    // protected function updateValue($value, \PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider) {
    //     // Add the user to follow to the list
    //     $target_id = $mutationDataProvider->get('target_id');
    //     $value[] = $target_id;
    // }
    /**
     * @throws AbstractException In case of error
     */
    protected function update(MutationDataProviderInterface $mutationDataProvider): string | int
    {
        $user_id = App::getState('current-user-id');
        $target_id = $mutationDataProvider->get('target_id');

        // Comment Leo 02/10/2015: added redundant values, so that we can query for both "Who are my followers" and "Who I am following"
        // and make both searchable and with pagination
        // Update values
        Utils::addUserMeta($user_id, \GD_METAKEY_PROFILE_FOLLOWSUSERS, $target_id);
        Utils::addUserMeta($target_id, \GD_METAKEY_PROFILE_FOLLOWEDBY, $user_id);

        // Update the counter
        $count = Utils::getUserMeta($target_id, \GD_METAKEY_PROFILE_FOLLOWERSCOUNT, true);
        $count = $count ? $count : 0;
        Utils::updateUserMeta($target_id, \GD_METAKEY_PROFILE_FOLLOWERSCOUNT, ($count + 1), true);

        return parent::update($mutationDataProvider);
    }
}
