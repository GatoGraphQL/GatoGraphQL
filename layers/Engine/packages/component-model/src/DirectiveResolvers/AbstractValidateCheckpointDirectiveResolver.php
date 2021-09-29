<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractValidateCheckpointDirectiveResolver extends AbstractValidateConditionDirectiveResolver
{
    protected EngineInterface $engine;

    #[Required]
    public function autowireAbstractValidateCheckpointDirectiveResolver(
        EngineInterface $engine,
    ): void {
        $this->engine = $engine;
    }

    /**
     * Validate checkpoints
     */
    protected function validateCondition(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        $checkpointSet = $this->getValidationCheckpointSet($relationalTypeResolver);
        $validation = $this->engine->validateCheckpoints($checkpointSet);
        return !GeneralUtils::isError($validation);
    }

    /**
     * Provide the checkpoint to validate
     */
    abstract protected function getValidationCheckpointSet(RelationalTypeResolverInterface $relationalTypeResolver): array;
}
