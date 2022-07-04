<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\MutationInputProperties;

class CreateOrUpdateStanceMutationResolver extends AbstractCreateUpdateStanceMutationResolver
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataAccessorInterface $fieldDataProvider): mixed
    {
        if ($this->isUpdate($fieldDataProvider)) {
            return $this->update($fieldDataProvider);
        }
        return $this->create($fieldDataProvider);
    }

    public function validateErrors(FieldDataAccessorInterface $fieldDataProvider): array
    {
        if ($this->isUpdate($fieldDataProvider)) {
            return $this->validateUpdateErrors($fieldDataProvider);
        }
        return $this->validateCreateErrors($fieldDataProvider);
    }

    /**
     * If there's an "id" entry => It's Update
     * Otherwise => It's Create
     */
    protected function isUpdate(FieldDataAccessorInterface $fieldDataProvider): bool
    {
        return !empty($fieldDataProvider->get(MutationInputProperties::ID));
    }
}
