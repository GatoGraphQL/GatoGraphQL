<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\AbstractTestCase;
use stdClass;

class FieldEqualsToTest extends AbstractTestCase
{
    /**
     * @dataProvider getFieldEqualsToFieldProviderEntries
     */
    public function testFieldEqualsToField(
        FieldInterface $field1,
        FieldInterface $field2
    ): void {
        $this->assertTrue($field1->isEquivalentTo($field2));
    }

    /**
     * @return mixed[]
     */
    protected function getFieldEqualsToFieldProviderEntries(): array
    {
        $inputObject1 = new stdClass();
        $inputObject1->someKey = new VariableReference('someVariable', null, new Location(1, 1));
        $inputObject2 = new stdClass();
        $inputObject2->someKey = new VariableReference('someVariable', null, new Location(2, 2));
        $inputObject3 = new stdClass();
        $inputObject3->literal = new Literal('someValue', new Location(1, 1));
        $inputObject3->enum = new Enum('someValue', new Location(1, 1));
        $inputObject3->variableReference = new VariableReference('someVariable', null, new Location(1, 1));
        $inputObject3->inputList = new InputList([new Literal('someValue', new Location(1, 1)), new Enum('someValue', new Location(1, 1)), new VariableReference('someVariable', null, new Location(1, 1))], new Location(1, 1));
        $inputObject3->inputObject = new InputObject($inputObject1, new Location(1, 1));
        $inputObject4 = new stdClass();
        $inputObject4->literal = new Literal('someValue', new Location(2, 2));
        $inputObject4->enum = new Enum('someValue', new Location(2, 2));
        $inputObject4->variableReference = new VariableReference('someVariable', null, new Location(2, 2));
        $inputObject4->inputList = new InputList([new Literal('someValue', new Location(2, 2)), new Enum('someValue', new Location(2, 2)), new VariableReference('someVariable', null, new Location(2, 2))], new Location(2, 2));
        $inputObject4->inputObject = new InputObject($inputObject2, new Location(2, 2));
        return [
            'name' => [
                new LeafField('someField', null, [], [], new Location(1, 1)),
                new LeafField('someField', null, [], [], new Location(2, 2)),
            ],
            'alias' => [
                new LeafField('someField', 'someAlias', [], [], new Location(1, 1)),
                new LeafField('someField', 'someAlias', [], [], new Location(2, 2)),
            ],
            'same-alias-as-name' => [
                new LeafField('someField', null, [], [], new Location(1, 1)),
                new LeafField('someField', 'someField', [], [], new Location(2, 2)),
            ],
            'same-alias-as-name-2' => [
                new LeafField('someField', 'someField', [], [], new Location(1, 1)),
                new LeafField('someField', null, [], [], new Location(2, 2)),
            ],
            'with-args' => [
                new LeafField('someField', null, [new Argument('someArg',new Literal('someValue', new Location(1, 1)), new Location(1, 1)), new Argument('anotherArg', new VariableReference('someVariable', null, new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg',new Literal('someValue', new Location(2, 2)), new Location(2, 2)), new Argument('anotherArg', new VariableReference('someVariable', null, new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'with-literal-args' => [
                new LeafField('someField', null, [new Argument('someArg',new Literal('someValue', new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg',new Literal('someValue', new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'with-enum-args' => [
                new LeafField('someField', null, [new Argument('someArg',new Enum('someValue', new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg',new Enum('someValue', new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'with-variable-reference-args' => [
                new LeafField('someField', null, [new Argument('someArg', new VariableReference('someVariable', null, new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new VariableReference('someVariable', null, new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'with-input-list-args' => [
                new LeafField('someField', null, [new Argument('someArg', new InputList([new Literal('someValue', new Location(1, 1)), new Enum('someValue', new Location(1, 1)), new VariableReference('someVariable', null, new Location(1, 1)), new InputList([new Literal('someValue', new Location(1, 1)), new Enum('someValue', new Location(1, 1)), new VariableReference('someVariable', null, new Location(1, 1)), new InputObject($inputObject3, new Location(1, 1))], new Location(1, 1))], new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new InputList([new Literal('someValue', new Location(2, 2)), new Enum('someValue', new Location(2, 2)), new VariableReference('someVariable', null, new Location(2, 2)), new InputList([new Literal('someValue', new Location(2, 2)), new Enum('someValue', new Location(2, 2)), new VariableReference('someVariable', null, new Location(2, 2)), new InputObject($inputObject4, new Location(2, 2))], new Location(2, 2))], new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'with-input-object-args' => [
                new LeafField('someField', null, [new Argument('someArg', new InputObject($inputObject3, new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new InputObject($inputObject4, new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'unordered-args' => [
                new LeafField('someField', null, [new Argument('someArg', new Literal('someValue', new Location(1, 1)), new Location(1, 1)), new Argument('anotherArg', new InputObject($inputObject1, new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('anotherArg', new InputObject($inputObject2, new Location(2, 2)), new Location(2, 2)), new Argument('someArg', new Literal('someValue', new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'with-directives' => [
                new LeafField('someField', null, [], [new Directive('someDirective', [], new Location(1, 1))], new Location(1, 1)),
                new LeafField('someField', null, [], [new Directive('someDirective', [], new Location(2, 2))], new Location(2, 2)),
            ],
            'with-more-directives' => [
                new LeafField('someField', null, [], [new Directive('someDirective', [], new Location(1, 1)), new Directive('anotherDirective', [], new Location(1, 1))], new Location(1, 1)),
                new LeafField('someField', null, [], [new Directive('someDirective', [], new Location(2, 2)), new Directive('anotherDirective', [], new Location(2, 2))], new Location(2, 2)),
            ],
            'with-directives-with-args' => [
                new LeafField('someField', null, [], [new Directive('someDirective', [new Argument('someArg', new Enum('someEnum', new Location(1, 1)), new Location(1, 1))], new Location(1, 1))], new Location(1, 1)),
                new LeafField('someField', null, [], [new Directive('someDirective', [new Argument('someArg', new Enum('someEnum', new Location(2, 2)), new Location(2, 2))], new Location(2, 2))], new Location(2, 2)),
            ],
            'relational' => [
                new RelationalField('someRelationalField', null, [], [new LeafField('someLeafField', null, [], [], new Location(1, 1))], [], new Location(1, 1)),
                new RelationalField('someRelationalField', null, [], [new LeafField('someLeafField', null, [], [], new Location(2, 2))], [], new Location(2, 2)),
            ],
            'relational-with-args' => [
                new RelationalField('someRelationalField', null, [new Argument('someArg',new Literal('someValue', new Location(1, 1)), new Location(1, 1)), new Argument('anotherArg', new VariableReference('someVariable', null, new Location(1, 1)), new Location(1, 1))], [new LeafField('someLeafField', null, [], [], new Location(1, 1))], [], new Location(1, 1)),
                new RelationalField('someRelationalField', null, [new Argument('someArg',new Literal('someValue', new Location(2, 2)), new Location(2, 2)), new Argument('anotherArg', new VariableReference('someVariable', null, new Location(2, 2)), new Location(2, 2))], [new LeafField('someLeafField', null, [], [], new Location(2, 2))], [], new Location(2, 2)),
            ],
        ];
    }

    /**
     * @dataProvider getFieldDoesNotEqualToFieldProviderEntries
     */
    public function testFieldDoesNotEqualToField(
        FieldInterface $field1,
        FieldInterface $field2
    ): void {
        $this->assertFalse($field1->isEquivalentTo($field2));
    }

    /**
     * @return mixed[]
     */
    protected function getFieldDoesNotEqualToFieldProviderEntries(): array
    {
        $inputObject1 = new stdClass();
        $inputObject1->someLiteral = new Literal('someValue', new Location(1, 1));
        $inputObject2 = new stdClass();
        $inputObject2->anotherLiteral = new Literal('someValue', new Location(2, 2));
        $inputObject3 = new stdClass();
        $inputObject3->literal = new Literal('someValue', new Location(1, 1));
        $inputObject4 = new stdClass();
        $inputObject4->literal = new Literal('anotherValue', new Location(2, 2));
        return [
            'name' => [
                new LeafField('someField', null, [], [], new Location(1, 1)),
                new LeafField('anotherField', null, [], [], new Location(2, 2)),
            ],
            'alias' => [
                new LeafField('someField', 'someAlias', [], [], new Location(1, 1)),
                new LeafField('someField', 'anotherAlias', [], [], new Location(2, 2)),
            ],
            'same-alias-as-name-but-different' => [
                new LeafField('someField', 'someField', [], [], new Location(1, 1)),
                new LeafField('someField', 'anotherAlias', [], [], new Location(2, 2)),
            ],
            'same-alias-as-name-but-different-2' => [
                new LeafField('someField', 'anotherAlias', [], [], new Location(1, 1)),
                new LeafField('someField', 'someField', [], [], new Location(2, 2)),
            ],
            'args-and-no-args' => [
                new LeafField('someField', null, [new Argument('someArg', new Literal('someValue', new Location(1, 1)), new Location(1, 1)), new Argument('anotherArg', new VariableReference('someVariable', null, new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [], [], new Location(2, 2)),
            ],
            'different-args' => [
                new LeafField('someField', null, [new Argument('someArg', new Literal('someValue', new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('anotherArg', new VariableReference('someVariable', null, new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'different-arg-count' => [
                new LeafField('someField', null, [new Argument('someArg', new Literal('someValue', new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new Literal('someValue', new Location(1, 1)), new Location(1, 1)), new Argument('someArg', new Literal('someValue', new Location(1, 1)), new Location(1, 1))], [], new Location(2, 2)),
            ],
            'args-with-different-literal-values' => [
                new LeafField('someField', null, [new Argument('someArg', new Literal('someValue', new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new Literal('anotherValue', new Location(1, 1)), new Location(1, 1))], [], new Location(2, 2)),
            ],
            'args-with-different-enum-values' => [
                new LeafField('someField', null, [new Argument('someArg', new Enum('someValue', new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new Enum('anotherValue', new Location(1, 1)), new Location(1, 1))], [], new Location(2, 2)),
            ],
            'args-with-different-variable-reference-values' => [
                new LeafField('someField', null, [new Argument('someArg', new VariableReference('someVariable', null, new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new VariableReference('anotherVariable', null, new Location(1, 1)), new Location(1, 1))], [], new Location(2, 2)),
            ],
            'args-with-different-input-list-values' => [
                new LeafField('someField', null, [new Argument('someArg', new InputList([new Literal('someValue', new Location(1, 1))], new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new InputList([new Literal('anotherValue', new Location(2, 2))], new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'args-with-different-input-list-values-2' => [
                new LeafField('someField', null, [new Argument('someArg', new InputList([new Literal('someValue', new Location(1, 1))], new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new InputList([new Enum('someValue', new Location(2, 2))], new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'args-with-different-input-list-values-3' => [
                new LeafField('someField', null, [new Argument('someArg', new InputList([new VariableReference('someVariable', null, new Location(1, 1))], new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new InputList([new VariableReference('anotherVariable', null, new Location(2, 2))], new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'args-with-different-input-list-values-4' => [
                new LeafField('someField', null, [new Argument('someArg', new InputList([new Literal('someValue', new Location(1, 1)), new Enum('someValue', new Location(1, 1))], new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new InputList([new Literal('someValue', new Location(2, 2))], new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'args-with-different-input-list-values-5' => [
                new LeafField('someField', null, [new Argument('someArg', new InputList([new Literal('someValue', new Location(1, 1)), new Enum('someValue', new Location(1, 1))], new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new InputList([new Enum('someValue', new Location(1, 1)), new Literal('someValue', new Location(2, 2))], new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'args-with-different-input-list-values-6' => [
                new LeafField('someField', null, [new Argument('someArg', new InputList([new InputList([new Literal('someValue', new Location(1, 1))], new Location(1, 1))], new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new InputList([new InputList([new Literal('anotherValue', new Location(2, 2))], new Location(2, 2))], new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'args-with-different-input-list-values-7' => [
                new LeafField('someField', null, [new Argument('someArg', new InputList([new InputList([new InputObject($inputObject3, new Location(1, 1))], new Location(1, 1))], new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new InputList([new InputList([new InputObject($inputObject4, new Location(1, 1))], new Location(2, 2))], new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'args-with-different-input-object-values' => [
                new LeafField('someField', null, [new Argument('someArg', new InputObject($inputObject1, new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new InputObject($inputObject2, new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'args-with-different-input-object-values-2' => [
                new LeafField('someField', null, [new Argument('someArg', new InputObject($inputObject3, new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new InputObject($inputObject4, new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'unordered-directives' => [
                new LeafField('someField', null, [], [new Directive('someDirective', [], new Location(1, 1)), new Directive('anotherDirective', [], new Location(1, 1))], new Location(1, 1)),
                new LeafField('someField', null, [], [new Directive('anotherDirective', [], new Location(2, 2)), new Directive('someDirective', [], new Location(2, 2))], new Location(2, 2)),
            ],
            'different-directive-count' => [
                new LeafField('someField', null, [], [new Directive('someDirective', [], new Location(1, 1))], new Location(1, 1)),
                new LeafField('someField', null, [], [new Directive('someDirective', [], new Location(2, 2)), new Directive('someDirective', [], new Location(2, 2))], new Location(2, 2)),
            ],
            'different-args-in-directives' => [
                new LeafField('someField', null, [], [new Directive('someDirective', [new Argument('someArg', new Enum('someEnum', new Location(2, 2)), new Location(2, 2))], new Location(1, 1))], new Location(1, 1)),
                new LeafField('someField', null, [], [new Directive('someDirective', [new Argument('someArg', new Enum('anotherEnum', new Location(2, 2)), new Location(2, 2))], new Location(2, 2))], new Location(2, 2)),
            ],
            'relational-with-different-args' => [
                new RelationalField('someRelationalField', null, [new Argument('someArg',new Literal('someValue', new Location(1, 1)), new Location(1, 1))], [new LeafField('someLeafField', null, [], [], new Location(1, 1))], [], new Location(1, 1)),
                new RelationalField('someRelationalField', null, [new Argument('someArg',new Literal('anotherValue', new Location(2, 2)), new Location(2, 2))], [new LeafField('someLeafField', null, [], [], new Location(2, 2))], [], new Location(2, 2)),
            ],
        ];
    }


    /**
     * @dataProvider getEqualsToRelationalFields
     */
    public function testEqualsToRelationalFields(
        RelationalField $relationalField1,
        RelationalField $relationalField2
    ): void {
        $this->assertTrue($relationalField1->isEquivalentTo($relationalField2));
    }

    /**
     * @return mixed[]
     */
    protected function getEqualsToRelationalFields(): array
    {
        return [
            [
                new RelationalField('someField', null, [], [], [], new Location(1, 1)),
                new RelationalField('someField', null, [], [], [], new Location(2, 2)),
            ],
        ];
    }
    
    /**
     * @dataProvider getDoesNotEqualToRelationalFields
     */
    public function testDoesNotEqualToRelationalFields(
        RelationalField $relationalField1,
        RelationalField $relationalField2
    ): void {
        $this->assertFalse($relationalField1->isEquivalentTo($relationalField2));
    }

    /**
     * @return mixed[]
     */
    protected function getDoesNotEqualToRelationalFields(): array
    {
        return [
            [
                new RelationalField('someField', null, [], [], [], new Location(1, 1)),
                new RelationalField('anotherField', null, [], [], [], new Location(2, 2)),
            ],
        ];
    }
}
