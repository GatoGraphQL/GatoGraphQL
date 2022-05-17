<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLQuery\Schema;

use Exception;
use GraphQLByPoP\GraphQLQuery\Module as GraphQLQueryModule;
use GraphQLByPoP\GraphQLQuery\ModuleConfiguration as GraphQLQueryModuleConfiguration;
use GraphQLByPoP\GraphQLQuery\Schema\QuerySymbols;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\DocumentFeedback;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\Engine\DirectiveResolvers\IncludeDirectiveResolver;
use PoP\FieldQuery\FeedbackMessageStoreInterface;
use PoP\FieldQuery\QueryHelpers;
use PoP\FieldQuery\QuerySyntax;
use PoP\GraphQLParser\Module as GraphQLParserModule;
use PoP\GraphQLParser\ModuleConfiguration as GraphQLParserModuleConfiguration;
use PoP\GraphQLParser\Exception\Parser\AbstractParserException;
use PoP\GraphQLParser\ExtendedSpec\Constants\QuerySymbols as GraphQLParserQuerySymbols;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\ResolvedFieldVariableReference;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\MetaDirective;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\FeedbackItemProviders\SuggestionFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocumentInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\MutationOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\Environment as RootEnvironment;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;
use PoPAPI\API\Schema\QuerySyntax as APIQuerySyntax;
use stdClass;

class GraphQLQueryConvertor implements GraphQLQueryConvertorInterface
{
    use BasicServiceTrait;

    private ?FeedbackMessageStoreInterface $feedbackMessageStore = null;
    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;
    private ?IncludeDirectiveResolver $includeDirectiveResolver = null;
    private ?ParserInterface $parser = null;

    final public function setFeedbackMessageStore(FeedbackMessageStoreInterface $feedbackMessageStore): void
    {
        $this->feedbackMessageStore = $feedbackMessageStore;
    }
    final protected function getFeedbackMessageStore(): FeedbackMessageStoreInterface
    {
        return $this->feedbackMessageStore ??= $this->instanceManager->getInstance(FeedbackMessageStoreInterface::class);
    }
    final public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    final protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    {
        return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    }
    final public function setIncludeDirectiveResolver(IncludeDirectiveResolver $includeDirectiveResolver): void
    {
        $this->includeDirectiveResolver = $includeDirectiveResolver;
    }
    final protected function getIncludeDirectiveResolver(): IncludeDirectiveResolver
    {
        return $this->includeDirectiveResolver ??= $this->instanceManager->getInstance(IncludeDirectiveResolver::class);
    }
    final public function setParser(ParserInterface $parser): void
    {
        $this->parser = $parser;
    }
    final protected function getParser(): ParserInterface
    {
        return $this->parser ??= $this->instanceManager->getInstance(ParserInterface::class);
    }

    /**
     * Convert the GraphQL Query to PoP query in its requested form
     * @return array 2 items: [operationType (string), fieldQuery (string)]
     */
    public function convertFromGraphQLToFieldQuery(
        string $graphQLQuery,
        ?array $variableValues = [],
        ?string $operationName = null
    ): array {
        list(
            $operationType,
            $operationFieldQueryPaths
        ) = $this->convertFromGraphQLToFieldQueryPaths(
            $graphQLQuery,
            $variableValues ?? [],
            $operationName
        );
        $fieldQueries = [];
        foreach ($operationFieldQueryPaths as $operationID => $fieldQueryPaths) {
            $operationFieldQueries = [];
            foreach ($fieldQueryPaths as $fieldQueryLevel) {
                // Join all connections with "."
                $operationFieldQueries[] = implode(
                    QuerySyntax::SYMBOL_RELATIONALFIELDS_NEXTLEVEL,
                    $fieldQueryLevel
                );
            }
            // Join all fields at the same level with ","
            $fieldQueries[] = implode(
                /**
                 * @todo Temporary addition to match `asQueryString` in the AST
                 * Added an extra " "
                 */
                QuerySyntax::SYMBOL_QUERYFIELDS_SEPARATOR . ' ',
                $operationFieldQueries
            );
        }
        return [
            $operationType,
            // Join all operators with a ";"
            implode(
                QuerySyntax::SYMBOL_OPERATIONS_SEPARATOR,
                $fieldQueries
            )
        ];
    }

