<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

abstract class AbstractValidateCheckpointDirectiveResolver extends AbstractValidateConditionDirectiveResolver
{
    private ?EngineInterface $engine = null;

    final public function setEngine(EngineInterface $engine): void
    {
        $this->engine = $engine;
    }
    final protected function getEngine(): EngineInterface
    {
        /** @var EngineInterface */
        return $this->engine ??= $this->instanceManager->getInstance(EngineInterface::class);
    }

    /**
     * Validate checkpoints
     */
    protected function isValidationSuccessful(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        $checkpoints = $this->getValidationCheckpoints($relationalTypeResolver);
        $feedbackItemResolution = $this->getEngine()->validateCheckpoints($checkpoints);
        return $feedbackItemResolution === null;
    }

    /**
     * Provide the checkpoint to validate
     *
     * @return CheckpointInterface[]
     */
    abstract protected function getValidationCheckpoints(RelationalTypeResolverInterface $relationalTypeResolver): array;
}
