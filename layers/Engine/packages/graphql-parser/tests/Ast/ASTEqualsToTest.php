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

class ASTEqualsToTest extends AbstractTestCase
{
    /**
     * @dataProvider equalsToLeafFields
     */
    public function testEqualsToLeafFields(
        LeafField $leafField1,
        LeafField $leafField2
    ): void {
        $this->assertTrue(
            $leafField1->equalsTo($leafField2)
        );
    }

    /**
     * @return mixed[]
     */
    public function equalsToLeafFields(): array
    {
        return [
            [
                new LeafField(
                    'someField',
                    null,
                    [],
                    [],
                    new Location(1, 1)
                ),
                new LeafField(
                    'someField',
                    null,
                    [],
                    [],
                    new Location(2, 2)
                )
            ],
        ];
    }
}
