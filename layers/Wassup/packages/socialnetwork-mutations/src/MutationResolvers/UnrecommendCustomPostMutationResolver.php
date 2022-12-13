<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\UserMeta\Utils;

class UnrecommendCustomPostMutationResolver extends AbstractRecommendOrUnrecommendCustomPostMutationResolver
{
    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        parent::validate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return;
        }

        $user_id = App::getState('current-user-id');
        $target_id = $fieldDataAccessor->getValue('target_id');

        // Check that the logged in user does currently follow that user
        $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_RECOMMENDSPOSTS);
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
            $errors = [];
            $errors[] = sprintf(
                $this->__('You had not recommended <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
                $this->getCustomPostTypeAPI()->getTitle($target_id)
            );
        }
    }

    /**
     * Function to override
     */
    protected function additionals($target_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::additionals($target_id, $fieldDataAccessor);
        App::doAction('gd_unrecommendpost', $target_id, $fieldDataAccessor);
    }

    // protected function updateValue($value, \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor) {
    //     // Remove the user from the list
    //     $target_id = $fieldDataAccessor->getValue('target_id');
    //     array_splice($value, array_search($target_id, $value), 1);
    // }
    /**
     * @throws AbstractException In case of error
     */
    protected function update(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $user_id = App::getState('current-user-id');
        $target_id = $fieldDataAccessor->getValue('target_id');

        // Update value
        Utils::deleteUserMeta($user_id, \GD_METAKEY_PROFILE_RECOMMENDSPOSTS, $target_id);
        \PoPCMSSchema\CustomPostMeta\Utils::deleteCustomPostMeta($target_id, \GD_METAKEY_POST_RECOMMENDEDBY, $user_id);

        // Update the count
        $count = \PoPCMSSchema\CustomPostMeta\Utils::getCustomPostMeta($target_id, \GD_METAKEY_POST_RECOMMENDCOUNT, true);
        $count = $count ? $count : 0;
        \PoPCMSSchema\CustomPostMeta\Utils::updateCustomPostMeta($target_id, \GD_METAKEY_POST_RECOMMENDCOUNT, ($count - 1), true);

        return parent::update($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
