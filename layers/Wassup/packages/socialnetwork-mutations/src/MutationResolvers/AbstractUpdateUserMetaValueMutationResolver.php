<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

abstract class AbstractUpdateUserMetaValueMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(array $form_data): array
    {
        $errors = [];
        $target_id = $form_data['target_id'];
        if (!$target_id) {
            $errors[] = $this->__('This URL is incorrect.', 'pop-coreprocessors');
        }
        return $errors;
    }

    protected function additionals($target_id, $form_data): void
    {
        App::doAction('gd_updateusermetavalue', $target_id, $form_data);
    }

    /**
     * @param array<string,mixed> $form_data
     * @throws AbstractException In case of error
     */
    protected function update(array $form_data): string | int
    {
        $target_id = $form_data['target_id'];
        return $target_id;
    }

    /**
     * @param array<string,mixed> $form_data
     * @throws AbstractException In case of error
     */
    public function executeMutation(array $form_data): mixed
    {
        $target_id = $this->update($form_data);
        $this->additionals($target_id, $form_data);

        return $target_id;
    }
}
