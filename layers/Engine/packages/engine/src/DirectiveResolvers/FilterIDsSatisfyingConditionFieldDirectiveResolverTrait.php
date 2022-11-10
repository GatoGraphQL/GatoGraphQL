<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\App;
use PoP\ComponentModel\DirectiveResolvers\RemoveIDFieldSetFieldDirectiveResolverTrait;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

trait FilterIDsSatisfyingConditionFieldDirectiveResolverTrait
{
    use RemoveIDFieldSetFieldDirectiveResolverTrait;

    /**
     * Check the condition field. If it is satisfied, then skip those fields.
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return array<string|int>
     * @param array<string|int,object> $idObjects
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<string,mixed> $messages
     */
    protected function getIDsSatisfyingCondition(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idObjects,
        array $idFieldSet,
        array &$succeedingPipelineIDFieldSet,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        $directiveArgs = $this->getResolvedDirectiveArgs(
            $relationalTypeResolver,
            $idFieldSet,
            $engineIterationFeedbackStore,
        );
        if ($directiveArgs === null) {
            /** @var ComponentModelModuleConfiguration */
            $moduleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
            $setFieldAsNullIfDirectiveFailed = $moduleConfiguration->setFieldAsNullIfDirectiveFailed();
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
            return [];
        }
        $idsSatisfyingCondition = [];
        foreach (array_keys($idFieldSet) as $id) {
            if ($directiveArgs['if'] ?? null) {
                $idsSatisfyingCondition[] = $id;
            }
        }
        return $idsSatisfyingCondition;
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<string|int> $idsToRemove
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     */
    protected function removeFieldSetForIDs(
        array $idFieldSet,
        array $idsToRemove,
        array &$succeedingPipelineIDFieldSet,
    ): void {
        // Calculate the $idFieldSet that must be removed from all the upcoming stages of the pipeline
        $idFieldSetToRemove = array_filter(
            $idFieldSet,
            fn (int|string $id) => in_array($id, $idsToRemove),
            ARRAY_FILTER_USE_KEY
        );
        $this->removeIDFieldSet(
            $succeedingPipelineIDFieldSet,
            $idFieldSetToRemove,
        );
    }
}
