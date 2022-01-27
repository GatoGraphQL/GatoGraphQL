<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Execution;

use PoP\GraphQLParser\Error\GraphQLErrorMessageProviderInterface;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\GraphQLParser\Spec\Parser\ParserInterface;
use PoP\Root\AbstractTestCase;

class ExecutableDocumentTest extends AbstractTestCase
{
    protected function getParser(): ParserInterface
    {
        return $this->getService(ParserInterface::class);
    }

    protected function getGraphQLErrorMessageProvider(): GraphQLErrorMessageProviderInterface
    {
        return $this->getService(GraphQLErrorMessageProviderInterface::class);
    }

    public function testGetVariableFromContext()
    {
        $parser = $this->getParser();

        // Validate that there are no errors <= no Exception is thrown
        $document = $parser->parse('
            query SomeQuery($includeUsers: Boolean) {
              users {
                id
                name @include(if: $includeUsers)
              }
            }
        ');
        $context = new Context(null, [
            'includeUsers' => true,
        ]);
        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertTrue(true);
    }

    public function testGetVariableDefaultValue()
    {
        $parser = $this->getParser();
        $document = $parser->parse('
            query SomeQuery($includeUsers: Boolean = true) {
              users {
                id
                name @include(if: $includeUsers)
              }
            }
        ');
        $context = new Context();
        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertTrue(true);
    }

    public function testRequestDefinedOperation()
    {
        $parser = $this->getParser();
        $document = $parser->parse('
            query SomeQuery {
              users {
                id
                name
              }
            }
        ');
        $context = new Context('SomeQuery');
        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertTrue(true);
    }

    public function testRequestOneOfDefinedOperation()
    {
        $parser = $this->getParser();
        $document = $parser->parse('
            query SomeQuery {
              users {
                id
                name
              }
            }

            query AnotherQuery {
              posts {
                id
                title
              }
            }
        ');
        $context = new Context('SomeQuery');
        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertTrue(true);
    }

    public function testNonUniqueOperation()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage($this->getGraphQLErrorMessageProvider()->getDuplicateOperationNameErrorMessage());
        $parser = $this->getParser();
        $document = $parser->parse('
            query SomeQuery {
              users {
                id
                name
              }
            }

            query AnotherQuery {
              posts {
                id
                title
              }
            }
        ');
        $context = new Context();
        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertTrue(true);
    }

    public function testMissingVariableValue()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage($this->getGraphQLErrorMessageProvider()->getValueIsNotSetForVariableErrorMessage());
        $parser = $this->getParser();
        $document = $parser->parse('
            query SomeQuery($format: String) {
              users {
                id
                name(format: $format)
              }
            }
        ');
        $context = new Context();
        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
    }

    public function testMissingVariableValueForDirective()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage($this->getGraphQLErrorMessageProvider()->getValueIsNotSetForVariableErrorMessage());
        $parser = $this->getParser();
        $document = $parser->parse('
            query SomeQuery($includeUsers: Boolean) {
              users {
                id
                name @include(if: $includeUsers)
              }
            }
        ');
        $context = new Context();
        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
    }

    public function testOperationDoesNotExist()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage($this->getGraphQLErrorMessageProvider()->getNoOperationMatchesNameErrorMessage());
        $parser = $this->getParser();
        $document = $parser->parse('
            query SomeQuery {
              users {
                id
                name
              }
            }
        ');
        $context = new Context('AnotherOp');
        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
    }

    public function testNonInitializedRequest()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage($this->getGraphQLErrorMessageProvider()->getExecuteValidationErrorMessage('getRequestedOperations'));
        $parser = $this->getParser();
        $document = $parser->parse('{ id }');
        $context = new Context();
        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->getRequestedOperations();
    }

    public function testRequestedOperationsMatchOperations()
    {
        $parser = $this->getParser();
        $document = $parser->parse('{ film(id: 1 filmID: 2) { title } }');
        $context = new Context();
        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertEquals(
            $executableDocument->getRequestedOperations(),
            [
                new QueryOperation('', [], [], [
                    new RelationalField('film', null, [
                        new Argument('id', new Literal(1, new Location(1, 12)), new Location(1, 8)),
                        new Argument('filmID', new Literal(2, new Location(1, 22)), new Location(1, 14)),
                    ], [
                        new LeafField('title', null, [], [], new Location(1, 27)),
                    ], [], new Location(1, 3))
                ], new Location(1, 1)),
            ]
        );
    }

    public function testRequestedOperation()
    {
        $parser = $this->getParser();
        $document = $parser->parse('
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
        ');
        $context = new Context('Two');
        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertEquals(
            $executableDocument->getRequestedOperations(),
            [
                new QueryOperation('Two', [], [], [
                    new RelationalField('post', null, [
                        new Argument('id', new Literal(2, new Location(9, 26)), new Location(9, 22)),
                    ], [
                        new LeafField('title', null, [], [], new Location(10, 21)),
                    ], [], new Location(9, 17))
                ], new Location(8, 19)),
            ]
        );
    }
}
