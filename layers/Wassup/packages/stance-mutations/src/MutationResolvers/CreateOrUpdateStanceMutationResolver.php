<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolvers;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\MutationInputProperties;

class CreateOrUpdateStanceMutationResolver extends AbstractCreateUpdateStanceMutationResolver
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): mixed
    {
        if ($this->isUpdate($mutationDataProvider)) {
            return $this->update($mutationDataProvider);
        }
        return $this->create($mutationDataProvider);
    }

    public function validateErrors(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): array
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
    protected function isUpdate(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): bool
    {
        return !empty($mutationDataProvider->getArgumentValue(MutationInputProperties::ID));
    }
}
