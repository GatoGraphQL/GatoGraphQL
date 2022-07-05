<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\MutationInputProperties;

class CreateOrUpdateStanceMutationResolver extends AbstractCreateUpdateStanceMutationResolver
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataAccessorInterface $fieldDataAccessor): mixed
    {
        if ($this->isUpdate($fieldDataAccessor)) {
            return $this->update($fieldDataAccessor);
        }
        return $this->create($fieldDataAccessor);
    }

    public function validateErrors(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        if ($this->isUpdate($fieldDataAccessor)) {
            return $this->validateUpdateErrors($fieldDataAccessor);
        }
        return $this->validateCreateErrors($fieldDataAccessor);
    }

    /**
     * If there's an "id" entry => It's Update
     * Otherwise => It's Create
     */
    protected function isUpdate(FieldDataAccessorInterface $fieldDataAccessor): bool
    {
        return !empty($fieldDataAccessor->getValue(MutationInputProperties::ID));
    }
}
