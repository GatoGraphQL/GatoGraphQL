<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoPSchema\UserMeta\Utils;
use PoP\ApplicationTaxonomies\FunctionAPIFactory;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;

class UnsubscribeFromTagMutationResolver extends AbstractSubscribeToOrUnsubscribeFromTagMutationResolver
{
    public function validateErrors(array $form_data): ?array
    {
        $errors = parent::validateErrors($form_data);
        if (!$errors) {
            $vars = ApplicationState::getVars();
            $user_id = $vars['global-userstate']['current-user-id'];
            $target_id = $form_data['target_id'];

            // Check that the logged in user is currently subscribed to that tag
            $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS);
            if (!in_array($target_id, $value)) {
                $applicationtaxonomyapi = FunctionAPIFactory::getInstance();
                $tag = $this->postTagTypeAPI->getTag($target_id);
                $errors[] = sprintf(
                    $this->translationAPI->__('You had not subscribed to <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
                    $applicationtaxonomyapi->getTagSymbolName($tag)
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
        $this->hooksAPI->doAction('gd_unsubscribefromtag', $target_id, $form_data);
    }

    protected function update($form_data): string | int
    {
        $vars = ApplicationState::getVars();
        $user_id = $vars['global-userstate']['current-user-id'];
        $target_id = $form_data['target_id'];

        // Update value
        Utils::deleteUserMeta($user_id, \GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS, $target_id);
        \PoPSchema\TaxonomyMeta\Utils::deleteTermMeta($target_id, \GD_METAKEY_TERM_SUBSCRIBEDBY, $user_id);

        // Update the counter
        $count = \PoPSchema\TaxonomyMeta\Utils::getTermMeta($target_id, \GD_METAKEY_TERM_SUBSCRIBERSCOUNT, true);
        $count = $count ? $count : 0;
        \PoPSchema\TaxonomyMeta\Utils::updateTermMeta($target_id, \GD_METAKEY_TERM_SUBSCRIBERSCOUNT, ($count - 1), true);

        return parent::update($form_data);
    }
}
