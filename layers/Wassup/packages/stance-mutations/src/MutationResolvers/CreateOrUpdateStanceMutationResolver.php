<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolvers;

use PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties;

class CreateOrUpdateStanceMutationResolver extends AbstractCreateUpdateStanceMutationResolver
{
    public function execute(array $form_data): mixed
    {
        if ($this->isUpdate($form_data)) {
            return $this->update($form_data);
        }
        return $this->create($form_data);
    }

    public function validateErrors(array $form_data): ?array
    {
        if ($this->isUpdate($form_data)) {
            return $this->validateUpdateErrors($form_data);
        }
        return $this->validateCreateErrors($form_data);
    }

    /**
     * If there's an "id" entry => It's Update
     * Otherwise => It's Create
     */
    protected function isUpdate(array $form_data): bool
    {
        return !empty($form_data[MutationInputProperties::ID]);
    }
}
