<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLQuery\Schema;

use Exception;
use GraphQLByPoP\GraphQLParser\Exception\Interfaces\LocationableExceptionInterface;
use GraphQLByPoP\GraphQLParser\Execution\Request;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\InputList;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\InputObject;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\Literal;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Field;
use GraphQLByPoP\GraphQLParser\Parser\Ast\FragmentReference;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces\FieldInterface;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Query;
use GraphQLByPoP\GraphQLParser\Parser\Ast\TypedFragmentReference;
use GraphQLByPoP\GraphQLParser\Parser\Parser;
use GraphQLByPoP\GraphQLParser\Validator\RequestValidator\RequestValidator;
use GraphQLByPoP\GraphQLQuery\ComponentConfiguration;
use GraphQLByPoP\GraphQLQuery\Schema\QuerySymbols;
use InvalidArgumentException;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\Engine\DirectiveResolvers\IncludeDirectiveResolver;
use PoP\FieldQuery\QueryHelpers;
use PoP\FieldQuery\QuerySyntax;
use PoP\Translation\TranslationAPIInterface;

class GraphQLQueryConvertor implements GraphQLQueryConvertorInterface
{
    protected TranslationAPIInterface $translationAPI;
    protected FeedbackMessageStoreInterface $feedbackMessageStore;

    public function __construct(
        TranslationAPIInterface $translationAPI,
        FeedbackMessageStoreInterface $feedbackMessageStore
    ) {
        $this->translationAPI = $translationAPI;
        $this->feedbackMessageStore = $feedbackMessageStore;
    }