    /**
     * Convert the GraphQL Query to an array containing all the
     * parts from the query
     * @return array 2 items: [operationType (string), fieldQueryPaths (array)]
     */
    protected function convertFromGraphQLToFieldQueryPaths(
        string $graphQLQuery,
        array $variableValues,
        ?string $operationName = null
    ): array {
        try {
            // If the validation throws an error, stop parsing the script
            $request = $this->parseAndCreateRequest(
                $graphQLQuery,
                $variableValues,
                $operationName
            );
            // Converting the query could also throw an Exception
            list(
                $operationType,
                $fieldQueryPaths
            ) = $this->convertRequestToFieldQueryPaths($request);
        } catch (AbstractParserException $parserError) {
            // The error description is the exception message
            $parserErrorFeedbackItemResolution = $parserError->getFeedbackItemResolution();
            $errorMessage = $parserErrorFeedbackItemResolution->getMessage();
            $extensions = [
                'locations' => [$parserError->getLocation()->toArray()],
                'code' => $parserErrorFeedbackItemResolution->getNamespacedCode(),
            ];
            if ($specifiedByURL = $parserErrorFeedbackItemResolution->getSpecifiedByURL()) {
                $extensions['specifiedBy'] = $specifiedByURL;
            }

            $this->getFeedbackMessageStore()->addQueryError($errorMessage, $extensions);

            // Returning nothing will not process the query
            return [
                null,
                []
            ];
        } catch (Exception $e) {
            /**
             * Send the exception error to the response, but only for DEV.
             * Otherwise, it's a potential security exposure.
             */
            $errorMessage = RootEnvironment::isApplicationEnvironmentDev() ?
                sprintf(
                    $this->__('[Exception (Visible on DEV only)] %s. Trace: %s', 'graphql-parser'),
                    $e->getMessage(),
                    $e->getTraceAsString()
                ) : $this->__('There was an unexpected error', 'graphql-query');

            $this->getFeedbackMessageStore()->addQueryError($errorMessage);

            return [
                null,
                []
            ];
        }
        return [
            $operationType,
            $fieldQueryPaths
        ];
    }

    /**
     * Indicates if the variable must be dealt with as an expression: if its name starts with "_"
     */
    public function treatVariableAsExpression(string $variableName): bool
    {
        return
            substr($variableName, 0, strlen(QuerySymbols::VARIABLE_AS_EXPRESSION_NAME_PREFIX)) == QuerySymbols::VARIABLE_AS_EXPRESSION_NAME_PREFIX
            /**
             * Switched from "%{...}%" to "$__..."
             * @todo Convert expressions from "$__" to "$"
             */
            && !str_starts_with($variableName, '__');
    }

