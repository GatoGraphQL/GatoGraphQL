<?php

namespace PoP\GraphQLParser\Spec\Parser;

use LogicException;
use PoP\GraphQLParser\FeedbackMessage\FeedbackMessageProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Variable;
use PoP\Root\AbstractTestCase;

class VariableTest extends AbstractTestCase
{
    protected function getFeedbackMessageProvider(): FeedbackMessageProvider
    {
        return $this->getService(FeedbackMessageProvider::class);
    }

    /**
     * Test if variable value equals expected value
     *
     * @dataProvider variableProvider
     */
    public function testGetValue($actual, $expected)
    {
        $var = new Variable('foo', 'bar', false, false, true, new Location(1, 1));
        $var->setContext(new Context(null, ['foo' => $actual]));
        $this->assertEquals($var->getValue()->getValue(), $expected);
    }

    public function testGetNullValueException()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage($this->getFeedbackMessageProvider()->getMessage(FeedbackMessageProvider::E2, 'foo'));
        $var = new Variable('foo', 'bar', false, false, true, new Location(1, 1));
        $var->getValue()->getValue();
    }

    public function testGetValueReturnsDefaultValueIfNoValueSet()
    {
        $var = new Variable('foo', 'bar', false, false, true, new Location(1, 1));
        $var->setDefaultValue(new Literal('default-value', new Location(1, 1)));
        $var->setContext(new Context());

        $this->assertEquals(
            'default-value',
            $var->getValue()->getValue()
        );
    }

    public function testGetValueReturnsSetValueEvenWithDefaultValue()
    {
        $var = new Variable('foo', 'bar', false, false, true, new Location(1, 1));
        $var->setContext(new Context(null, ['foo' => 'real-value']));
        $var->setDefaultValue(new Literal('default-value', new Location(1, 1)));

        $this->assertEquals(
            'real-value',
            $var->getValue()->getValue()
        );
    }

    public function testIndicatesDefaultValuePresent()
    {
        $var = new Variable('foo', 'bar', false, false, true, new Location(1, 1));
        $var->setDefaultValue(new Literal('default-value', new Location(1, 1)));

        $this->assertTrue(
            $var->hasDefaultValue()
        );
    }

    public function testHasNoDefaultValue()
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
