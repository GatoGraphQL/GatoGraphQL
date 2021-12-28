<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Execution;

use PHPUnit\Framework\TestCase;
use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Literal;
use PoPBackbone\GraphQLParser\Parser\Ast\LeafField;
use PoPBackbone\GraphQLParser\Parser\Ast\QueryOperation;
use PoPBackbone\GraphQLParser\Parser\Ast\RelationalField;
use PoPBackbone\GraphQLParser\Parser\Location;
use PoPBackbone\GraphQLParser\Parser\Parser;

class ExecutableDocumentTest extends TestCase
{
    public function testGetVariableFromContext()
    {
        $parser = new Parser();

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
        $parser = new Parser();
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
        $parser = new Parser();
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
        $parser = new Parser();
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
        $parser = new Parser();
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
        $parser = new Parser();
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
        $parser = new Parser();
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
        $parser = new Parser();
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
        $parser = new Parser();
        $document = $parser->parse('{ id }');
        $context = new Context();
        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->getRequestedOperations();
    }

    public function testRequestedOperationsMatchOperations()
    {
        $parser = new Parser();
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
}