    protected function convertArgumentValue($value)
    {
        /** @var GraphQLQueryModuleConfiguration */
        $moduleConfiguration = App::getModule(GraphQLQueryModule::class)->getConfiguration();
        /**
         * Generate the field AST as composable field `{{ field }}`,
         * so its value can be computed on runtime.
         *
         * @todo Remove this code! It is temporary and a hack to convert to PQL, which is being migrated away!
         */
        if ($value instanceof ResolvedFieldVariableReference) {
            return $this->convertArgumentValueForResolvedFieldVariableReference($value);
        }

        if (
            $value instanceof VariableReference &&
            $moduleConfiguration->enableVariablesAsExpressions() &&
            $this->treatVariableAsExpression($value->getName())
        ) {
            /**
             * If the value is a reference to a variable, and its name starts with "_",
             * then replace it with an expression, so its value can be computed on runtime
             */
            return QueryHelpers::getExpressionQuery($value->getName());
        }

        if ($value instanceof Literal) {
            if (is_string($value->getValue())) {
                return $this->maybeWrapStringInQuotesToAvoidExecutingAsAField($value->getValue());
            }
            return $value->getValue();
        }

        /**
         * @todo Temporary addition to match `asQueryString` in the AST
         * Print again the variable, don't resolve it yet, so the fieldName is found on $dbObject
         */
        if ($value instanceof VariableReference) {
            return '$' . $value->getName();
        }
        // if ($value instanceof VariableReference || $value instanceof Variable) {
        //     return $this->convertArgumentValue($value->getValue());
        // }

        if (is_array($value)) {
            /**
             * When coming from the InputList, its `getValue` is an array of Variables
             */
            return array_map(
                $this->convertArgumentValue(...),
                $value
            );
        }

        if ($value instanceof stdClass) {
            return (object) array_map(
                $this->convertArgumentValue(...),
                (array) $value
            );
        }

        /**
         * If the value is of type InputList, then resolve the array with its variables (under `getValue`)
         */
        if ($value instanceof InputList) {
            $array = [];
            foreach ($value->getAstValue() as $key => $value) {
                $array[$key] = $this->convertArgumentValue($value);
            }
            return $array;
            // return array_map(
            //     $this->convertArgumentValue(...),
            //     $value->getValue()
            // );
        }

        if ($value instanceof InputObject) {
            // Copied from `InputObject->getValue`
            $object = new stdClass();
            foreach ((array) $value->getAstValue() as $key => $value) {
                $object->$key = $this->convertArgumentValue($value);
            }
            return $object;
            // // Convert from array back to stdClass
            // return (object) array_map(
            //     $this->convertArgumentValue(...),
            //     // Convert from stdClass to array
            //     (array) $value->getValue()
            // );
        }

        // Otherwise it may be a scalar value
        if (is_string($value)) {
            return $this->maybeWrapStringInQuotesToAvoidExecutingAsAField($value);
        }

        return $value;
    }

    /**
     * @todo Remove this code! It is temporary and a hack to convert to PQL, which is being migrated away!
     */
    protected function convertArgumentValueForResolvedFieldVariableReference(ResolvedFieldVariableReference $value): string
    {
        $field = $value->getField();
        $fieldQuery = $field->getName();
        if ($field->getArguments() !== []) {
            $fieldQueryArguments = [];
            foreach ($field->getArguments() as $argument) {
                $argumentValue = $this->convertArgumentValue($argument->getValue());
                $argumentValue = $this->maybeConvertArgumentValueForResolvedFieldVariableReference($argumentValue);
                $fieldQueryArguments[] = $argument->getName() . ':' . $argumentValue;
            }
            $fieldQuery .= '(' . implode(',', $fieldQueryArguments) . ')';
        }
        return APIQuerySyntax::SYMBOL_EMBEDDABLE_FIELD_PREFIX
            . $fieldQuery
            . APIQuerySyntax::SYMBOL_EMBEDDABLE_FIELD_SUFFIX;
    }

    /**
     * @todo Remove this code! It is temporary and a hack to convert to PQL, which is being migrated away!
     */
    protected function maybeConvertArgumentValueForResolvedFieldVariableReference(mixed $argumentValue): mixed
    {
        if (is_string($argumentValue) && str_starts_with($argumentValue, '{{')) {
            $argumentValue = substr($argumentValue, 2, -2);
        } elseif (is_array($argumentValue)) {
            $argumentValueItems = [];
            foreach ($argumentValue as $argumentValueKey => $argumentValueValue) {
                $argumentValueItems[] = $argumentValueKey . ':' . $this->maybeConvertArgumentValueForResolvedFieldVariableReference($argumentValueValue);
            }
            $argumentValue = '[' . implode(',', $argumentValueItems) . ']';
        } elseif ($argumentValue instanceof stdClass) {
            $argumentValueItems = [];
            foreach ((array) $argumentValue as $argumentValueKey => $argumentValueValue) {
                $argumentValueItems[] = $argumentValueKey . ':' . $this->maybeConvertArgumentValueForResolvedFieldVariableReference($argumentValueValue);
            }
            $argumentValue = '{' . implode(',', $argumentValueItems) . '}';
        }
        return $argumentValue;
    }

