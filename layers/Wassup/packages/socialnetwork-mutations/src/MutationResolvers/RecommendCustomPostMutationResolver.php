<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoPCMSSchema\UserMeta\Utils;

class RecommendCustomPostMutationResolver extends AbstractRecommendOrUnrecommendCustomPostMutationResolver
{
    public function validateErrors(MutationDataProviderInterface $mutationDataProvider): array
    {
        $errors = parent::validateErrors($mutationDataProvider);
        if (!$errors) {
            $user_id = App::getState('current-user-id');
            $target_id = $mutationDataProvider->getValue('target_id');

            // Check that the logged in user has not already recommended this post
            $value = Utils::getUserMeta($user_id, \GD_METAKEY_PROFILE_RECOMMENDSPOSTS);
            if (in_array($target_id, $value)) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
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
    protected function additionals($target_id, MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::additionals($target_id, $mutationDataProvider);
        App::doAction('gd_recommendpost', $target_id, $mutationDataProvider);
    }

    // protected function updateValue($value, \PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider) {
    //     // Add the user to follow to the list
    //     $target_id = $mutationDataProvider->getValue('target_id');
    //     $value[] = $target_id;
    // }
    /**
     * @throws AbstractException In case of error
     */
    protected function update(MutationDataProviderInterface $mutationDataProvider): string | int
    {
        $user_id = App::getState('current-user-id');
        $target_id = $mutationDataProvider->getValue('target_id');

        // Update value
        Utils::addUserMeta($user_id, \GD_METAKEY_PROFILE_RECOMMENDSPOSTS, $target_id);
        \PoPCMSSchema\CustomPostMeta\Utils::addCustomPostMeta($target_id, \GD_METAKEY_POST_RECOMMENDEDBY, $user_id);

        // Update the counter
        $count = \PoPCMSSchema\CustomPostMeta\Utils::getCustomPostMeta($target_id, \GD_METAKEY_POST_RECOMMENDCOUNT, true);
        $count = $count ? $count : 0;
        \PoPCMSSchema\CustomPostMeta\Utils::updateCustomPostMeta($target_id, \GD_METAKEY_POST_RECOMMENDCOUNT, ($count + 1), true);

        return parent::update($mutationDataProvider);
    }
}
