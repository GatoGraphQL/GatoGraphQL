<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoPCMSSchema\UserMeta\Utils;

class UnrecommendCustomPostMutationResolver extends AbstractRecommendOrUnrecommendCustomPostMutationResolver
{
    public function validateErrors(FieldDataAccessorInterface $fieldDataProvider): array
    {
        $errors = parent::validateErrors($fieldDataProvider);
        if (!$errors) {
            $user_id = App::getState('current-user-id');
            $target_id = $fieldDataProvider->get('target_id');

            // Check that the logged in user does currently follow that user
            $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_RECOMMENDSPOSTS);
            if (!in_array($target_id, $value)) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = sprintf(
                    $this->__('You had not recommended <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
                    $this->getCustomPostTypeAPI()->getTitle($target_id)
                );
            }
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($target_id, FieldDataAccessorInterface $fieldDataProvider): void
    {
        parent::additionals($target_id, $fieldDataProvider);
        App::doAction('gd_unrecommendpost', $target_id, $fieldDataProvider);
    }

    // protected function updateValue($value, \PoP\ComponentModel\Mutation\FieldDataAccessorInterface $fieldDataProvider) {
    //     // Remove the user from the list
    //     $target_id = $fieldDataProvider->get('target_id');
    //     array_splice($value, array_search($target_id, $value), 1);
    // }
    /**
     * @throws AbstractException In case of error
     */
    protected function update(FieldDataAccessorInterface $fieldDataProvider): string | int
    {
        $user_id = App::getState('current-user-id');
        $target_id = $fieldDataProvider->get('target_id');

        // Update value
        Utils::deleteUserMeta($user_id, \GD_METAKEY_PROFILE_RECOMMENDSPOSTS, $target_id);
        \PoPCMSSchema\CustomPostMeta\Utils::deleteCustomPostMeta($target_id, \GD_METAKEY_POST_RECOMMENDEDBY, $user_id);

        // Update the count
        $count = \PoPCMSSchema\CustomPostMeta\Utils::getCustomPostMeta($target_id, \GD_METAKEY_POST_RECOMMENDCOUNT, true);
        $count = $count ? $count : 0;
        \PoPCMSSchema\CustomPostMeta\Utils::updateCustomPostMeta($target_id, \GD_METAKEY_POST_RECOMMENDCOUNT, ($count - 1), true);

        return parent::update($fieldDataProvider);
    }
}
