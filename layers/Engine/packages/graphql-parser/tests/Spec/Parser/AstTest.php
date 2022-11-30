<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser;

use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Ast\Variable;
use PoP\Root\AbstractTestCase;
use PoP\Root\Exception\ShouldNotHappenException;

class AstTest extends AbstractTestCase
{
    public function testArgument(): void
    {
        $argument = new Argument('test', new Literal('test', new Location(1, 1)), new Location(1, 1));

        $this->assertNotNull($argument->getValueAST());
        $this->assertEquals($argument->getName(), 'test');
    }

    public function testField(): void
    {
        $field = new LeafField('field', null, [
            new Argument('argument', new Literal('argument value', new Location(1, 1)), new Location(1, 1))
        ], [], new Location(1, 1));

        $this->assertEquals($field->getName(), 'field');
        $this->assertNotEmpty($field->getArguments());
        $this->assertTrue($field->hasArguments());
        // $this->assertEquals(['argument' => 'argument value'], $field->getKeyValueArguments());
    }

    public function testFragment(): void
    {
        $fields = [
            new LeafField('field', null, [], [], new Location(1, 1))
        ];

        $fragment = new Fragment('shipInfo', 'Ship', [], $fields, new Location(1, 1));

        $this->assertEquals('shipInfo', $fragment->getName());
        $this->assertEquals('Ship', $fragment->getModel());
        $this->assertEquals($fields, $fragment->getFieldsOrFragmentBonds());
    }

    public function testFragmentReference(): void
    {
        $reference = new FragmentReference('shipInfo', new Location(1, 1));
        $this->assertEquals('shipInfo', $reference->getName());
    }

    public function testInlineFragment(): void
    {
        $fields = [
            new LeafField('id', null, [], [], new Location(1, 1))
        ];

        $reference = new InlineFragment('Ship', $fields, [], new Location(1, 1));

        $this->assertEquals('Ship', $reference->getTypeName());
        $this->assertEquals($fields, $reference->getFieldsOrFragmentBonds());
    }

    public function testQuery(): void
    {
        $arguments = [
            new Argument('limit', new Literal('10', new Location(1, 1)), new Location(1, 1))
        ];

        $fields = [
            new LeafField('id', null, [], [], new Location(1, 1))
        ];

        $query = new RelationalField('ships', 'lastShips', $arguments, $fields, [], new Location(1, 1));

        $this->assertEquals('ships', $query->getName());
        $this->assertEquals('lastShips', $query->getAlias());
        $this->assertEquals([$arguments[0]], $query->getArguments());
        // $this->assertEquals(['limit' => '10'], $query->getKeyValueArguments());
        $this->assertEquals($fields, $query->getFieldsOrFragmentBonds());
        $this->assertTrue($query->hasArguments());
    }

    public function testArgumentValues(): void
    {
        $list = new InputList(['a', 'b'], new Location(1, 1));
        $this->assertEquals(['a', 'b'], $list->getValue());
        $this->assertEquals(true, $list->hasValue());

        $inputObject = new InputObject((object) ['a', 'b'], new Location(1, 1));
        $this->assertEquals((object) ['a', 'b'], $inputObject->getValue());
        $this->assertEquals(true, $inputObject->hasValue());

        $literal = new Literal('text', new Location(1, 1));
        $this->assertEquals('text', $literal->getValue());
        $this->assertEquals(true, $literal->hasValue());
    }

    public function testVariable(): void
    {
        $variable = new Variable('id', 'int', false, false, true, true, true, [], new Location(1, 1));

        $this->assertEquals('id', $variable->getName());
        $this->assertEquals('int', $variable->getTypeName());
        $this->assertFalse($variable->isRequired());
        $this->assertFalse($variable->isArray());
        $this->assertTrue($variable->isArrayOfArrays());
        $this->assertTrue($variable->isArrayOfArraysElementRequired());

        $variable->setContext(new Context(null, [$variable->getName() => 'text']));
        $this->assertEquals('text', $variable->getValue());
    }

    public function testVariableLogicException(): void
    {
        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage(sprintf(
            'Context has not been set for Variable object (with name \'%s\')',
            'id'
        ));
        $variable = new Variable('id', 'int', false, false, true, false, false, [], new Location(1, 1));
        $variable->getValue();
    }
}