    /**
     * If the string ends with "()" it must be wrapped with quotes "", to make
     * sure it is interpreted as a string, and not as a field.
     *
     * eg: `{ posts(searchfor:"hel()") { id } }`
     * eg: `{ posts(ids:["hel()"]) { id } }`
     *
     * @see https://github.com/leoloso/PoP/issues/743
     */
    protected function maybeWrapStringInQuotesToAvoidExecutingAsAField(string $value): string
    {
        if ($this->getFieldQueryInterpreter()->isFieldArgumentValueAField($value)) {
            return $this->getFieldQueryInterpreter()->wrapStringInQuotes($value);
        }
        return $value;
    }

    protected function convertArguments(array $queryArguments): array
    {
        // Convert the arguments into an array
        $arguments = [];
        foreach ($queryArguments as $argument) {
            $value = $argument->getValue();
            $arguments[$argument->getName()] = $this->convertArgumentValue($value);
        }
        return $arguments;
    }

    protected function convertDirective(Directive $directive): array
    {
        $directiveArgs = $this->convertArguments($directive->getArguments());
        $directiveName = $directive->getName();
        $directiveComposableDirectives = '';
        if ($directive instanceof MetaDirective) {
            $nestedDirectives = array_map(
                $this->convertDirective(...),
                $directive->getNestedDirectives()
            );
            $nestedDirectives = array_map(
                [$this->getFieldQueryInterpreter(), 'convertDirectiveToFieldDirective'],
                $nestedDirectives
            );
            $directiveComposableDirectives = QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING . implode(
                /**
                 * @todo Temporary addition to match `asQueryString` in the AST
                 * Added an extra " "
                 */
                QuerySyntax::SYMBOL_FIELDDIRECTIVE_SEPARATOR . ' ',
                $nestedDirectives
            ) . QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING;
        }
        return $this->getFieldQueryInterpreter()->getDirective(
            $directiveName,
            $directiveArgs,
            $directiveComposableDirectives
        );
    }

    protected function convertField(FieldInterface $field): string
    {
        // Convert the arguments and directives into an array
        $arguments = $this->convertArguments($field->getArguments());
        $fieldDirectives = [];
        foreach ($field->getDirectives() as $directive) {
            $fieldDirectives[] = $this->convertDirective($directive);
        }
        return $this->getFieldQueryInterpreter()->getField(
            $field->getName(),
            $arguments,
            $field->getAlias(),
            false,
            $fieldDirectives
        );
    }

