<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser;

use PoPBackbone\GraphQLParser\Execution\Request;
use PoPBackbone\GraphQLParser\Parser\Ast\Fragment;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{

    public function testMethods()
    {
        $fragment1     = new Fragment('fragmentName1', 'test', [], [], new Location(1, 1));
        $fragment2     = new Fragment('fragmentName2', 'test', [], [], new Location(1, 1));
        $operationsData   = ['query1', 'query2', 'mutation1', 'mutation2'];
        $fragmentsData = [$fragment1];
        $variableValues     = [
            'page' => 2
        ];

        $request = new Request();
        $request->process([
            'operations' => $operationsData,
            'fragments' => $fragmentsData,
        ]);
        $request->setVariableValues($variableValues);

        $this->assertEquals($operationsData, $request->getOperations());
        $this->assertEquals($fragmentsData, $request->getFragments());
        $this->assertEquals($variableValues, $request->getVariableValues());

        $this->assertTrue($request->hasFragments());
        $this->assertTrue($request->hasOperations());

        $this->assertTrue($request->hasVariable('page'));
        $this->assertEquals(2, $request->getVariableValue('page'));

        $request->addFragment($fragment2);
        $this->assertEquals($fragment2, $request->getFragment('fragmentName2'));
        $this->assertNull($request->getFragment('unknown fragment'));
    }
}
