<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Ast;

use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLParserErrorFeedbackItemProvider;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\MutationOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Ast\Variable;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\GraphQLParser\Spec\Parser\ParserInterface;
use PoP\Root\AbstractTestCase;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;
use stdClass;

class FieldEqualsToTest extends AbstractTestCase
{
    /**
     * @dataProvider getEqualsToLeafFields
     */
    public function testEqualsToLeafFields(
        LeafField $leafField1,
        LeafField $leafField2
    ): void {
        $this->assertTrue($leafField1->equalsTo($leafField2));
    }

    /**
     * @return mixed[]
     */
    protected function getEqualsToLeafFields(): array
    {
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
                new LeafField('someField', null, [new Argument('someArg', new Literal('someValue', new Location(1, 1)), new Location(1, 1)), new Argument('anotherArg', new VariableReference('someVariable', null, new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('someArg', new Literal('someValue', new Location(2, 2)), new Location(2, 2)), new Argument('anotherArg', new VariableReference('someVariable', null, new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
            'unordered-args' => [
                new LeafField('someField', null, [new Argument('someArg', new Literal('someValue', new Location(1, 1)), new Location(1, 1)), new Argument('anotherArg', new VariableReference('someVariable', null, new Location(1, 1)), new Location(1, 1))], [], new Location(1, 1)),
                new LeafField('someField', null, [new Argument('anotherArg', new VariableReference('someVariable', null, new Location(2, 2)), new Location(2, 2)), new Argument('someArg', new Literal('someValue', new Location(2, 2)), new Location(2, 2))], [], new Location(2, 2)),
            ],
        ];
    }

    /**
     * @dataProvider getDoesNotEqualToLeafFields
     */
    public function testDoesNotEqualToLeafFields(
        LeafField $leafField1,
        LeafField $leafField2
    ): void {
        $this->assertFalse($leafField1->equalsTo($leafField2));
    }

    /**
     * @return mixed[]
     */
    protected function getDoesNotEqualToLeafFields(): array
    {
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
        ];
    }


    /**
     * @dataProvider getEqualsToRelationalFields
     */
    public function testEqualsToRelationalFields(
        RelationalField $relationalField1,
        RelationalField $relationalField2
    ): void {
        $this->assertTrue($relationalField1->equalsTo($relationalField2));
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
        $this->assertFalse($relationalField1->equalsTo($relationalField2));
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