    /**
     * Convert the GraphQL Query to PoP query in its requested form
     * @return array 2 items: [operationType (string), fieldQuery (string)]
     */
    public function convertFromGraphQLToFieldQuery(
        string $graphQLQuery,
        ?array $variables = [],
        bool $enableMultipleQueryExecution = false,
        ?string $operationName = null
    ): array {
        list(
            $operationType,
            $operationFieldQueryPaths
        ) = $this->convertFromGraphQLToFieldQueryPaths(
            $graphQLQuery,
            $variables ?? [],
            $enableMultipleQueryExecution,
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
        array $variables,
        bool $enableMultipleQueryExecution,
        ?string $operationName = null
    ): array {
        try {
            // If the validation throws an error, stop parsing the script
            $request = $this->parseAndCreateRequest(
                $graphQLQuery,
                $variables,
                $enableMultipleQueryExecution,
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
            $this->feedbackMessageStore->addQueryError($errorMessage, $extensions);
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
     *
     * @param string $variableName
     * @return boolean
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
        } elseif ($value instanceof VariableReference || $value instanceof Variable || $value instanceof Literal) {
            return $value->getValue();
        } elseif (is_array($value)) {
            /**
             * When coming from the InputList, its `getValue` is an array of Variables
             */
            return array_map(
                [$this, 'convertArgumentValue'],
                $value
            );
        } elseif ($value instanceof InputList || $value instanceof InputObject) {
            return array_map(
                [$this, 'convertArgumentValue'],
                $value->getValue()
            );
        }
        // Otherwise it may be a scalar value
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
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();

        // Convert the arguments and directives into an array
        $arguments = $this->convertArguments($field->getArguments());
        $directives = [];
        $fieldDirectives = $field->getDirectives();
        $rootAndComposableDirectives = [];
        $rootDirectivePositions = $composableDirectivesByPosition = [];
        /**
         * Enable composable directives:
         * Executing <directive1<directive11,directive12<directive123>>> can be done doing
         * @directive1 @directive11(nestedUnder: -1) @directive12(nestedUnder: -2) @directive123(nestedUnder -1)
         * In this case, "nestedUnder" indicates the relative position from the directive,
         * to its parent directive (under which it must be nested).
         */
        $enableComposableDirectives = ComponentConfiguration::enableComposableDirectives();
        /**
         * The first pass goes from right to left, as to enable composable directives:
         * because we can have <directive1<directive2<directive3>>>, represented as
         * @directive1 @directive2(nestedUnder: -1) @directive3(nestedUnder -1),
         * then directive 3 must first be added under directive2, and then this one
         * must be added under directive1.
         * If we iterated from left to right, directive3 would not be added under
         * directive1=>directive2
         */
        $directiveCount = count($fieldDirectives);
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
                if (isset($directiveArgs[SchemaElements::DIRECTIVE_PARAM_NESTED_UNDER])) {
                    $nestedUnder = $directiveArgs[SchemaElements::DIRECTIVE_PARAM_NESTED_UNDER];
                    unset($directiveArgs[SchemaElements::DIRECTIVE_PARAM_NESTED_UNDER]);
                }
                /**
                 * Because we're iterating from right to left, if this directive
                 * has been defined as composing to another directive,
                 * it already has this data
                 */
                if (isset($composableDirectivesByPosition[$counter])) {
                    $directiveComposableDirectives = QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING . implode(
                        QuerySyntax::SYMBOL_FIELDDIRECTIVE_SEPARATOR,
                        array_map(
                            [$fieldQueryInterpreter, 'convertDirectiveToFieldDirective'],
                            $composableDirectivesByPosition[$counter]
                        )
                    ) . QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING;
                }
            }
            $directiveName = $directive->getName();
            $convertedDirective = $fieldQueryInterpreter->getDirective(
                $directiveName,
                $directiveArgs,
                $directiveComposableDirectives
            );
            $rootAndComposableDirectives[$counter] = $convertedDirective;
            if ($enableComposableDirectives && $nestedUnder !== null) {
                if (!is_int($nestedUnder) || !($nestedUnder < 0)) {
                    $this->feedbackMessageStore->addQueryError(
                        sprintf(
                            $this->translationAPI->__('Param \'%s\' must be a negative integer, hence value \'%s\' in directive \'%s\' has been ignored', 'graphql-query'),
                            SchemaElements::DIRECTIVE_PARAM_NESTED_UNDER,
                            $nestedUnder,
                            $directiveName
                        )
                    );
                } elseif ((-1 * $nestedUnder) > $counter) {
                    $this->feedbackMessageStore->addQueryError(
                        sprintf(
                            $this->translationAPI->__('There is no directive at position \'%s\' (set under param \'%s\') relative to directive \'%s\'', 'graphql-query'),
                            $nestedUnder,
                            SchemaElements::DIRECTIVE_PARAM_NESTED_UNDER,
                            $directiveName
                        )
                    );
                } else {
                    // From the current position, move "$nestedUnder" positions to the left
                    // (it's a negative int)
                    $nestedUnderPos = $counter + $nestedUnder;
                    $composableDirectivesByPosition[$nestedUnderPos] ??= [];
                    $composableDirectivesByPosition[$nestedUnderPos][] = $convertedDirective;
                }
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
        return $fieldQueryInterpreter->getField(
            $field->getName(),
            $arguments,
            $field->getAlias(),
            false,
            $directives
        );
    }

    /**
     * Restrain fields to the model through directive <include(if:isType($model))>
     *
     * @return array
     */
    protected function restrainFieldsByTypeOrInterface(array $fragmentFieldPaths, string $fragmentModel): array
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var DirectiveResolverInterface */
        $includeDirectiveResolver = $instanceManager->getInstance(IncludeDirectiveResolver::class);
        // Create the <include> directive, if the fragment references the type or interface
        $includeDirective = $fieldQueryInterpreter->composeFieldDirective(
            $includeDirectiveResolver->getDirectiveName(),
            $fieldQueryInterpreter->getFieldArgsAsString([
                'if' => $fieldQueryInterpreter->getField(
                    'or',
                    [
                        'values' => [
                            $fieldQueryInterpreter->getField(
                                'isType',
                                [
                                    'type' => $fragmentModel
                                ]
                            ),
                            $fieldQueryInterpreter->getField(
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
            function (array $fragmentFieldPath) use ($includeDirective, $fieldQueryInterpreter): array {
                // The field can itself compose other fields. Get the 1st element
                // to apply the directive to the root property only
                $fragmentRootField = $fragmentFieldPath[0];

                // Add the directive to the current directives from the field
                $rootFieldDirectives = $fieldQueryInterpreter->getFieldDirectives((string)$fragmentRootField);
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

    protected function processAndAddFieldPaths(Request $request, array &$queryFieldPaths, array $fields, array $queryField = []): void
    {
        // Iterate through the query's fields: properties, connections, fragments
        $queryFieldPath = $queryField;
        foreach ($fields as $field) {
            if ($field instanceof Field) {
                // Fields are leaves in the graph
                $queryFieldPaths[] = array_merge(
                    $queryFieldPath,
                    [$this->convertField($field)]
                );
            } elseif ($field instanceof Query) {
                // Queries are connections
                $nestedFieldPaths = $this->getFieldPathsFromQuery($request, $field);
                foreach ($nestedFieldPaths as $nestedFieldPath) {
                    $queryFieldPaths[] = array_merge(
                        $queryFieldPath,
                        $nestedFieldPath
                    );
                }
            } elseif ($field instanceof FragmentReference || $field instanceof TypedFragmentReference) {
                // Replace the fragment reference with its resolved information
                $fragmentReference = $field;
                if ($fragmentReference instanceof FragmentReference) {
                    $fragmentName = $fragmentReference->getName();
                    $fragment = $request->getFragment($fragmentName);
                    $fragmentFields = $fragment->getFields();
                    $fragmentType = $fragment->getModel();
                } elseif ($fragmentReference instanceof TypedFragmentReference) {
                    $fragmentFields = $fragmentReference->getFields();
                    $fragmentType = $fragmentReference->getTypeName();
                }

                // Get the fields defined in the fragment
                $fragmentConvertedFieldPaths = [];
                $this->processAndAddFieldPaths($request, $fragmentConvertedFieldPaths, $fragmentFields);

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

    protected function getFieldPathsFromQuery(Request $request, Query $query): array
    {
        $queryFieldPaths = [];
        $queryFieldPath = [$this->convertField($query)];

        // Iterate through the query's fields: properties and connections
        if ($fields = $query->getFields()) {
            $this->processAndAddFieldPaths($request, $queryFieldPaths, $fields, $queryFieldPath);
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
    protected function convertRequestToFieldQueryPaths(Request $request): array
    {
        $fieldQueryPaths = [];
        // It is either is a query or a mutation
        $mutations = $request->getMutations();
        $queries = $request->getQueries();
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
            $this->feedbackMessageStore->addQueryWarning(
                $this->translationAPI->__('Cannot execute both queries AND mutations, hence the queries have been ignored, resolving mutations only', 'graphql-query')
            );
        }
        foreach ($queriesOrMutations as $query) {
            $operationLocation = $query->getLocation();
            $operationID = sprintf(
                '%s-%s',
                $operationLocation->getLine(),
                $operationLocation->getColumn()
            );
            $fieldQueryPaths[$operationID] = array_merge(
                $fieldQueryPaths[$operationID] ?? [],
                $this->getFieldPathsFromQuery($request, $query)
            );
        }
        return [
            $operationType,
            $fieldQueryPaths
        ];
    }

    /**
     * Function copied from youshido/graphql/src/Execution/Processor.php
     *
     * @param [type] $payload
     * @param array $variables
     * @return void
     */
    protected function parseAndCreateRequest(
        string $payload,
        array $variables,
        bool $enableMultipleQueryExecution,
        ?string $operationName = null
    ): Request {
        if (empty($payload)) {
            throw new InvalidArgumentException(
                $this->translationAPI->__('Must provide an operation.', 'graphql-query')
            );
        }

        $parser  = new Parser();
        $parsedData = $parser->parse($payload);

        // GraphiQL sends the operationName to execute in the payload, under "operationName"
        // This is required when the payload contains multiple queries
        if (is_null($operationName)) {
            /**
             * If not enabling multiple query execution, validate that
             * only one operation was submitted.
             *
             * From the GraphQL spec:
             *
             * > If operationName is null:
             * >     If document contains exactly one operation.
             * >         Return the Operation contained in the document.
             * >     Otherwise produce a query error requiring operationName.
             *
             * @see https://spec.graphql.org/draft/#GetOperation()
             * @see https://spec.graphql.org/draft/#sel-EANLHDBFBGCBFDCBnmD
             */
            if (!$enableMultipleQueryExecution) {
                $operationCount = count($parsedData['queryOperations']) + count($parsedData['mutationOperations']);
                if ($operationCount > 1) {
                    throw new InvalidArgumentException(sprintf(
                        $this->translationAPI->__(
                            'Feature \'Multiple Query Execution\' is not enabled, so can execute 1 operation only, but %s operations were submitted (\'%s\')',
                            'graphql-query'
                        ),
                        $operationCount,
                        implode(
                            '\', \'',
                            array_merge(
                                array_map(
                                    function (array $operation): string {
                                        return $operation['name'];
                                    },
                                    $parsedData['queryOperations']
                                ),
                                array_map(
                                    function (array $operation): string {
                                        return $operation['name'];
                                    },
                                    $parsedData['mutationOperations']
                                )
                            )
                        )
                    ));
                }
            }
        } else {
            /**
             * Hack! Because GraphiQL does not allow to execute more than 1 operation,
             * (so it doesn't support query batching), then this artificial query
             * indicates to execute all (i.e. execute all operations but this one):
             * ```
             *   query __ALL { id }
             * ```
             */
            if ($enableMultipleQueryExecution && strtoupper($operationName) == ClientSymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME) {
                // Find the position and number of queries processed by this operation
                foreach ($parsedData['queryOperations'] as $queryOperation) {
                    if ($queryOperation['name'] == $operationName) {
                        array_splice(
                            $parsedData['queries'],
                            $queryOperation['position'],
                            $queryOperation['numberItems']
                        );
                        break;
                    }
                }
                foreach ($parsedData['mutationOperations'] as $mutationOperation) {
                    if ($mutationOperation['name'] == $operationName) {
                        array_splice(
                            $parsedData['mutations'],
                            $mutationOperation['position'],
                            $mutationOperation['numberItems']
                        );
                        break;
                    }
                }
            } else {
                /**
                 * Find the position and number of queries processed by this operation
                 * If the operation is the same one, then that's it, retrieve it.
                 *
                 * Otherwise, remove it from the entry, so that if sending an operationName,
                 * that does not exist, the set to execute is an empty array
                 */
                if ($parsedData['queryOperations']) {
                    for ($i = count($parsedData['queryOperations']) - 1; $i >= 0; $i--) {
                        $queryOperation = $parsedData['queryOperations'][$i];
                        if ($queryOperation['name'] == $operationName) {
                            $parsedData['queries'] = array_slice(
                                $parsedData['queries'],
                                $queryOperation['position'],
                                $queryOperation['numberItems']
                            );
                            break;
                        } else {
                            array_splice(
                                $parsedData['queries'],
                                $queryOperation['position'],
                                $queryOperation['numberItems']
                            );
                        }
                    }
                } else {
                    // Make sure no queries are executed
                    unset($parsedData['queries']);
                }
                if ($parsedData['mutationOperations']) {
                    for ($i = count($parsedData['mutationOperations']) - 1; $i >= 0; $i--) {
                        $mutationOperation = $parsedData['mutationOperations'][$i];
                        if ($mutationOperation['name'] == $operationName) {
                            $parsedData['mutations'] = array_slice(
                                $parsedData['mutations'],
                                $mutationOperation['position'],
                                $mutationOperation['numberItems']
                            );
                            break;
                        } else {
                            array_splice(
                                $parsedData['mutations'],
                                $mutationOperation['position'],
                                $mutationOperation['numberItems']
                            );
                        }
                    }
                } else {
                    // Make sure no mutations are executed
                    unset($parsedData['mutations']);
                }
                /**
                 * From the GraphQL spec:
                 *
                 * > If operation was not found, produce a query error.
                 *
                 * @see https://spec.graphql.org/draft/#GetOperation()
                 * @see https://spec.graphql.org/draft/#sel-IANLHCDBDCAACCmB3L
                 */
                if (empty($parsedData['queries']) && empty($parsedData['mutations'])) {
                    throw new InvalidArgumentException(sprintf(
                        $this->translationAPI->__('No operation with name \'%s\' was submitted.', 'graphql-query'),
                        $operationName
                    ));
                }
            }
        }

        $request = new Request($parsedData, $variables);

        // If the validation fails, it will throw an exception
        (new RequestValidator())->validate($request);

        // Return the request
        return $request;
    }
}
