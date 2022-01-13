<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\Root\App;
use PoPSchema\UserMeta\Utils;

class UnrecommendCustomPostMutationResolver extends AbstractRecommendOrUnrecommendCustomPostMutationResolver
{
    public function validateErrors(array $form_data): array
    {
        $errors = parent::validateErrors($form_data);
        if (!$errors) {
            $user_id = App::getState('current-user-id');
            $target_id = $form_data['target_id'];

            // Check that the logged in user does currently follow that user
            $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_RECOMMENDSPOSTS);
            if (!in_array($target_id, $value)) {
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
    protected function additionals($target_id, $form_data): void
    {
        parent::additionals($target_id, $form_data);
        App::doAction('gd_unrecommendpost', $target_id, $form_data);
    }

    // protected function updateValue($value, $form_data) {

    //     // Remove the user from the list
    //     $target_id = $form_data['target_id'];
    //     array_splice($value, array_search($target_id, $value), 1);
    // }

    protected function update($form_data): string | int
    {
        $user_id = App::getState('current-user-id');
        $target_id = $form_data['target_id'];

        // Update value
        Utils::deleteUserMeta($user_id, \GD_METAKEY_PROFILE_RECOMMENDSPOSTS, $target_id);
        \PoPSchema\CustomPostMeta\Utils::deleteCustomPostMeta($target_id, \GD_METAKEY_POST_RECOMMENDEDBY, $user_id);

        // Update the count
        $count = \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($target_id, \GD_METAKEY_POST_RECOMMENDCOUNT, true);
        $count = $count ? $count : 0;
        \PoPSchema\CustomPostMeta\Utils::updateCustomPostMeta($target_id, \GD_METAKEY_POST_RECOMMENDCOUNT, ($count - 1), true);

        return parent::update($form_data);
    }
}
