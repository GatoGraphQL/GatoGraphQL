<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\UserMeta\Utils;

class RecommendCustomPostMutationResolver extends AbstractRecommendOrUnrecommendCustomPostMutationResolver
{
    public function validateErrors(array $form_data): array
    {
        $errors = parent::validateErrors($form_data);
        if (!$errors) {
            $vars = ApplicationState::getVars();
            $user_id = $vars['current-user-id'];
            $target_id = $form_data['target_id'];

            // Check that the logged in user has not already recommended this post
            $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_RECOMMENDSPOSTS);
            if (in_array($target_id, $value)) {
                $errors[] = sprintf(
                    $this->__('You have already recommended <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
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
        $this->getHooksAPI()->doAction('gd_recommendpost', $target_id, $form_data);
    }

    // protected function updateValue($value, $form_data) {

    //     // Add the user to follow to the list
    //     $target_id = $form_data['target_id'];
    //     $value[] = $target_id;
    // }

    protected function update($form_data): string | int
    {
        $vars = ApplicationState::getVars();
        $user_id = $vars['current-user-id'];
        $target_id = $form_data['target_id'];

        // Update value
        Utils::addUserMeta($user_id, \GD_METAKEY_PROFILE_RECOMMENDSPOSTS, $target_id);
        \PoPSchema\CustomPostMeta\Utils::addCustomPostMeta($target_id, \GD_METAKEY_POST_RECOMMENDEDBY, $user_id);

        // Update the counter
        $count = \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($target_id, \GD_METAKEY_POST_RECOMMENDCOUNT, true);
        $count = $count ? $count : 0;
        \PoPSchema\CustomPostMeta\Utils::updateCustomPostMeta($target_id, \GD_METAKEY_POST_RECOMMENDCOUNT, ($count + 1), true);

        return parent::update($form_data);
    }
}
