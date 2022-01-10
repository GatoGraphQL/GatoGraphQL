<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\UserMeta\Utils;

class FollowUserMutationResolver extends AbstractFollowOrUnfollowUserMutationResolver
{
    public function validateErrors(array $form_data): array
    {
        $errors = parent::validateErrors($form_data);
        if (!$errors) {
            $vars = ApplicationState::getVars();
            $user_id = $vars['current-user-id'];
            $target_id = $form_data['target_id'];

            if ($user_id == $target_id) {
                $errors[] = $this->__('You can\'t follow yourself!', 'pop-coreprocessors');
            } else {
                // Check that the logged in user does not currently follow that user
                $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_FOLLOWSUSERS);
                if (in_array($target_id, $value)) {
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
    protected function additionals($target_id, $form_data): void
    {
        parent::additionals($target_id, $form_data);
        $this->getHooksAPI()->doAction('gd_followuser', $target_id, $form_data);
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

        // Comment Leo 02/10/2015: added redundant values, so that we can query for both "Who are my followers" and "Who I am following"
        // and make both searchable and with pagination
        // Update values
        Utils::addUserMeta($user_id, \GD_METAKEY_PROFILE_FOLLOWSUSERS, $target_id);
        Utils::addUserMeta($target_id, \GD_METAKEY_PROFILE_FOLLOWEDBY, $user_id);

        // Update the counter
        $count = Utils::getUserMeta($target_id, \GD_METAKEY_PROFILE_FOLLOWERSCOUNT, true);
        $count = $count ? $count : 0;
        Utils::updateUserMeta($target_id, \GD_METAKEY_PROFILE_FOLLOWERSCOUNT, ($count + 1), true);

        return parent::update($form_data);
    }
}
