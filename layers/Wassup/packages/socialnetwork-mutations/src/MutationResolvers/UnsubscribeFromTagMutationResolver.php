<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\ApplicationTaxonomies\FunctionAPIFactory;
use PoPCMSSchema\UserMeta\Utils;

class UnsubscribeFromTagMutationResolver extends AbstractSubscribeToOrUnsubscribeFromTagMutationResolver
{
    public function validateErrors(MutationDataProviderInterface $mutationDataProvider): array
    {
        $errors = parent::validateErrors($mutationDataProvider);
        if (!$errors) {
            $user_id = App::getState('current-user-id');
            $target_id = $mutationDataProvider->getValue('target_id');

            // Check that the logged in user is currently subscribed to that tag
            $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS);
            if (!in_array($target_id, $value)) {
                $applicationtaxonomyapi = FunctionAPIFactory::getInstance();
                $tag = $this->getPostTagTypeAPI()->getTag($target_id);
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = sprintf(
                    $this->__('You had not subscribed to <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
                    $applicationtaxonomyapi->getTagSymbolName($tag)
                );
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
        App::doAction('gd_unsubscribefromtag', $target_id, $mutationDataProvider);
    }

    /**
     * @throws AbstractException In case of error
     */
    protected function update(MutationDataProviderInterface $mutationDataProvider): string | int
    {
        $user_id = App::getState('current-user-id');
        $target_id = $mutationDataProvider->getValue('target_id');

        // Update value
        Utils::deleteUserMeta($user_id, \GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS, $target_id);
        \PoPCMSSchema\TaxonomyMeta\Utils::deleteTermMeta($target_id, \GD_METAKEY_TERM_SUBSCRIBEDBY, $user_id);

        // Update the counter
        $count = \PoPCMSSchema\TaxonomyMeta\Utils::getTermMeta($target_id, \GD_METAKEY_TERM_SUBSCRIBERSCOUNT, true);
        $count = $count ? $count : 0;
        \PoPCMSSchema\TaxonomyMeta\Utils::updateTermMeta($target_id, \GD_METAKEY_TERM_SUBSCRIBERSCOUNT, ($count - 1), true);

        return parent::update($mutationDataProvider);
    }
}
