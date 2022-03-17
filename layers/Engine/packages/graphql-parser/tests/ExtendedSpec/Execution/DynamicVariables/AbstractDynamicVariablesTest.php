<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution\DynamicVariables;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\ExtendedSpec\Constants\QuerySyntax;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReference;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Variable;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\GraphQLParser\Spec\Parser\ParserInterface;
use PoP\Root\AbstractTestCase;
use PoP\Root\Feedback\FeedbackItemResolution;

abstract class AbstractDynamicVariablesTest extends AbstractTestCase
{
    /**
     * @return array<string, mixed> [key]: Component class, [value]: Configuration
     */
    protected static function getComponentClassConfiguration(): array
    {
        $componentClassConfiguration = parent::getComponentClassConfiguration();
        $componentClassConfiguration[\PoP\GraphQLParser\Component::class][\PoP\GraphQLParser\Environment::ENABLE_DYNAMIC_VARIABLES] = static::enabled();
        return $componentClassConfiguration;
    }

    abstract protected static function enabled(): bool;

    protected function getParser(): ParserInterface
    {
        return $this->getService(ParserInterface::class);
    }

    protected function getQueryOperation(?Variable $variable, VariableReference $variableReference): QueryOperation
    {
        $variables = $variable !== null
            ? [
                $variable,
            ]
            : [];
        return new QueryOperation(
            'Op',
            $variables,
            [],
            [
                new RelationalField(
                    'film',
                    null,
                    [
                        new Argument('id', $variableReference, new Location(3, 22)),
                    ],
                    [
                        new LeafField('title', null, [], [], new Location(4, 21)),
                    ],
                    [],
                    new Location(3, 17)
                )
            ],
            new Location(2, 19)
        );
    }

    protected function getContext(array $variableValues): Context
    {
        return new Context('Op', $variableValues);
    }

    protected function getQuery(
        bool $addVariableInOperation,
        bool $useDynamicVariableName,
    ): string {
        $variableName = $useDynamicVariableName ? '$_id' : '$id';
        return sprintf(
            '
            query Op%s {
                film(id: %s) {
                    title
                }
            }
            ',
            $addVariableInOperation ? sprintf('(%s: ID)', $variableName) : '',
            $variableName
        );
    }

    protected function getVariable(string $variableName): Variable
    {
        return new Variable($variableName, 'ID', false, false, false, new Location(2, 22));
    }

    public function testVariableDefinedInOperationAndDynamicVariableName(): void
    {
        $variableName = '_id';
        $variable = $this->getVariable($variableName);
        $context = $this->getContext([
            $variableName => 1,
        ]);
        $variable->setContext($context);
        $variableReference = new VariableReference($variableName, $variable, new Location(3, 26));
        $executableDocument = new ExecutableDocument($this->getParser()->parse($this->getQuery(true, true)), $context);
        $executableDocument->validateAndInitialize();
        $this->assertEquals(
            [
                $this->getQueryOperation($variable, $variableReference),
            ],
            $executableDocument->getRequestedOperations()
        );
    }

    public function testVariableDefinedInOperationAndStaticVariableName(): void
    {
        $variableName = 'id';
        $variable = $this->getVariable($variableName);
        $context = $this->getContext([
            $variableName => 1,
        ]);
        $variable->setContext($context);
        $variableReference = new VariableReference($variableName, $variable, new Location(3, 26));
        $executableDocument = new ExecutableDocument($this->getParser()->parse($this->getQuery(true, false)), $context);
        $executableDocument->validateAndInitialize();
        $this->assertEquals(
            [
                $this->getQueryOperation($variable, $variableReference),
            ],
            $executableDocument->getRequestedOperations()
        );
    }

