<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Facades\Engine\EngineFacade;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateConditionDirectiveResolver;
use PoP\ComponentModel\Misc\GeneralUtils;

abstract class AbstractValidateCheckpointDirectiveResolver extends AbstractValidateConditionDirectiveResolver
{
    /**
     * Validate checkpoints
     */
    protected function validateCondition(RelationalTypeResolverInterface $typeResolver): bool
    {
        $checkpointSet = $this->getValidationCheckpointSet($typeResolver);
        $engine = EngineFacade::getInstance();
        $validation = $engine->validateCheckpoints($checkpointSet);
        return !GeneralUtils::isError($validation);
    }

    /**
     * Provide the checkpoint to validate
     */
    abstract protected function getValidationCheckpointSet(RelationalTypeResolverInterface $typeResolver): array;
}
