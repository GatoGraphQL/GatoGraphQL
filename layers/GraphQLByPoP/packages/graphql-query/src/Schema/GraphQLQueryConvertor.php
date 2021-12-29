<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLQuery\Schema;

use Exception;
use GraphQLByPoP\GraphQLQuery\ComponentConfiguration;
use InvalidArgumentException;
use PoP\BasicService\BasicServiceTrait;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\Engine\DirectiveResolvers\IncludeDirectiveResolver;
use PoP\FieldQuery\QueryHelpers;
use PoP\FieldQuery\QuerySyntax;
use PoP\GraphQLParser\Execution\ExecutableDocument;
use PoP\GraphQLParser\Parser\ParserInterface;
use PoPBackbone\GraphQLParser\Exception\LocationableExceptionInterface;
use PoPBackbone\GraphQLParser\Execution\Context;
use PoPBackbone\GraphQLParser\Execution\ExecutableDocumentInterface;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\InputList;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\InputObject;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Literal;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;
use PoPBackbone\GraphQLParser\Parser\Ast\FieldInterface;
use PoPBackbone\GraphQLParser\Parser\Ast\FragmentReference;
use PoPBackbone\GraphQLParser\Parser\Ast\InlineFragment;
use PoPBackbone\GraphQLParser\Parser\Ast\LeafField;
use PoPBackbone\GraphQLParser\Parser\Ast\MutationOperation;
use PoPBackbone\GraphQLParser\Parser\Ast\OperationInterface;
use PoPBackbone\GraphQLParser\Parser\Ast\QueryOperation;
use PoPBackbone\GraphQLParser\Parser\Ast\RelationalField;
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
                QuerySyntax::SYMBOL_QUERYFIELDS_SEPARATOR,
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
        } catch (Exception $e) {
            // Save the error
            $errorMessage = $e->getMessage();
            // Retrieve the location of the error
            $location = ($e instanceof LocationableExceptionInterface) ?
                $e->getLocation()->toArray() :
                null;
            $extensions = [];
            if (!is_null($location)) {
                $extensions['location'] = $location;
            }
            $this->getFeedbackMessageStore()->addQueryError($errorMessage, $extensions);
            // Returning nothing will not process the query
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
        return substr($variableName, 0, strlen(QuerySymbols::VARIABLE_AS_EXPRESSION_NAME_PREFIX)) == QuerySymbols::VARIABLE_AS_EXPRESSION_NAME_PREFIX;
    }

    protected function convertArgumentValue($value)
    {
        /**
         * If the value is of type InputList, then resolve the array with its variables (under `getValue`)
         */
        if (
            $value instanceof VariableReference &&
            ComponentConfiguration::enableVariablesAsExpressions() &&
            $this->treatVariableAsExpression($value->getName())
        ) {
            /**
             * If the value is a reference to a variable, and its name starts with "_",
             * then replace it with an expression, so its value can be computed on runtime
             */
            return QueryHelpers::getExpressionQuery($value->getName());
        } elseif ($value instanceof Literal) {
            if (is_string($value->getValue())) {
                return $this->maybeWrapStringInQuotesToAvoidExecutingAsAField($value->getValue());
            }
            return $value->getValue();
        } elseif ($value instanceof VariableReference || $value instanceof Variable) {
            return $this->convertArgumentValue($value->getValue());
        } elseif (is_array($value)) {
            /**
             * When coming from the InputList, its `getValue` is an array of Variables
             */
            return array_map(
                [$this, 'convertArgumentValue'],
                $value
            );
        } elseif ($value instanceof stdClass) {
            return (object) array_map(
                [$this, 'convertArgumentValue'],
                (array) $value
            );
        } elseif ($value instanceof InputList) {
            return array_map(
                [$this, 'convertArgumentValue'],
                $value->getValue()
            );
        } elseif ($value instanceof InputObject) {
            // Convert from array back to stdClass
            return (object) array_map(
                [$this, 'convertArgumentValue'],
                // Convert from stdClass to array
                (array) $value->getValue()
            );
        }
        // Otherwise it may be a scalar value
        if (is_string($value)) {
            return $this->maybeWrapStringInQuotesToAvoidExecutingAsAField($value);
        }
        return $value;
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

    protected function convertField(FieldInterface $field): string
    {
        // Convert the arguments and directives into an array
        $arguments = $this->convertArguments($field->getArguments());
        $directives = [];
        $fieldDirectives = $field->getDirectives();
        $rootAndComposableDirectives = [];
        $rootDirectivePositions = $composableDirectivesByPosition = [];
        /**
         * Enable composable directives:
         * Executing <directive1<directive11,directive12<directive123>>> can be done doing
         * @directive1(affect: [1, 2]) @directive11(affect: [1]) @directive12(affect: [1]) @directive123
         * In this case, "affect" indicates the relative position from the meta-directive
         * to its nested/affected directive.
         */
        $enableComposableDirectives = ComponentConfiguration::enableComposableDirectives();
        /**
         * Comment 29/12: Move the param, from "nestedUnder" under the directive
         * to "affect" under the meta-directive. For simplicity, bridge the new
         * logic to the previous logic (this code is temporary).
         */
        $directiveCount = count($fieldDirectives);
        $nestedUnderPositions = [];
        if ($enableComposableDirectives) {
            $counter = 0;
            foreach ($fieldDirectives as $directive) {
                $directiveName = $directive->getName();
                $directiveArgs = $this->convertArguments($directive->getArguments());
                $nestedUnder = null;
                /**
                 * Check if it's a nested directive and, if so, remove param "nestedUnder"
                 * which is not used by the directive (it's a "meta" param)
                 */
                if (isset($directiveArgs[SchemaElements::DIRECTIVE_PARAM_AFFECT_DIRECTIVES_UNDER_POS])) {
                    $directiveNestedUnderPositions = (array) $directiveArgs[SchemaElements::DIRECTIVE_PARAM_AFFECT_DIRECTIVES_UNDER_POS];
                    if (!is_array($directiveNestedUnderPositions)) {
                        $this->getFeedbackMessageStore()->addQueryError(
                            sprintf(
                                $this->getTranslationAPI()->__('Param \'%s\' must be an array of positive integers, hence value \'%s\' in directive \'%s\' has been ignored', 'graphql-query'),
                                SchemaElements::DIRECTIVE_PARAM_AFFECT_DIRECTIVES_UNDER_POS,
                                $directiveNestedUnderPositions,
                                $directiveName
                            )
                        );
                    } else {
                        /** @var int[] $directiveNestedUnderPositions */
                        foreach ($directiveNestedUnderPositions as $nestedUnder) {
                            if (!($nestedUnder > 0)) {
                                $this->getFeedbackMessageStore()->addQueryError(
                                    sprintf(
                                        $this->getTranslationAPI()->__('Param \'%s\' must be a positive integer, hence value \'%s\' in directive \'%s\' has been ignored', 'graphql-query'),
                                        SchemaElements::DIRECTIVE_PARAM_AFFECT_DIRECTIVES_UNDER_POS,
                                        $nestedUnder,
                                        $directiveName
                                    )
                                );
                                continue;
                            } elseif ($nestedUnder > ($directiveCount - $counter)) {
                                $this->getFeedbackMessageStore()->addQueryError(
                                    sprintf(
                                        $this->getTranslationAPI()->__('There is no directive at position \'%s\' (set under param \'%s\') relative to directive \'%s\'', 'graphql-query'),
                                        $nestedUnder,
                                        SchemaElements::DIRECTIVE_PARAM_AFFECT_DIRECTIVES_UNDER_POS,
                                        $directiveName
                                    )
                                );
                                continue;
                            }
                            $nestedUnderPositions[$counter + $nestedUnder] = (int) (-1 * $nestedUnder);
                        }
                    }
                }
                $counter++;
            }
        }
        /**
         * The first pass goes from right to left, as to enable composable directives:
         * because we can have <directive1<directive2<directive3>>>, represented as
         * @directive1 @directive2(nestedUnder: -1) @directive3(nestedUnder -1),
         * then directive 3 must first be added under directive2, and then this one
         * must be added under directive1.
         * If we iterated from left to right, directive3 would not be added under
         * directive1=>directive2
         */
        $counter = $directiveCount - 1;
        foreach (array_reverse($fieldDirectives) as $directive) {
            $directiveArgs = $this->convertArguments($directive->getArguments());
            $nestedUnder = null;
            $directiveComposableDirectives = '';
            if ($enableComposableDirectives) {
                /**
                 * Check if it's a nested directive and, if so, remove param "nestedUnder"
                 * which is not used by the directive (it's a "meta" param)
                 */
                if (isset($directiveArgs[SchemaElements::DIRECTIVE_PARAM_AFFECT_DIRECTIVES_UNDER_POS])) {
                    unset($directiveArgs[SchemaElements::DIRECTIVE_PARAM_AFFECT_DIRECTIVES_UNDER_POS]);
                }
                $nestedUnder = $nestedUnderPositions[$counter] ?? null;
                /**
                 * Because we're iterating from right to left, if this directive
                 * has been defined as composing to another directive,
                 * it already has this data
                 */
                if (isset($composableDirectivesByPosition[$counter])) {
                    $directiveComposableDirectives = QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING . implode(
                        QuerySyntax::SYMBOL_FIELDDIRECTIVE_SEPARATOR,
                        array_map(
                            [$this->getFieldQueryInterpreter(), 'convertDirectiveToFieldDirective'],
                            $composableDirectivesByPosition[$counter]
                        )
                    ) . QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING;
                }
            }
            $directiveName = $directive->getName();
            $convertedDirective = $this->getFieldQueryInterpreter()->getDirective(
                $directiveName,
                $directiveArgs,
                $directiveComposableDirectives
            );
            $rootAndComposableDirectives[$counter] = $convertedDirective;
            if ($enableComposableDirectives && $nestedUnder !== null) {
                // From the current position, move "$nestedUnder" positions to the left
                // (it's a negative int)
                $nestedUnderPos = $counter + $nestedUnder;
                $composableDirectivesByPosition[$nestedUnderPos] ??= [];
                $composableDirectivesByPosition[$nestedUnderPos][] = $convertedDirective;
            } else {
                // Because we're iterating from right to left, place the item
                // at the beginning
                array_unshift($rootDirectivePositions, $counter);
            }
            $counter--;
        }
        /**
         * Move the root directives (i.e. not nested ones) to the directives array
         */
        foreach ($rootDirectivePositions as $pos) {
            $rootDirective = $rootAndComposableDirectives[$pos];
            $directives[] = $rootDirective;
        }
        return $this->getFieldQueryInterpreter()->getField(
            $field->getName(),
            $arguments,
            $field->getAlias(),
            false,
            $directives
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
                    'or',
                    [
                        'values' => [
                            $this->getFieldQueryInterpreter()->getField(
                                'isType',
                                [
                                    'type' => $fragmentModel
                                ]
                            ),
                            $this->getFieldQueryInterpreter()->getField(
                                'implements',
                                [
                                    'interface' => $fragmentModel
                                ]
                            )
                        ],
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
                        QuerySyntax::SYMBOL_FIELDDIRECTIVE_SEPARATOR .
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
                $fragmentConvertedFieldPaths = $this->restrainFieldsByTypeOrInterface($fragmentConvertedFieldPaths, $fragmentType);

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
                $this->getTranslationAPI()->__('Cannot execute both queries AND mutations, hence the queries have been ignored, resolving mutations only', 'graphql-query')
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
        if (empty($payload)) {
            throw new InvalidArgumentException(
                $this->getTranslationAPI()->__('Must provide an operation.', 'graphql-query')
            );
        }

        /**
         * If some variable hasn't been submitted, it will throw an Exception.
         * Let it bubble up
         */
        $document = $this->getParser()->parse($payload);
        $executableDocument = (new ExecutableDocument($document, new Context($operationName, $variableValues)))->validateAndInitialize();
        return $executableDocument;
    }
}
