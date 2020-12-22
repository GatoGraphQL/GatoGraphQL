<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Facades\Engine\EngineFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateConditionDirectiveResolver;
use PoP\ComponentModel\Misc\GeneralUtils;

abstract class AbstractValidateCheckpointDirectiveResolver extends AbstractValidateConditionDirectiveResolver
{
    /**
     * Validate checkpoints
     *
     * @param TypeResolverInterface $typeResolver
     * @return boolean
     */
    protected function validateCondition(TypeResolverInterface $typeResolver): bool
    {
        $checkpointSet = $this->getValidationCheckpointSet($typeResolver);
        $engine = EngineFacade::getInstance();
        $validation = $engine->validateCheckpoints($checkpointSet);
        return !GeneralUtils::isError($validation);
    }

    /**
     * Provide the checkpoint to validate
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    abstract protected function getValidationCheckpointSet(TypeResolverInterface $typeResolver): array;
}
