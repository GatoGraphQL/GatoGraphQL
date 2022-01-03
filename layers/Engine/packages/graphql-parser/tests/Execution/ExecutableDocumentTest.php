<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Execution;

use PoP\Root\Testing\PHPUnit\AbstractIntegrationTestCase;
use PoPBackbone\GraphQLParser\Execution\Context;
use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Literal;
use PoPBackbone\GraphQLParser\Parser\Ast\LeafField;
use PoPBackbone\GraphQLParser\Parser\Ast\QueryOperation;
use PoPBackbone\GraphQLParser\Parser\Ast\RelationalField;
use PoPBackbone\GraphQLParser\Parser\Location;
use PoPBackbone\GraphQLParser\Parser\Parser;

class ExecutableDocumentTest extends AbstractIntegrationTestCase
{
    /**
     * Commented test, since it produces error:
     *
     * > Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException: You have requested a non-existent service "PoP\GraphQLParser\Query\QueryAugmenterServiceInterface".
     *
     * Fix when solving the corresponding issue:
     *
     * @see https://github.com/leoloso/PoP/issues/464
     *
     * Then, must fix the assertion: all Location(...) were copy/pasted, their col/row must be adapted
     */
    public function testMultipleQueryExecution(): void
    {
        $parser = new Parser();
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

            query __ALL {
              id
            }
        ');
        $context = new Context('__ALL');
        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertEquals(
            $executableDocument->getRequestedOperations(),
            [
                new QueryOperation('One', [], [], [
                    new RelationalField('film', null, [
                        new Argument('id', new Literal(1, new Location(9, 26)), new Location(9, 22)),
                    ], [
                        new LeafField('title', null, [], [], new Location(10, 21)),
                    ], [], new Location(9, 17))
                ], new Location(8, 19)),
                new QueryOperation('Two', [], [], [
                    new RelationalField('post', null, [
                        new Argument('id', new Literal(2, new Location(9, 26)), new Location(9, 22)),
                    ], [
                        new LeafField('title', null, [], [], new Location(10, 21)),
                    ], [], new Location(9, 17))
                ], new Location(8, 19)),
                new QueryOperation('__ALL', [], [], [
                  new LeafField('id', null, [], [], new Location(10, 21))
                ], new Location(8, 19)),
            ]
        );
    }
}
