<?php

namespace PoP\GraphQLParser\Spec\Parser;

use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Variable;
use PoP\Root\AbstractTestCase;
use PoP\Root\Exception\ShouldNotHappenException;

class VariableTest extends AbstractTestCase
{
    /**
     * Test if variable value equals expected value
     *
     * @dataProvider variableProvider
     */
    public function testGetValue($actual, $expected)
    {
        $var = new Variable('foo', 'bar', false, false, true, new Location(1, 1));
        $var->setContext(new Context(null, ['foo' => $actual]));
        $this->assertEquals($var->getValue(), $expected);
    }

    public function testGetNullValueException(): void
    {
        $this->expectException(ShouldNotHappenException::class);
        $this->expectExceptionMessage(sprintf(
            'Context has not been set for Variable object (with name \'%s\')',
            'foo'
        ));
        $var = new Variable('foo', 'bar', false, false, true, new Location(1, 1));
        $var->getValue();
    }

    public function testGetValueReturnsDefaultValueIfNoValueSet(): void
    {
        $var = new Variable('foo', 'bar', false, false, true, new Location(1, 1));
        $var->setDefaultValueAST(new Literal('default-value', new Location(1, 1)));
        $var->setContext(new Context());

        $this->assertEquals(
            'default-value',
            $var->getValue()
        );
    }

    public function testGetValueReturnsSetValueEvenWithDefaultValue(): void
    {
        $var = new Variable('foo', 'bar', false, false, true, new Location(1, 1));
        $var->setContext(new Context(null, ['foo' => 'real-value']));
        $var->setDefaultValueAST(new Literal('default-value', new Location(1, 1)));

        $this->assertEquals(
            'real-value',
            $var->getValue()
        );
    }

    public function testIndicatesDefaultValuePresent(): void
    {
        $var = new Variable('foo', 'bar', false, false, true, new Location(1, 1));
        $var->setDefaultValueAST(new Literal('default-value', new Location(1, 1)));

        $this->assertTrue(
            $var->hasDefaultValue()
        );
    }

    public function testHasNoDefaultValue(): void
    {
        $var = new Variable('foo', 'bar', false, false, true, new Location(1, 1));

        $this->assertFalse(
            $var->hasDefaultValue()
        );
    }

    /**
     * @return array Array of <mixed: value to set, mixed: expected value>
     */
    public static function variableProvider()
    {
        return [
            [
                0,
                0
            ]
        ];
    }
}
