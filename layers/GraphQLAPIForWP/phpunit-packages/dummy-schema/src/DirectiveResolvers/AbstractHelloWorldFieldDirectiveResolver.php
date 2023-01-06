<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\DummySchema\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\Engine\DirectiveResolvers\AbstractGlobalFieldDirectiveResolver;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

abstract class AbstractHelloWorldFieldDirectiveResolver extends AbstractGlobalFieldDirectiveResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getDirectiveName(): string
    {
        return 'helloWorld';
    }

    final public function getDirectiveVersionInputTypeResolver(RelationalTypeResolverInterface $relationalTypeResolver): ?InputTypeResolverInterface
    {
        return $this->getStringScalarTypeResolver();
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<FieldDataAccessProviderInterface> $succeedingPipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<FieldDirectiveResolverInterface> $succeedingPipelineFieldDirectiveResolvers
     * @param array<string|int,object> $idObjects
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        array $succeedingPipelineFieldDirectiveResolvers,
        array $idObjects,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$succeedingPipelineIDFieldSet,
        array &$succeedingPipelineFieldDataAccessProviders,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        foreach ($idFieldSet as $id => $fieldSet) {
            foreach ($fieldSet->fields as $field) {
                $resolvedIDFieldValues[$id][$field] = sprintf(
                    $this->__('%s: %s (version: "%s")', 'dummy-schema'),
                    $resolvedIDFieldValues[$id][$field],
                    $this->getDirectiveVersion($relationalTypeResolver) ?? 'no version / default',
                );
            }
        }
    }
    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('Hello + original content of the field + directive version', 'dummy-schema');
    }
}
