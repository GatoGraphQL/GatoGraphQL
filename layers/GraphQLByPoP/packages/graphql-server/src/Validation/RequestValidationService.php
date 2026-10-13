<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Validation;

use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Feedback\QueryFeedback;
use PoP\ComponentModel\Feedback\QueryFeedbackInterface;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\ComponentModel\Schema\TypeConditionServiceInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocumentInterface;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationTypes;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\Root\Services\AbstractBasicService;
use SplObjectStorage;

use function array_filter;
use function array_values;
use function count;
use function json_encode;
use function spl_object_id;

class RequestValidationService extends AbstractBasicService implements RequestValidationServiceInterface
{
    private ?GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService = null;
    private ?TypeConditionServiceInterface $typeConditionService = null;

    final protected function getGraphQLSchemaDefinitionService(): GraphQLSchemaDefinitionServiceInterface
    {
        if ($this->graphQLSchemaDefinitionService === null) {
            /** @var GraphQLSchemaDefinitionServiceInterface */
            $graphQLSchemaDefinitionService = $this->instanceManager->getInstance(GraphQLSchemaDefinitionServiceInterface::class);
            $this->graphQLSchemaDefinitionService = $graphQLSchemaDefinitionService;
        }
        return $this->graphQLSchemaDefinitionService;
    }
    final protected function getTypeConditionService(): TypeConditionServiceInterface
    {
        if ($this->typeConditionService === null) {
            /** @var TypeConditionServiceInterface */
            $typeConditionService = $this->instanceManager->getInstance(TypeConditionServiceInterface::class);
            $this->typeConditionService = $typeConditionService;
        }
        return $this->typeConditionService;
    }

    public function maybeValidateExecutableDocumentAgainstSchema(ExecutableDocumentInterface $executableDocument): bool
    {
        /** @var ComponentModelModuleConfiguration */
        $moduleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        if (!$moduleConfiguration->returnNoDataOnRequestErrors()) {
            return true;
        }
        $errors = $this->validateExecutableDocumentAgainstSchema($executableDocument);
        $documentFeedbackStore = App::getFeedbackStore()->documentFeedbackStore;
        foreach ($errors as $error) {
            $documentFeedbackStore->addError($error);
        }
        return $errors === [];
    }

    /**
     * @return QueryFeedback[]
     */
    public function validateExecutableDocumentAgainstSchema(ExecutableDocumentInterface $executableDocument): array
    {
        $document = $executableDocument->getDocument();
        $operations = $executableDocument->getMultipleOperationsToExecute()
            ?? array_filter([$executableDocument->getRequestedOperation()]);

        /** @var array<string,QueryFeedback> */
        $errors = [];
        foreach ($operations as $operation) {
            $rootObjectTypeResolver = match ($operation->getOperationType()) {
                OperationTypes::QUERY => $this->getGraphQLSchemaDefinitionService()->getSchemaQueryRootObjectTypeResolver(),
                OperationTypes::MUTATION => $this->getGraphQLSchemaDefinitionService()->getSchemaMutationRootObjectTypeResolver(),
                default => null,
            };
            if ($rootObjectTypeResolver === null) {
                continue;
            }
            $this->validateFieldsOrFragmentBonds(
                $rootObjectTypeResolver,
                $operation->getFieldsOrFragmentBonds(),
                $document,
                $errors,
            );
        }
        return array_values($errors);
    }

    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @param array<string,QueryFeedback> $errors
     */
    protected function validateFieldsOrFragmentBonds(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $fieldsOrFragmentBonds,
        Document $document,
        array &$errors,
    ): void {
        /** @var ObjectTypeResolverInterface[] */
        $objectTypeResolvers = $relationalTypeResolver instanceof UnionTypeResolverInterface
            ? $relationalTypeResolver->getTargetObjectTypeResolvers()
            : [$relationalTypeResolver];
        foreach ($this->getFieldTypeConditionsTuples($fieldsOrFragmentBonds, [], $document) as [$field, $typeConditions]) {
            $applicableObjectTypeResolvers = array_values(array_filter(
                $objectTypeResolvers,
                fn (ObjectTypeResolverInterface $objectTypeResolver) => $this->satisfiesTypeConditions($objectTypeResolver, $typeConditions),
            ));
            if ($applicableObjectTypeResolvers === []) {
                continue;
            }
            $this->validateField(
                $relationalTypeResolver,
                $applicableObjectTypeResolvers,
                $field,
                $document,
                $errors,
            );
        }
    }

