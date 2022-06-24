<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution\MultiFieldDirectives;

use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Location;

class DisabledMultiFieldDirectiveTest extends AbstractMultiFieldDirectiveTest
{
    protected static function enableMultiFieldDirectives(): bool
    {
        return false;
    }

    public function queryWithMultiFieldDirectiveProvider()
    {
        return [
            [
                <<<GRAPHQL
                    {
                        name
                        description
                            @translate(
                                affectAdditionalFieldsUnderPos: [1]
                            )
                    }
                GRAPHQL,
                new Document(
                    [
                        new QueryOperation(
                            '',
                            [],
                            [],
                            [
                                new LeafField(
                                    'name',
                                    null,
                                    [],
                                    [],
                                    new Location(2, 9)
                                ),
                                new LeafField(
                                    'description',
                                    null,
                                    [],
                                    [
                                        new Directive(
                                            'translate',
                                            [
                                                new Argument(
                                                    'affectAdditionalFieldsUnderPos',
                                                    new InputList(
                                                        [1],
                                                        new Location(5, 49)
                                                    ),
                                                    new Location(5, 17)
                                                ),
                                            ],
                                            new Location(4, 14)
                                        ),
                                    ],
                                    new Location(3, 9)
                                ),
                            ],
                            new Location(1, 5)
                        )
                    ]
                ),
            ],
        ];
    }
}
