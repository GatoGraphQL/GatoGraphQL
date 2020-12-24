<?php
/*
* This file is a part of GraphQL project.
*
* @author Alexandr Viniychuk <a@viniychuk.com>
* created: 5/15/16 3:51 PM
*/

namespace GraphQLByPoP\GraphQLParser\Parser;


use GraphQLByPoP\GraphQLParser\Execution\Request;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Fragment;
use GraphQLByPoP\GraphQLParser\Parser\Location;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{

    public function testMethods()
    {
        $fragment1     = new Fragment('fragmentName1', 'test', [], [], new Location(1, 1));
        $fragment2     = new Fragment('fragmentName2', 'test', [], [], new Location(1, 1));
        $queriesData   = ['query1', 'query2'];
        $mutationsData = ['mutation1', 'mutation2'];
        $fragmentsData = [$fragment1];
        $variables     = [
            'page' => 2
        ];

        $request = new Request([
            'queries'   => $queriesData,
            'mutations' => $mutationsData,
            'fragments' => $fragmentsData,
        ]);
        $request->setVariables($variables);

        $this->assertEquals($queriesData, $request->getQueries());
        $this->assertEquals($mutationsData, $request->getMutations());
        $this->assertEquals($fragmentsData, $request->getFragments());
        $this->assertEquals($variables, $request->getVariables());

        $this->assertTrue($request->hasFragments());
        $this->assertTrue($request->hasMutations());
        $this->assertTrue($request->hasQueries());

        $this->assertTrue($request->hasVariable('page'));
        $this->assertEquals(2, $request->getVariable('page'));

        $request->addFragment($fragment2);
        $this->assertEquals($fragment2, $request->getFragment('fragmentName2'));
        $this->assertNull($request->getFragment('unknown fragment'));
    }

    public function testSetVariableParseJson()
    {
        $variables = '{"foo": "bar"}';
        $expectedVariableArray = [ 'foo' => 'bar' ];

        $request = new Request([], $variables);
        $this->assertEquals($expectedVariableArray, $request->getVariables());

        $request = new Request();
        $request->setVariables($variables);
        $this->assertEquals($expectedVariableArray, $request->getVariables());
    }
}