    /**
     * Restrain fields to the model through directive <include(if:isType($model))>
     */
    protected function restrainFieldsByTypeOrInterface(array $fragmentFieldPaths, string $fragmentModel): array
    {
        // Create the <include> directive, if the fragment references the type or interface
        $includeDirective = $this->getFieldQueryInterpreter()->composeFieldDirective(
            $this->getIncludeDirectiveResolver()->getDirectiveName(),
            $this->getFieldQueryInterpreter()->getFieldArgsAsString([
                'if' => $this->getFieldQueryInterpreter()->getField(
                    'isTypeOrImplements',
                    [
                        'typeOrInterface' => $fragmentModel
                    ]
                ),
            ])
        );
        $fragmentFieldPaths = array_map(
            function (array $fragmentFieldPath) use ($includeDirective): array {
                // The field can itself compose other fields. Get the 1st element
                // to apply the directive to the root property only
                $fragmentRootField = $fragmentFieldPath[0];

                // Add the directive to the current directives from the field
                $rootFieldDirectives = $this->getFieldQueryInterpreter()->getFieldDirectives((string)$fragmentRootField);
                if ($rootFieldDirectives) {
                    // The include directive comes first,
                    // so if it evals to false the upcoming directives are not executed
                    $rootFieldDirectives =
                        $includeDirective .
                        /**
                         * @todo Temporary addition to match `asQueryString` in the AST
                         * Added an extra " "
                         */
                        QuerySyntax::SYMBOL_FIELDDIRECTIVE_SEPARATOR . ' ' .
                        $rootFieldDirectives;
                    // Also remove the directive from the root field, since it will be added again below
                    list(
                        $fieldDirectivesOpeningSymbolPos,
                    ) = QueryHelpers::listFieldDirectivesSymbolPositions($fragmentRootField);
                    $fragmentRootField = substr($fragmentRootField, 0, $fieldDirectivesOpeningSymbolPos);
                } else {
                    $rootFieldDirectives = $includeDirective;
                }

                // Replace the first element, adding the directive
                $fragmentFieldPath[0] =
                    $fragmentRootField .
                    QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING .
                    $rootFieldDirectives .
                    QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING;
                return $fragmentFieldPath;
            },
            $fragmentFieldPaths
        );
        return $fragmentFieldPaths;
    }

    protected function processAndAddFieldPaths(ExecutableDocumentInterface $executableDocument, array &$queryFieldPaths, array $fields, array $queryField = []): void
    {
        // Iterate through the query's fields: properties, connections, fragments
        $queryFieldPath = $queryField;
        foreach ($fields as $field) {
            if ($field instanceof LeafField) {
                // Fields are leaves in the graph
                $queryFieldPaths[] = array_merge(
                    $queryFieldPath,
                    [$this->convertField($field)]
                );
            } elseif ($field instanceof RelationalField) {
                // Queries are connections
                $nestedFieldPaths = $this->getFieldPathsFromQuery($executableDocument, $field);
                foreach ($nestedFieldPaths as $nestedFieldPath) {
                    $queryFieldPaths[] = array_merge(
                        $queryFieldPath,
                        $nestedFieldPath
                    );
                }
            } elseif ($field instanceof FragmentReference || $field instanceof InlineFragment) {
                // Replace the fragment reference with its resolved information
                $fragmentReference = $field;
                if ($fragmentReference instanceof FragmentReference) {
                    $fragmentName = $fragmentReference->getName();
                    $fragment = $executableDocument->getDocument()->getFragment($fragmentName);
                    $fragmentFields = $fragment->getFieldsOrFragmentBonds();
                    $fragmentType = $fragment->getModel();
                } else {
                    $fragmentFields = $fragmentReference->getFieldsOrFragmentBonds();
                    $fragmentType = $fragmentReference->getTypeName();
                }

                // Get the fields defined in the fragment
                $fragmentConvertedFieldPaths = [];
                $this->processAndAddFieldPaths($executableDocument, $fragmentConvertedFieldPaths, $fragmentFields);

                // Restrain those fields to the indicated type
                /**
                 * @todo Temporary addition to match `asQueryString` in the AST
                 * @todo Watch out: Here directive <include(if:isTypeOrImplements(...))> was removed,
                 * since it's already resolved via AST, and `->asFieldOutputQueryString` does not print
                 * the directive in the fieldOutput
                 */
                // $fragmentConvertedFieldPaths = $this->restrainFieldsByTypeOrInterface($fragmentConvertedFieldPaths, $fragmentType);

                // Add them to the list of fields in the query
                foreach ($fragmentConvertedFieldPaths as $fragmentFieldPath) {
                    $queryFieldPaths[] = array_merge(
                        $queryFieldPath,
                        $fragmentFieldPath
                    );
                }
            }
        }
    }

