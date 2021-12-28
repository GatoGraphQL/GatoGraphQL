<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser;

use PHPUnit\Framework\TestCase;
use PoPBackbone\GraphQLParser\Execution\Context;
use PoPBackbone\GraphQLParser\Execution\ExecutableDocument;
use PoPBackbone\GraphQLParser\Parser\Ast\Document;
use PoPBackbone\GraphQLParser\Parser\Ast\Fragment;
use PoPBackbone\GraphQLParser\Parser\Ast\MutationOperation;
use PoPBackbone\GraphQLParser\Parser\Ast\QueryOperation;

class RequestTest extends TestCase
{
    public function testMethods()
    {
        $fragment1     = new Fragment('fragmentName1', 'test', [], [], new Location(1, 1));
        $fragment2     = new Fragment('fragmentName2', 'test', [], [], new Location(1, 1));
        $operationsData   = [
            new QueryOperation('query1', [], [], [], new Location(1, 1)),
            new QueryOperation('query2', [], [], [], new Location(1, 1)),
            new MutationOperation('mutation1', [], [], [], new Location(1, 1)),
            new MutationOperation('mutation1', [], [], [], new Location(1, 1)),
        ];
        $fragmentsData = [$fragment1, $fragment2];
        $variableValues     = [
            'page' => 2
        ];

        $executableDocument = new ExecutableDocument(
            new Document($operationsData, $fragmentsData),
            new Context(null, $variableValues)
        );

        $this->assertEquals($operationsData, $executableDocument->getDocument()->getOperations());
        $this->assertEquals($fragmentsData, $executableDocument->getDocument()->getFragments());
        $this->assertEquals($variableValues, $executableDocument->getContext()->getVariableValues());

        $this->assertTrue($executableDocument->getContext()->hasVariableValue('page'));
        $this->assertEquals(2, $executableDocument->getContext()->getVariableValue('page'));

        $this->assertEquals($fragment2, $executableDocument->getDocument()->getFragment('fragmentName2'));
        $this->assertNull($executableDocument->getDocument()->getFragment('unknown fragment'));
    }
}
