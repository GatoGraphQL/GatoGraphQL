<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser;

use PHPUnit\Framework\TestCase;
use PoPBackbone\GraphQLParser\Execution\Context;
use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\InputList;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\InputObject;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Literal;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use PoPBackbone\GraphQLParser\Parser\Ast\Field;
use PoPBackbone\GraphQLParser\Parser\Ast\Fragment;
use PoPBackbone\GraphQLParser\Parser\Ast\FragmentReference;
use PoPBackbone\GraphQLParser\Parser\Ast\RelationalField;
use PoPBackbone\GraphQLParser\Parser\Ast\TypedFragmentReference;

class AstTest extends TestCase
{
    public function testArgument()
    {
        $argument = new Argument('test', new Literal('test', new Location(1, 1)), new Location(1, 1));

        $this->assertNotNull($argument->getValue());
        $this->assertEquals($argument->getName(), 'test');

        $test2Value = new Literal('some value', new Location(1, 1));
        $argument->setName('test2');
        $argument->setValue($test2Value);

        $this->assertEquals($argument->getName(), 'test2');
        $this->assertEquals($argument->getValue()->getValue(), 'some value');
    }

    public function testField()
    {
        $field = new Field('field', null, [], [], new Location(1, 1));

        $this->assertEquals($field->getName(), 'field');
        $this->assertEmpty($field->getArguments());
        $this->assertFalse($field->hasArguments());

        $field->setAlias('alias');
        $field->setName('alias');
        $this->assertEquals($field->getAlias(), 'alias');
        $this->assertEquals($field->getName(), 'alias');

        $field->addArgument(new Argument('argument', new Literal('argument value', new Location(1, 1)), new Location(1, 1)));
        $this->assertTrue($field->hasArguments());
        $this->assertEquals(['argument' => 'argument value'], $field->getKeyValueArguments());
    }

    public function testFragment()
    {
        $fields = [
            new Field('field', null, [], [], new Location(1, 1))
        ];

        $fragment = new Fragment('shipInfo', 'Ship', [], $fields, new Location(1, 1));

        $this->assertEquals('shipInfo', $fragment->getName());
        $this->assertEquals('Ship', $fragment->getModel());
        $this->assertEquals($fields, $fragment->getFieldOrFragmentReferences());

        $fragment->setName('largeShipInfo');
        $this->assertEquals('largeShipInfo', $fragment->getName());

        $fragment->setModel('Boat');
        $this->assertEquals('Boat', $fragment->getModel());

        $newField = [
            new Field('id', null, [], [], new Location(1, 1))
        ];
        $fragment->setFieldOrFragmentReferences($newField);
        $this->assertEquals($newField, $fragment->getFieldOrFragmentReferences());

        // $this->assertFalse($fragment->isUsed());
        // $fragment->setUsed(true);
        // $this->assertTrue($fragment->isUsed());
    }

    public function testFragmentReference()
    {
        $reference = new FragmentReference('shipInfo', new Location(1, 1));

        $this->assertEquals('shipInfo', $reference->getName());

        $reference->setName('largeShipInfo');
        $this->assertEquals('largeShipInfo', $reference->getName());
    }

    public function testTypedFragmentReference()
    {
        $fields = [
            new Field('id', null, [], [], new Location(1, 1))
        ];

        $reference = new TypedFragmentReference('Ship', $fields, [], new Location(1, 1));

        $this->assertEquals('Ship', $reference->getTypeName());
        $this->assertEquals($fields, $reference->getFieldOrFragmentReferences());

        $reference->setTypeName('BigBoat');
        $this->assertEquals('BigBoat', $reference->getTypeName());

        $newFields = [
            new Field('name', null, [], [], new Location(1, 1)),
            new Field('id', null, [], [], new Location(1, 1))
        ];

        $reference->setFieldOrFragmentReferences($newFields);
        $this->assertEquals($newFields, $reference->getFieldOrFragmentReferences());
    }

    public function testQuery()
    {
        $arguments = [
            new Argument('limit', new Literal('10', new Location(1, 1)), new Location(1, 1))
        ];

        $fields = [
            new Field('id', null, [], [], new Location(1, 1))
        ];

        $query = new RelationalField('ships', 'lastShips', $arguments, $fields, [], new Location(1, 1));

        $this->assertEquals('ships', $query->getName());
        $this->assertEquals('lastShips', $query->getAlias());
        $this->assertEquals(['limit' => $arguments[0]], $query->getArguments());
        $this->assertEquals(['limit' => '10'], $query->getKeyValueArguments());
        $this->assertEquals($fields, $query->getFieldOrFragmentReferences());
        $this->assertTrue($query->hasArguments());
        $this->assertTrue($query->hasFieldOrFragmentReferences());

        $query->setFieldOrFragmentReferences([]);
        $query->setArguments([]);

        $this->assertEmpty($query->getArguments());
        $this->assertEmpty($query->getFieldOrFragmentReferences());
        $this->assertEmpty($query->getKeyValueArguments());

        $this->assertFalse($query->hasArguments());
        $this->assertFalse($query->hasFieldOrFragmentReferences());

        $query->addArgument(new Argument('offset', new Literal(10, new Location(1, 1)), new Location(1, 1)));
        $this->assertTrue($query->hasArguments());
    }

    public function testArgumentValues()
    {
        $list = new InputList(['a', 'b'], new Location(1, 1));
        $this->assertEquals(['a', 'b'], $list->getValue());
        $list->setValue(['a']);
        $this->assertEquals(['a'], $list->getValue());

        $inputObject = new InputObject((object) ['a', 'b'], new Location(1, 1));
        $this->assertEquals((object) ['a', 'b'], $inputObject->getValue());
        $inputObject->setValue((object) ['a']);
        $this->assertEquals((object) ['a'], $inputObject->getValue());

        $literal = new Literal('text', new Location(1, 1));
        $this->assertEquals('text', $literal->getValue());
        $literal->setValue('new text');
        $this->assertEquals('new text', $literal->getValue());
    }

    public function testVariable()
    {
        $variable = new Variable('id', 'int', false, false, true, new Location(1, 1));

        $this->assertEquals('id', $variable->getName());
        $this->assertEquals('int', $variable->getTypeName());
        $this->assertFalse($variable->isRequired());
        $this->assertFalse($variable->isArray());

        $variable->setTypeName('string');
        $this->assertEquals('string', $variable->getTypeName());

        $variable->setName('limit');
        $this->assertEquals('limit', $variable->getName());

        $variable->setIsArray(true);
        $variable->setRequired(true);

        $this->assertTrue($variable->isRequired());
        $this->assertTrue($variable->isArray());

        $variable->setContext(new Context(null, [$variable->getName() => 'text']));
        $this->assertEquals(new Literal('text', new Location(1, 1)), $variable->getValue());
    }

    public function testVariableLogicException()
    {
        $this->expectException(\LogicException::class);
        $variable = new Variable('id', 'int', false, false, true, new Location(1, 1));
        $variable->getValue();
    }
}
