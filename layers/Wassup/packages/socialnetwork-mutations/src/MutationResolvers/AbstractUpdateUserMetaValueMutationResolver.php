<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

abstract class AbstractUpdateUserMetaValueMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(FieldDataAccessorInterface $fieldDataProvider): array
    {
        $errors = [];
        $target_id = $fieldDataProvider->get('target_id');
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

    protected function additionals($target_id, FieldDataAccessorInterface $fieldDataProvider): void
    {
        App::doAction('gd_updateusermetavalue', $target_id, $fieldDataProvider);
    }

    /**
     * @throws AbstractException In case of error
     */
    protected function update(FieldDataAccessorInterface $fieldDataProvider): string | int
    {
        $target_id = $fieldDataProvider->get('target_id');
        return $target_id;
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataAccessorInterface $fieldDataProvider): mixed
    {
        $target_id = $this->update($fieldDataProvider);
        $this->additionals($target_id, $fieldDataProvider);

        return $target_id;
    }
}
