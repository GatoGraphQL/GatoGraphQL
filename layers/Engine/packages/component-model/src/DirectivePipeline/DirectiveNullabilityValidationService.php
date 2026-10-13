<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use PoP\ComponentModel\App;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\MetaFieldDirectiveResolverInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Enums\OutputValueNullabilityViolations;
use PoP\ComponentModel\FeedbackItemProviders\FieldResolutionErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedback;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\Schema\OutputValueNullabilityServiceInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Services\AbstractBasicService;
use SplObjectStorage;

use function array_key_exists;
use function array_slice;
use function spl_object_id;

class DirectiveNullabilityValidationService extends AbstractBasicService implements DirectiveNullabilityValidationServiceInterface
{
    private ?OutputValueNullabilityServiceInterface $outputValueNullabilityService = null;

    final protected function getOutputValueNullabilityService(): OutputValueNullabilityServiceInterface
    {
        if ($this->outputValueNullabilityService === null) {
            /** @var OutputValueNullabilityServiceInterface */
            $outputValueNullabilityService = $this->instanceManager->getInstance(OutputValueNullabilityServiceInterface::class);
            $this->outputValueNullabilityService = $outputValueNullabilityService;
        }
        return $this->outputValueNullabilityService;
    }

    /**
     * Validate only the stages of the pipeline started by the engine.
     *
     * The directives nested within a meta directive (eg: `@underEachArrayItem`)
     * are executed in a pipeline of their own, on the items of the field
     * (not on the field itself), so they must not be validated against the
     * field's type. Instead, the field is validated when the meta directive's
     * stage finishes, after it has composed the field's value back.
     */
    public function mustValidateDirectivePipelineStage(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableSpecCompliantErrorHandling()) {
            return false;
        }
        return App::getState('directive-pipeline-depth') === 1;
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<string|int,object> $idObjects
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    public function takeSnapshot(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        array $idObjects,
        array $resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): DirectiveNullabilityValidationSnapshot {
        /** @var array<string|int,array<int,OutputValueNullabilityViolations|null>> */
        $idFieldNullabilityViolations = [];
        foreach ($idFieldSet as $id => $fieldSet) {
            $objectTypeResolver = $this->getObjectTypeResolver($relationalTypeResolver, $idObjects, $id);
            if ($objectTypeResolver === null) {
                continue;
            }
            foreach ($fieldSet->fields as $field) {
                if (!isset($resolvedIDFieldValues[$id]) || !$resolvedIDFieldValues[$id]->offsetExists($field)) {
                    continue;
                }
                $idFieldNullabilityViolations[$id][spl_object_id($field)] = $this->getNullabilityViolation(
                    $objectTypeResolver,
                    $field,
                    $resolvedIDFieldValues[$id][$field],
                );
            }
        }
        return new DirectiveNullabilityValidationSnapshot(
            $engineIterationFeedbackStore->objectResolutionFeedbackStore->getErrorCount(),
            $engineIterationFeedbackStore->schemaFeedbackStore->getErrorCount(),
            $idFieldNullabilityViolations,
        );
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<string|int,object> $idObjects
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    public function validate(
        DirectiveNullabilityValidationSnapshot $snapshot,
        FieldDirectiveResolverInterface $fieldDirectiveResolver,
        Directive $directive,
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        array $idObjects,
        array $resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $objectResolutionFeedbackStore = $engineIterationFeedbackStore->objectResolutionFeedbackStore;
        $schemaFeedbackStore = $engineIterationFeedbackStore->schemaFeedbackStore;

        /**
         * The errors are only ever appended to the stores, so
         * those added by this stage are the ones after the snapshot.
         *
         * @var array<string|int,array<int,true>>
         */
        $erroredIDFields = [];
        foreach (array_slice($objectResolutionFeedbackStore->getErrors(), $snapshot->objectErrorCount) as $objectError) {
            foreach ($objectError->getIDFieldSet() as $id => $fieldSet) {
                foreach ($fieldSet->fields as $field) {
                    $erroredIDFields[$id][spl_object_id($field)] = true;
                }
            }
        }
        /** @var array<int,true> */
        $erroredFields = [];
        foreach (array_slice($schemaFeedbackStore->getErrors(), $snapshot->schemaErrorCount) as $schemaError) {
            foreach ($schemaError->getFields() as $field) {
                $erroredFields[spl_object_id($field)] = true;
            }
        }

        $isMetaDirective = $fieldDirectiveResolver instanceof MetaFieldDirectiveResolverInterface;
        foreach ($idFieldSet as $id => $fieldSet) {
            $objectTypeResolver = $this->getObjectTypeResolver($relationalTypeResolver, $idObjects, $id);
            if ($objectTypeResolver === null) {
                continue;
            }
            foreach ($fieldSet->fields as $field) {
                if (!isset($resolvedIDFieldValues[$id]) || !$resolvedIDFieldValues[$id]->offsetExists($field)) {
                    continue;
                }
                $fieldID = spl_object_id($field);
                if (isset($erroredFields[$fieldID]) || isset($erroredIDFields[$id][$fieldID])) {
                    continue;
                }
                $nullabilityViolation = $this->getNullabilityViolation(
                    $objectTypeResolver,
                    $field,
                    $resolvedIDFieldValues[$id][$field],
                );
                if ($nullabilityViolation === null) {
                    continue;
                }
                /**
                 * Report only the violations introduced by this directive
                 */
                if (
                    isset($snapshot->idFieldNullabilityViolations[$id])
                    && array_key_exists($fieldID, $snapshot->idFieldNullabilityViolations[$id])
                    && $snapshot->idFieldNullabilityViolations[$id][$fieldID] === $nullabilityViolation
                ) {
                    continue;
                }
                $objectResolutionFeedbackStore->addError(
                    new ObjectResolutionFeedback(
                        new FeedbackItemResolution(
                            FieldResolutionErrorFeedbackItemProvider::class,
                            $this->getNullabilityViolationErrorCode($nullabilityViolation, $isMetaDirective),
                            [
                                $directive->asQueryString(),
                                $field->getName(),
                            ]
                        ),
                        $directive,
                        $relationalTypeResolver,
                        $directive,
                        [
                            $id => new EngineIterationFieldSet([$field]),
                        ]
                    )
                );
            }
        }
    }

    /**
     * @param array<string|int,object> $idObjects
     */
    protected function getObjectTypeResolver(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idObjects,
        string|int $id,
    ): ?ObjectTypeResolverInterface {
        if ($relationalTypeResolver instanceof UnionTypeResolverInterface) {
            $object = $idObjects[$id] ?? null;
            if ($object === null) {
                return null;
            }
            return $relationalTypeResolver->getTargetObjectTypeResolver($object);
        }
        /** @var ObjectTypeResolverInterface $relationalTypeResolver */
        return $relationalTypeResolver;
    }

    protected function getNullabilityViolation(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        mixed $value,
    ): ?OutputValueNullabilityViolations {
        $fieldTypeModifiers = $objectTypeResolver->getFieldTypeModifiers($field);
        if ($fieldTypeModifiers === null) {
            return null;
        }
        return $this->getOutputValueNullabilityService()->getNullabilityViolation(
            $fieldTypeModifiers,
            $value,
        );
    }

    protected function getNullabilityViolationErrorCode(
        OutputValueNullabilityViolations $nullabilityViolation,
        bool $isMetaDirective,
    ): string {
        return match ($nullabilityViolation) {
            OutputValueNullabilityViolations::NULL_VALUE => $isMetaDirective
                ? FieldResolutionErrorFeedbackItemProvider::E13
                : FieldResolutionErrorFeedbackItemProvider::E10,
            OutputValueNullabilityViolations::NULL_ARRAY_ITEM => $isMetaDirective
                ? FieldResolutionErrorFeedbackItemProvider::E14
                : FieldResolutionErrorFeedbackItemProvider::E11,
            OutputValueNullabilityViolations::NULL_ARRAY_OF_ARRAYS_ITEM => $isMetaDirective
                ? FieldResolutionErrorFeedbackItemProvider::E15
                : FieldResolutionErrorFeedbackItemProvider::E12,
        };
    }
}
