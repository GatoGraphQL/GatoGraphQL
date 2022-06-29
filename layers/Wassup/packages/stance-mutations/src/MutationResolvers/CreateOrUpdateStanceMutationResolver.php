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
    public function executeMutation(WithArgumentsInterface $withArgumentsAST): mixed
    {
        if ($this->isUpdate($withArgumentsAST)) {
            return $this->update($withArgumentsAST);
        }
        return $this->create($withArgumentsAST);
    }

    public function validateErrors(WithArgumentsInterface $withArgumentsAST): array
    {
        if ($this->isUpdate($withArgumentsAST)) {
            return $this->validateUpdateErrors($withArgumentsAST);
        }
        return $this->validateCreateErrors($withArgumentsAST);
    }

    /**
     * If there's an "id" entry => It's Update
     * Otherwise => It's Create
     */
    protected function isUpdate(WithArgumentsInterface $withArgumentsAST): bool
    {
        return !empty($withArgumentsAST->getArgumentValue(MutationInputProperties::ID));
    }
}
