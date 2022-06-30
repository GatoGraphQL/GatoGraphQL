<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

abstract class AbstractUpdateUserMetaValueMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(MutationDataProviderInterface $mutationDataProvider): array
    {
        $errors = [];
        $target_id = $mutationDataProvider->getValue('target_id');
        if (!$target_id) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('This URL is incorrect.', 'pop-coreprocessors');
        }
        return $errors;
    }

    protected function additionals($target_id, MutationDataProviderInterface $mutationDataProvider): void
    {
        App::doAction('gd_updateusermetavalue', $target_id, $mutationDataProvider);
    }

    /**
     * @throws AbstractException In case of error
     */
    protected function update(MutationDataProviderInterface $mutationDataProvider): string | int
    {
        $target_id = $mutationDataProvider->getValue('target_id');
        return $target_id;
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(MutationDataProviderInterface $mutationDataProvider): mixed
    {
        $target_id = $this->update($mutationDataProvider);
        $this->additionals($target_id, $mutationDataProvider);

        return $target_id;
    }
}
