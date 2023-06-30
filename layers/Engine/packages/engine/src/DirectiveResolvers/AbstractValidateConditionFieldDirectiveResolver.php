<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\Directives\FieldDirectiveBehaviors;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\SuperRootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

abstract class AbstractValidateConditionFieldDirectiveResolver extends AbstractValidateFieldDirectiveResolver
{
    private ?SuperRootObjectTypeResolver $superRootObjectTypeResolver = null;

    final public function setSuperRootObjectTypeResolver(SuperRootObjectTypeResolver $superRootObjectTypeResolver): void
    {
        $this->superRootObjectTypeResolver = $superRootObjectTypeResolver;
    }
    final protected function getSuperRootObjectTypeResolver(): SuperRootObjectTypeResolver
    {
        if ($this->superRootObjectTypeResolver === null) {
            /** @var SuperRootObjectTypeResolver */
            $superRootObjectTypeResolver = $this->instanceManager->getInstance(SuperRootObjectTypeResolver::class);
            $this->superRootObjectTypeResolver = $superRootObjectTypeResolver;
        }
        return $this->superRootObjectTypeResolver;
    }

    /**
     * If validating a directive, place it after resolveAndMerge
     * Otherwise, before
     */
    public function getPipelinePosition(): string
    {
        if ($this->isValidatingDirective()) {
            return PipelinePositions::AFTER_RESOLVE;
        }
        return PipelinePositions::BEFORE_RESOLVE;
    }

    /**
     * Also add all the @validate... directives to the Operation
     */
    public function getFieldDirectiveBehavior(): string
    {
        if (!$this->isValidatingDirective()) {
            return FieldDirectiveBehaviors::FIELD_AND_OPERATION;
        }
        return parent::getFieldDirectiveBehavior();
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return array<string|int,EngineIterationFieldSet> Failed $idFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     */
    protected function validateIDFieldSet(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        array &$succeedingPipelineIDFieldSet,
        array &$resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        if (
            $this->isValidationSuccessful(
                $relationalTypeResolver,
                $idFieldSet,
                $succeedingPipelineIDFieldSet,
                $resolvedIDFieldValues,
                $engineIterationFeedbackStore,
            )
        ) {
            return [];
        }
        // All fields failed
        $this->addUnsuccessfulValidationErrors(
            $relationalTypeResolver,
            $idFieldSet,
            $fieldDataAccessProvider,
            $engineIterationFeedbackStore,
        );
        return $idFieldSet;
    }

    /**
     * Condition to validate. Return `true` for success, `false` for failure
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     */
    abstract protected function isValidationSuccessful(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        array &$succeedingPipelineIDFieldSet,
        array &$resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): bool;

    /**
     * Add the errors to the FeedbackStore
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    abstract protected function addUnsuccessfulValidationErrors(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void;

    /**
     * Show a different error message depending on if we are validating the whole field, or a directive
     * By default, validate the whole field
     */
    protected function isValidatingDirective(): bool
    {
        return false;
    }

    /**
     * @param array<string|int> $ids
     * @return array<string|int,EngineIterationFieldSet>
     */
    protected function getFieldIDSetForField(FieldInterface $field, array $ids): array
    {
        $fieldIDFieldSet = [];
        $fieldEngineIterationFieldSet = new EngineIterationFieldSet([$field]);
        foreach ($ids as $id) {
            $fieldIDFieldSet[$id] = $fieldEngineIterationFieldSet;
        }
        return $fieldIDFieldSet;
    }
}
