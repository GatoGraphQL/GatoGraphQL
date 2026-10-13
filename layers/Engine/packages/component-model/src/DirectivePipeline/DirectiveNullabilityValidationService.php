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
use function array_key_last;
use function array_pop;
use function array_slice;
use function spl_object_id;

class DirectiveNullabilityValidationService extends AbstractBasicService implements DirectiveNullabilityValidationServiceInterface
{
    /**
     * A stack, as a directive pipeline started by the engine
     * could (in theory) resolve another one.
     *
     * @var DirectiveNullabilityValidationContext[]
     */
    private array $contexts = [];

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
     *
     * The violations are reported once the whole pipeline has been executed,
     * as a later directive may still replace the `null` (eg: `@default`).
     */
    public function mustValidateDirectivePipeline(): bool
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

    public function startDirectivePipeline(
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $this->contexts[] = new DirectiveNullabilityValidationContext(
            $engineIterationFeedbackStore->objectResolutionFeedbackStore->getErrorCount(),
            $engineIterationFeedbackStore->schemaFeedbackStore->getErrorCount(),
        );
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<string|int,object> $idObjects
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    public function trackNullabilityViolations(
        DirectiveNullabilityValidationSnapshot $snapshot,
        FieldDirectiveResolverInterface $fieldDirectiveResolver,
        Directive $directive,
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        array $idObjects,
        array $resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $context = $this->contexts[array_key_last($this->contexts) ?? -1] ?? null;
        if ($context === null) {
            return;
        }
        [$erroredIDFields, $erroredFields] = $this->getErroredIDFields(
            $engineIterationFeedbackStore,
            $snapshot->objectErrorCount,
            $snapshot->schemaErrorCount,
        );
        $isMetaDirective = $fieldDirectiveResolver instanceof MetaFieldDirectiveResolverInterface;
        foreach ($idFieldSet as $id => $fieldSet) {
            $objectTypeResolver = $this->getObjectTypeResolver($relationalTypeResolver, $idObjects, $id);
            if ($objectTypeResolver === null) {
                continue;
            }
            foreach ($fieldSet->fields as $field) {
                $fieldID = spl_object_id($field);
                if (
                    isset($erroredFields[$fieldID])
                    || isset($erroredIDFields[$id][$fieldID])
                    || !isset($resolvedIDFieldValues[$id])
                    || !$resolvedIDFieldValues[$id]->offsetExists($field)
                ) {
                    unset($context->idFieldNullabilityViolations[$id][$fieldID]);
                    continue;
                }
                $nullabilityViolation = $this->getNullabilityViolation(
                    $objectTypeResolver,
                    $field,
                    $resolvedIDFieldValues[$id][$field],
                );
                if ($nullabilityViolation === null) {
                    unset($context->idFieldNullabilityViolations[$id][$fieldID]);
                    continue;
                }
                /**
                 * Attribute the violation to the directive that introduced it
                 */
                if (
                    isset($snapshot->idFieldNullabilityViolations[$id])
                    && array_key_exists($fieldID, $snapshot->idFieldNullabilityViolations[$id])
                    && $snapshot->idFieldNullabilityViolations[$id][$fieldID] === $nullabilityViolation
                ) {
                    continue;
                }
                $context->idFieldNullabilityViolations[$id][$fieldID] = new DirectiveNullabilityViolation(
                    $directive,
                    $isMetaDirective,
                    $field,
                    $nullabilityViolation,
                );
            }
        }
    }

    /**
     * @param array<string|int,object> $idObjects
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    public function endDirectivePipeline(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idObjects,
        array $resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $context = array_pop($this->contexts);
        if ($context === null) {
            return;
        }
        [$erroredIDFields, $erroredFields] = $this->getErroredIDFields(
            $engineIterationFeedbackStore,
            $context->objectErrorCount,
            $context->schemaErrorCount,
        );
        $objectResolutionFeedbackStore = $engineIterationFeedbackStore->objectResolutionFeedbackStore;
        foreach ($context->idFieldNullabilityViolations as $id => $fieldNullabilityViolations) {
            $objectTypeResolver = $this->getObjectTypeResolver($relationalTypeResolver, $idObjects, $id);
            if ($objectTypeResolver === null) {
                continue;
            }
            foreach ($fieldNullabilityViolations as $fieldID => $directiveNullabilityViolation) {
                $field = $directiveNullabilityViolation->field;
                if (
                    isset($erroredFields[$fieldID])
                    || isset($erroredIDFields[$id][$fieldID])
                    || !isset($resolvedIDFieldValues[$id])
                    || !$resolvedIDFieldValues[$id]->offsetExists($field)
                ) {
                    continue;
                }
                /**
                 * Report the violation only if it is still there
                 * after all the directives have been executed
                 */
                $nullabilityViolation = $this->getNullabilityViolation(
                    $objectTypeResolver,
                    $field,
                    $resolvedIDFieldValues[$id][$field],
                );
                if ($nullabilityViolation !== $directiveNullabilityViolation->nullabilityViolation) {
                    continue;
                }
                $directive = $directiveNullabilityViolation->directive;
                $objectResolutionFeedbackStore->addError(
                    new ObjectResolutionFeedback(
                        new FeedbackItemResolution(
                            FieldResolutionErrorFeedbackItemProvider::class,
                            $this->getNullabilityViolationErrorCode(
                                $nullabilityViolation,
                                $directiveNullabilityViolation->isMetaDirective,
                            ),
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
     * The errors are only ever appended to the stores, so
     * those added after some point are the ones after the
     * count of errors at that point.
     *
     * @return array{0:array<string|int,array<int,true>>,1:array<int,true>} The errored fields per object ID, and the fields errored for all objects
     */
    protected function getErroredIDFields(
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        int $objectErrorCount,
        int $schemaErrorCount,
    ): array {
        /** @var array<string|int,array<int,true>> */
        $erroredIDFields = [];
        foreach (array_slice($engineIterationFeedbackStore->objectResolutionFeedbackStore->getErrors(), $objectErrorCount) as $objectError) {
            foreach ($objectError->getIDFieldSet() as $id => $fieldSet) {
                foreach ($fieldSet->fields as $field) {
                    $erroredIDFields[$id][spl_object_id($field)] = true;
                }
            }
        }
        /** @var array<int,true> */
        $erroredFields = [];
        foreach (array_slice($engineIterationFeedbackStore->schemaFeedbackStore->getErrors(), $schemaErrorCount) as $schemaError) {
            foreach ($schemaError->getFields() as $field) {
                $erroredFields[spl_object_id($field)] = true;
            }
        }
        return [$erroredIDFields, $erroredFields];
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