    /**
     * Flatten the fragments, keeping the type conditions
     * that each field must satisfy to be resolved
     *
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @param string[] $typeConditions
     * @return array<array{0:FieldInterface,1:string[]}>
     */
    protected function getFieldTypeConditionsTuples(
        array $fieldsOrFragmentBonds,
        array $typeConditions,
        Document $document,
    ): array {
        $fieldTypeConditionsTuples = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                $fragment = $document->getFragment($fieldOrFragmentBond->getName());
                if ($fragment === null) {
                    continue;
                }
                $fieldTypeConditionsTuples = [
                    ...$fieldTypeConditionsTuples,
                    ...$this->getFieldTypeConditionsTuples(
                        $fragment->getFieldsOrFragmentBonds(),
                        [...$typeConditions, $fragment->getModel()],
                        $document,
                    ),
                ];
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                $fieldTypeConditionsTuples = [
                    ...$fieldTypeConditionsTuples,
                    ...$this->getFieldTypeConditionsTuples(
                        $fieldOrFragmentBond->getFieldsOrFragmentBonds(),
                        $fieldOrFragmentBond->getTypeName() !== null
                            ? [...$typeConditions, $fieldOrFragmentBond->getTypeName()]
                            : $typeConditions,
                        $document,
                    ),
                ];
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentBond;
            $fieldTypeConditionsTuples[] = [$field, $typeConditions];
        }
        return $fieldTypeConditionsTuples;
    }

    /**
     * @param string[] $typeConditions
     */
    protected function satisfiesTypeConditions(
        ObjectTypeResolverInterface $objectTypeResolver,
        array $typeConditions,
    ): bool {
        foreach ($typeConditions as $typeCondition) {
            if (!$this->getTypeConditionService()->isTypeOrImplements($objectTypeResolver, $typeCondition)) {
                return false;
            }
        }
        return true;
    }

    /**
     * A field queried on a union type is an error only
     * when none of its member types has that field (if
     * some of them do, the objects from the other types
     * produce an error when executing the query).
     *
     * @param ObjectTypeResolverInterface[] $objectTypeResolvers
     * @param array<string,QueryFeedback> $errors
     */
    protected function validateField(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $objectTypeResolvers,
        FieldInterface $field,
        Document $document,
        array &$errors,
    ): void {
        /** @var SplObjectStorage<ObjectTypeResolverInterface,ObjectTypeFieldResolverInterface> */
        $objectTypeResolverFieldResolvers = new SplObjectStorage();
        foreach ($objectTypeResolvers as $objectTypeResolver) {
            $objectTypeFieldResolver = $objectTypeResolver->getExecutableObjectTypeFieldResolverForField($field);
            if ($objectTypeFieldResolver === null) {
                continue;
            }
            $objectTypeResolverFieldResolvers[$objectTypeResolver] = $objectTypeFieldResolver;
        }

        if ($objectTypeResolverFieldResolvers->count() === 0) {
            $typeResolver = count($objectTypeResolvers) === 1 ? $objectTypeResolvers[0] : $relationalTypeResolver;
            $this->addError(
                $this->createQueryFeedback(
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_3_1,
                        [
                            $field->getName(),
                            $typeResolver->getMaybeNamespacedTypeName(),
                        ]
                    ),
                    $field,
                    $typeResolver,
                    $field,
                ),
                $errors,
            );
            return;
        }

        /** @var SplObjectStorage<RelationalTypeResolverInterface,bool> */
        $nestedRelationalTypeResolvers = new SplObjectStorage();
        foreach ($objectTypeResolverFieldResolvers as $objectTypeResolver) {
            /** @var ObjectTypeFieldResolverInterface */
            $objectTypeFieldResolver = $objectTypeResolverFieldResolvers[$objectTypeResolver];
            $fieldTypeResolver = $objectTypeFieldResolver->getFieldTypeResolver($objectTypeResolver, $field->getName());
            if (
                $field instanceof RelationalField
                && !($fieldTypeResolver instanceof RelationalTypeResolverInterface)
            ) {
                $this->addError(
                    $this->createQueryFeedback(
                        new FeedbackItemResolution(
                            GraphQLSpecErrorFeedbackItemProvider::class,
                            GraphQLSpecErrorFeedbackItemProvider::E_5_3_3,
                            [
                                $field->getOutputKey(),
                                $objectTypeResolver->getMaybeNamespacedTypeName(),
                            ]
                        ),
                        $field,
                        $objectTypeResolver,
                        $field,
                    ),
                    $errors,
                );
                continue;
            }

            $objectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
            $objectTypeResolver->validateFieldArgsAgainstSchema(
                $field,
                $objectTypeFieldResolutionFeedbackStore,
            );
            $this->addErrors($objectTypeFieldResolutionFeedbackStore->getErrors(), $objectTypeResolver, $field, $errors);

            $this->validateFieldDirectives($objectTypeResolver, $field, $errors);

            if ($field instanceof RelationalField && $fieldTypeResolver instanceof RelationalTypeResolverInterface) {
                $nestedRelationalTypeResolvers[$fieldTypeResolver] = true;
            }
        }

        if (!($field instanceof RelationalField)) {
            return;
        }
        foreach ($nestedRelationalTypeResolvers as $nestedRelationalTypeResolver) {
            $this->validateFieldsOrFragmentBonds(
                $nestedRelationalTypeResolver,
                $field->getFieldsOrFragmentBonds(),
                $document,
                $errors,
            );
        }
    }

    /**
     * @param array<string,QueryFeedback> $errors
     */
    protected function validateFieldDirectives(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        array &$errors,
    ): void {
        $directives = $field->getDirectives();
        if ($directives === []) {
            return;
        }
        /** @var SplObjectStorage<Directive,FieldInterface[]> */
        $directiveFields = new SplObjectStorage();
        foreach ($directives as $directive) {
            $directiveFields[$directive] = [$field];
        }
        $engineIterationFeedbackStore = new EngineIterationFeedbackStore();
        $objectTypeResolver->resolveDirectivesIntoPipelineData(
            $directives,
            $directiveFields,
            $engineIterationFeedbackStore,
        );
        $this->addErrors($engineIterationFeedbackStore->schemaFeedbackStore->getErrors(), $objectTypeResolver, $field, $errors);
        $this->addErrors($engineIterationFeedbackStore->objectResolutionFeedbackStore->getErrors(), $objectTypeResolver, $field, $errors);
    }

    /**
     * @param QueryFeedbackInterface[] $queryFeedbackErrors
     * @param array<string,QueryFeedback> $errors
     */
    protected function addErrors(
        array $queryFeedbackErrors,
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldInterface $field,
        array &$errors,
    ): void {
        foreach ($queryFeedbackErrors as $queryFeedbackError) {
            $this->addError(
                $this->createQueryFeedback(
                    $queryFeedbackError->getFeedbackItemResolution(),
                    $queryFeedbackError->getAstNode(),
                    $relationalTypeResolver,
                    $field,
                    $queryFeedbackError->getExtensions(),
                ),
                $errors,
            );
        }
    }

    /**
     * Print the same entries under "extensions" as
     * when the error is produced when executing the query
     *
     * @param array<string,mixed> $extensions
     */
    protected function createQueryFeedback(
        FeedbackItemResolution $feedbackItemResolution,
        AstInterface $astNode,
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldInterface $field,
        array $extensions = [],
    ): QueryFeedback {
        return new QueryFeedback(
            $feedbackItemResolution,
            $astNode,
            [
                'type' => $relationalTypeResolver->getTypeOutputKey(),
                Tokens::FIELD => $field->asASTNodeString(),
                ...$extensions,
            ],
        );
    }

    /**
     * The same error can be produced by several member types
     * of a union: print it only once
     *
     * @param array<string,QueryFeedback> $errors
     */
    protected function addError(QueryFeedback $error, array &$errors): void
    {
        $feedbackItemResolution = $error->getFeedbackItemResolution();
        $key = (string) json_encode([
            $feedbackItemResolution->getFeedbackProviderServiceClass(),
            $feedbackItemResolution->getCode(),
            $feedbackItemResolution->getMessageParams(),
            spl_object_id($error->getAstNode()),
        ]);
        $errors[$key] ??= $error;
    }
}
