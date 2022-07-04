<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoPCMSSchema\UserMeta\Utils;

class UndoDownvoteCustomPostMutationResolver extends AbstractDownvoteOrUndoDownvoteCustomPostMutationResolver
{
    public function validateErrors(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $errors = parent::validateErrors($fieldDataAccessor);
        if (!$errors) {
            $user_id = App::getState('current-user-id');
            $target_id = $fieldDataAccessor->get('target_id');

            // Check that the logged in user does currently follow that user
            $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_DOWNVOTESPOSTS);
            if (!in_array($target_id, $value)) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = sprintf(
                    $this->__('You had not down-voted <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
                    $this->getCustomPostTypeAPI()->getTitle($target_id)
                );
            }
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($target_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::additionals($target_id, $fieldDataAccessor);
        App::doAction('gd_undodownvotepost', $target_id, $fieldDataAccessor);
    }

    /**
     * @throws AbstractException In case of error
     */
    protected function update(FieldDataAccessorInterface $fieldDataAccessor): string | int
    {
        $user_id = App::getState('current-user-id');
        $target_id = $fieldDataAccessor->get('target_id');

        // Update value
        Utils::deleteUserMeta($user_id, \GD_METAKEY_PROFILE_DOWNVOTESPOSTS, $target_id);
        \PoPCMSSchema\CustomPostMeta\Utils::deleteCustomPostMeta($target_id, \GD_METAKEY_POST_DOWNVOTEDBY, $user_id);

        // Update the count
        $count = \PoPCMSSchema\CustomPostMeta\Utils::getCustomPostMeta($target_id, \GD_METAKEY_POST_DOWNVOTECOUNT, true);
        $count = $count ? $count : 0;
        \PoPCMSSchema\CustomPostMeta\Utils::updateCustomPostMeta($target_id, \GD_METAKEY_POST_DOWNVOTECOUNT, ($count - 1), true);

        return parent::update($fieldDataAccessor);
    }

    /**
     * Function to be called by the opposite function (Up-vote/Down-vote)
     */
    public function undo(FieldDataAccessorInterface $fieldDataAccessor)
    {
        return $this->update($fieldDataAccessor);
    }
}
