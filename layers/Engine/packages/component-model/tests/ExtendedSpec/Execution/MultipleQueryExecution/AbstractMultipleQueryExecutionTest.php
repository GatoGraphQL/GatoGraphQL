<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ExtendedSpec\Execution\MultipleQueryExecution;

use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\GraphQLParser\ExtendedSpec\Parser\Parser;
use PoP\GraphQLParser\Exception\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\GraphQLParser\Spec\Parser\ParserInterface;
use PoP\Root\AbstractTestCase;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Module\ModuleInterface;

abstract class AbstractMultipleQueryExecutionTest extends AbstractTestCase
{
    private ?ParserInterface $parser = null;

    /**
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    protected static function getModuleClassConfiguration(): array
    {
        $moduleClassConfiguration = parent::getModuleClassConfiguration();
        $moduleClassConfiguration[\PoP\GraphQLParser\Module::class][\PoP\GraphQLParser\Environment::ENABLE_MULTIPLE_QUERY_EXECUTION] = static::enabled();
        return $moduleClassConfiguration;
    }

    abstract protected static function enabled(): bool;

    protected function getParser(): ParserInterface
    {
        return $this->parser ??= new Parser();
    }

    public function testMultipleQueryExecution(): void
    {
        $parser = $this->getParser();
        $query = '
            query One {
                film(id: 1) {
                    title
                }
            }

            query Two {
                post(id: 2) {
                    title
                }
            }

            query ExecuteAll @dependsOn(operations: ["One", "Two"]) {
                id
            }
        ';
        $document = $parser->parse($query);
        $queryOneOperation = new QueryOperation(
            'One',
            [],
            [],
            [
                new RelationalField(
                    'film',
                    null,
                    [
                        new Argument('id', new Literal(1, new Location(3, 26)), new Location(3, 22)),
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
        $queryTwoOperation = new QueryOperation(
            'Two',
            [],
            [],
            [
                new RelationalField(
                    'post',
                    null,
                    [
                        new Argument('id', new Literal(2, new Location(9, 26)), new Location(9, 22)),
                    ],
                    [
                        new LeafField('title', null, [], [], new Location(10, 21)),
                    ],
                    [],
                    new Location(9, 17)
                )
            ],
            new Location(8, 19)
        );
        $queryAllOperation = new QueryOperation(
            'ExecuteAll',
            [],
            [
                new Directive('dependsOn', [
                    new Argument('operations', new InputList([new Literal("One", new Location(14, 55)), new Literal("Two", new Location(14, 62))], new Location(14, 53)), new Location(14, 41))
                ], new Location(14, 31))
            ],
            [
                new LeafField('id', null, [], [], new Location(15, 17))
            ],
            new Location(14, 19)
        );

        // Test any other operationName than "ExecuteAll"
        $context = new Context('Two');
        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertEquals(
            [
                $queryTwoOperation,
            ],
            $executableDocument->getMultipleOperationsToExecute()
        );

        // Test the "ExecuteAll" operationName => execute all operations
        $context = new Context('ExecuteAll');
        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertEquals(
            $this->enabled() && $this->hasOperationDependencyDefinerDirectiveBeenDefined() ?
                [
                    $queryOneOperation,
                    $queryTwoOperation,
                    $queryAllOperation,
                ] : [
                    $queryAllOperation,
                ],
            $executableDocument->getMultipleOperationsToExecute()
        );

        // Passing no operationName => we must get an error "Must indicate operationName"
        $context = new Context('');
        $executableDocument = new ExecutableDocument($document, $context);
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_6_1_B))->getMessage());
        $executableDocument->validateAndInitialize();
    }

    protected function hasOperationDependencyDefinerDirectiveBeenDefined(): bool
    {
        return false;
    }
}
