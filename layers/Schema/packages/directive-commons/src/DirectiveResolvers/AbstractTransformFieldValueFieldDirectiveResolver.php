<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\DirectiveResolvers;

use PoP\ComponentModel\App;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\DirectiveResolvers\AbstractFieldDirectiveResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

abstract class AbstractTransformFieldValueFieldDirectiveResolver extends AbstractFieldDirectiveResolver
{
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
        /** 
         * If the directive args contain any reference to a promise,
         * validate it
         */
        /** @var ComponentModelModuleConfiguration */
        $moduleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        $setFieldAsNullIfDirectiveFailed = $moduleConfiguration->setFieldAsNullIfDirectiveFailed();

        $resolveDirectiveArgsOnObject = $this->directive->hasArgumentReferencingResolvedOnObjectPromise();

        if (!$resolveDirectiveArgsOnObject) {
            $directiveArgs = $this->getResolvedDirectiveArgs(
                $relationalTypeResolver,
                $idFieldSet,
                $engineIterationFeedbackStore,
            );
            if ($directiveArgs === null) {
                if ($setFieldAsNullIfDirectiveFailed) {
                    $this->removeIDFieldSet(
                        $succeedingPipelineIDFieldSet,
                        $idFieldSet,
                    );
                    $this->setFieldResponseValueAsNull(
                        $resolvedIDFieldValues,
                        $idFieldSet,
                    );
                }
                return;
            }
        }
        
        foreach ($idFieldSet as $id => $fieldSet) {
            foreach ($fieldSet->fields as $field) {
                if ($resolveDirectiveArgsOnObject) {
                    $directiveArgs = $this->getResolvedDirectiveArgsForObjectAndField(
                        $relationalTypeResolver,
                        $field,
                        $id,
                        $engineIterationFeedbackStore,
                    );
                    if ($directiveArgs === null) {
                        if ($setFieldAsNullIfDirectiveFailed) {
                            $iterationFieldSet = [$id => new EngineIterationFieldSet([$field])];
                            $this->removeIDFieldSet(
                                $succeedingPipelineIDFieldSet,
                                $iterationFieldSet,
                            );
                            $this->setFieldResponseValueAsNull(
                                $resolvedIDFieldValues,
                                $iterationFieldSet,
                            );
                        }
                        continue;
                    }
                }

                $resolvedIDFieldValues[$id][$field] = $this->transformValue(
                    $resolvedIDFieldValues[$id][$field],
                    $id,
                    $field,
                    $relationalTypeResolver,
                    $succeedingPipelineIDFieldSet,
                    $resolvedIDFieldValues,
                    $engineIterationFeedbackStore,
                );
            }
        }

        /**
         * Reset the AppState
         */
        if ($resolveDirectiveArgsOnObject) {
            $this->resetObjectResolvedDynamicVariablesInAppState();
            $this->directiveDataAccessor->resetDirectiveArgs();
        }
    }

    /**
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    abstract protected function transformValue(
        mixed $value,
        string|int $id,
        FieldInterface $field,
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$succeedingPipelineIDFieldSet,
        array &$resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): mixed;
}
