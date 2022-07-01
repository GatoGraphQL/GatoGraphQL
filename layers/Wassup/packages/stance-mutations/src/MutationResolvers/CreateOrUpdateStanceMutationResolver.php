<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\MutationInputProperties;

class CreateOrUpdateStanceMutationResolver extends AbstractCreateUpdateStanceMutationResolver
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(MutationDataProviderInterface $mutationDataProvider): mixed
    {
        if ($this->isUpdate($mutationDataProvider)) {
            return $this->update($mutationDataProvider);
        }
        return $this->create($mutationDataProvider);
    }

    public function validateErrors(MutationDataProviderInterface $mutationDataProvider): array
    {
        if ($this->isUpdate($mutationDataProvider)) {
            return $this->validateUpdateErrors($mutationDataProvider);
        }
        return $this->validateCreateErrors($mutationDataProvider);
    }

    /**
     * If there's an "id" entry => It's Update
     * Otherwise => It's Create
     */
    protected function isUpdate(MutationDataProviderInterface $mutationDataProvider): bool
    {
        return !empty($mutationDataProvider->get(MutationInputProperties::ID));
    }
}