    protected function getFieldPathsFromQuery(ExecutableDocumentInterface $executableDocument, RelationalField $query): array
    {
        $queryFieldPaths = [];
        $queryFieldPath = [$this->convertField($query)];

        // Iterate through the query's fields: properties and connections
        if ($fields = $query->getFieldsOrFragmentBonds()) {
            $this->processAndAddFieldPaths($executableDocument, $queryFieldPaths, $fields, $queryFieldPath);
        } else {
            // Otherwise, just add the query field, which doesn't have subfields
            $queryFieldPaths[] = $queryFieldPath;
        }

        return $queryFieldPaths;
    }

    /**
     * Convert the GraphQL to its equivalent fieldQuery.
     * The GraphQL syntax is explained in graphql.org
     * @return array 2 items: [operationType (string), fieldQueryPaths (array)]
     *
     * @see https://graphql.org/learn/queries/
     */
    protected function convertRequestToFieldQueryPaths(ExecutableDocumentInterface $executableDocument): array
    {
        $fieldQueryPaths = [];
        // It is either is a query or a mutation
        $mutations = $queries = [];
        $operations = $executableDocument->getRequestedOperations();

        // @todo Migrate this, currently this code is not working
        if ($operations === []) {
            /** @var GraphQLParserModuleConfiguration */
            $moduleConfiguration = App::getModule(GraphQLParserModule::class)->getConfiguration();
            if ($moduleConfiguration->enableMultipleQueryExecution()) {
                // Add a suggestion indicating to pass __ALL in the query
                App::getFeedbackStore()->documentFeedbackStore->addSuggestion(
                    new DocumentFeedback(
                        new FeedbackItemResolution(
                            SuggestionFeedbackItemProvider::class,
                            SuggestionFeedbackItemProvider::S1,
                            [
                                GraphQLParserQuerySymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME,
                            ]
                        ),
                        LocationHelper::getNonSpecificLocation()
                    )
                );
            }
        }

        foreach ($operations as $operation) {
            if ($operation instanceof QueryOperation) {
                $queries[] = $operation;
                continue;
            }
            if ($operation instanceof MutationOperation) {
                $mutations[] = $operation;
            }
        }
        if ($mutations) {
            $queriesOrMutations = $mutations;
            $operationType = OperationTypes::MUTATION;
        } else {
            $queriesOrMutations = $queries;
            $operationType = OperationTypes::QUERY;
        }
        // BUT, when doing multiple-query execution, it could pass both a query AND a mutation!
        // In that case, execute mutations only, and display a warning on the query
        if ($queries && $mutations) {
            $this->getFeedbackMessageStore()->addQueryWarning(
                $this->__('Cannot execute both queries AND mutations, hence the queries have been ignored, resolving mutations only', 'graphql-query')
            );
        }
        /** @var OperationInterface[] $queriesOrMutations */
        foreach ($queriesOrMutations as $operation) {
            $operationName = $operation->getName();
            $operationFieldPaths = [];
            $this->processAndAddFieldPaths($executableDocument, $operationFieldPaths, $operation->getFieldsOrFragmentBonds());
            $fieldQueryPaths[$operationName] = array_merge(
                $fieldQueryPaths[$operationName] ?? [],
                $operationFieldPaths
            );
        }
        return [
            $operationType,
            $fieldQueryPaths
        ];
    }

    /**
     * Function copied from youshido/graphql/src/Execution/Processor.php
     */
    protected function parseAndCreateRequest(
        string $payload,
        array $variableValues,
        ?string $operationName = null
    ): ExecutableDocumentInterface {
        /**
         * If some variable hasn't been submitted, it will throw an Exception.
         * Let it bubble up
         */
        $document = $this->getParser()->parse($payload);
        $executableDocument = (
            new ExecutableDocument(
                $document,
                new Context($operationName, $variableValues)
            )
        )->validateAndInitialize();
        return $executableDocument;
    }
}