    // public function testVariablesNotDefinedInOperation(): void
    // {
    //     $parser = $this->getParser();
    //     $query = '
    //         query Op {
    //             film(id: $_id) {
    //                 title
    //             }
    //         }
    //     ';
    //     $document = $parser->parse($query);
    //     $context = new Context('Op', [
    //         '_id' => 1,
    //     ]);
    //     $variable = new Variable('_id', 'ID', false, false, false, new Location(2, 22));
    //     $variable->setContext($context);
    //     $variableReference = $this->enabled()
    //         ? new DynamicVariableReference('_id', $variable, new Location(3, 26))
    //         : new VariableReference('_id', $variable, new Location(3, 26));
    //     $queryOperation = new QueryOperation(
    //         'Op',
    //         [
    //             $variable,
    //         ],
    //         [],
    //         [
    //             new RelationalField(
    //                 'film',
    //                 null,
    //                 [
    //                     new Argument('id', $variableReference, new Location(3, 22)),
    //                 ],
    //                 [
    //                     new LeafField('title', null, [], [], new Location(4, 21)),
    //                 ],
    //                 [],
    //                 new Location(3, 17)
    //             )
    //         ],
    //         new Location(2, 19)
    //     );

    //     $executableDocument = new ExecutableDocument($document, $context);
    //     $executableDocument->validateAndInitialize();
    //     $this->assertEquals(
    //         [
    //             $queryOperation,
    //         ],
    //         $executableDocument->getRequestedOperations()
    //     );
    // }

    // public function testVariablesNotDefinedInOperation(): void
    // {
    //     $parser = $this->getParser();
    //     $query = '
    //         query Op {
    //             film(id: $_id) {
    //                 title
    //             }
    //         }
    //     ';
    //     $document = $parser->parse($query);
    //     $variableReference = $this->enabled()
    //         ? new DynamicVariableReference('_id', null, new Location(3, 26))
    //         : new VariableReference('_id', null, new Location(3, 26));
    //     $queryOperation = new QueryOperation(
    //         'Op',
    //         [],
    //         [],
    //         [
    //             new RelationalField(
    //                 'film',
    //                 null,
    //                 [
    //                     new Argument('id', $variableReference, new Location(3, 22)),
    //                 ],
    //                 [
    //                     new LeafField('title', null, [], [], new Location(4, 21)),
    //                 ],
    //                 [],
    //                 new Location(3, 17)
    //             )
    //         ],
    //         new Location(2, 19)
    //     );

    //     // Test any other operationName than __ALL
    //     $context = new Context(null);
    //     $executableDocument = new ExecutableDocument($document, $context);
    //     $executableDocument->validateAndInitialize();
    //     $this->assertEquals(
    //         [
    //             $queryOperation,
    //         ],
    //         $executableDocument->getRequestedOperations()
    //     );

    //     // Test the __ALL operationName => execute all operations
    //     $context = new Context('__ALL');
    //     $executableDocument = new ExecutableDocument($document, $context);
    //     $executableDocument->validateAndInitialize();
    //     $this->assertEquals(
    //         $this->enabled() ?
    //             [
    //                 $queryOneOperation,
    //                 $queryTwoOperation,
    //             ] : [
    //                 $queryAllOperation,
    //             ],
    //         $executableDocument->getRequestedOperations()
    //     );

    //     // Passing no operationName, and has __ALL in document => execute all operations
    //     // If env var disabled => we must get an error "Must indicate operationName"
    //     $context = new Context('');
    //     $executableDocument = new ExecutableDocument($document, $context);
    //     if (!$this->enabled()) {
    //         $this->expectException(InvalidRequestException::class);
    //         $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_6_1_B))->getMessage());
    //     }
    //     $executableDocument->validateAndInitialize();
    //     if ($this->enabled()) {
    //         $this->assertEquals(
    //             [
    //                 $queryOneOperation,
    //                 $queryTwoOperation,
    //             ],
    //             $executableDocument->getRequestedOperations()
    //         );
    //     }

    //     // Passing no operationName, and does not have __ALL in document =>
    //     // we must get an error "Must indicate operationName"
    //     $query = str_replace('__ALL', '__not_ALL', $query);
    //     $document = $parser->parse($query);
    //     $executableDocument = new ExecutableDocument($document, $context);
    //     $this->expectException(InvalidRequestException::class);
    //     $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_6_1_B))->getMessage());
    //     $executableDocument->validateAndInitialize();
    // }
}
