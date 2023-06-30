<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

abstract class AbstractCheckpointValidateConditionFieldDirectiveResolver extends AbstractValidateConditionFieldDirectiveResolver
{
    private ?EngineInterface $engine = null;

    final public function setEngine(EngineInterface $engine): void
    {
        $this->engine = $engine;
    }
    final protected function getEngine(): EngineInterface
    {
        if ($this->engine === null) {
            /** @var EngineInterface */
            $engine = $this->instanceManager->getInstance(EngineInterface::class);
            $this->engine = $engine;
        }
        return $this->engine;
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     */
    protected function isValidationSuccessful(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        array &$succeedingPipelineIDFieldSet,
        array &$resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): bool {
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
