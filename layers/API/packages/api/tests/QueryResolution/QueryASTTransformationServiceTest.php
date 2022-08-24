<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\AbstractTestCase;

class QueryASTTransformationServiceTest extends AbstractTestCase
{
    protected function getQueryASTTransformationService(): QueryASTTransformationServiceInterface
    {
        /** @var QueryASTTransformationServiceInterface */
        return $this->getService(QueryASTTransformationServiceInterface::class);
    }

    /**
     * This is the AST for this GraphQL query (it's a not valid one!):
     *
     *   ```
     *   {}
     *   ```
     */
    public function testEmptyOperationMaximumFieldDepth(): void
    {
        $operation = new QueryOperation(
            '',
            [],
            [],
            [
            ],
            new Location(2, 15)
        );
        $operationMaximumFieldDepth = $this->getQueryASTTransformationService()->getOperationMaximumFieldDepth($operation, []);
        $this->assertEquals(
            0,
            $operationMaximumFieldDepth
        );
    }

    /**
     * This is the AST for this GraphQL query:
     *
     *   ```
     *   query {
     *     id # level 1
     *   }
     *   ```
     */
    public function testOperationWithLeafFieldMaximumFieldDepth(): void
    {
        $leafField = new LeafField('id', null, [], [], new Location(3, 17));
        $operation = new QueryOperation(
            '',
            [],
            [],
            [
                $leafField,
            ],
            new Location(2, 15)
        );
        $operationMaximumFieldDepth = $this->getQueryASTTransformationService()->getOperationMaximumFieldDepth($operation, []);
        $this->assertEquals(
            1,
            $operationMaximumFieldDepth
        );
    }

    /**
     * This is the AST for this GraphQL query:
     *
     *   ```
     *   query {
     *     film(id: 1) { # level 1
     *       title # level 2
     *       director { # level 2
     *         name # level 3 # <= maximum depth!
     *       }
     *     }
     *     post(id: 2) { # level 1
     *       title # level 2
     *     }
     *   }
     *   ```
     */
    public function testOperationWithRelationalFieldMaximumFieldDepth(): void
    {
        $leafField2 = new LeafField('name', null, [], [], new Location(6, 23));
        $relationalField2 = new RelationalField(
            'director',
            null,
            [],
            [
                $leafField2,
            ],
            [],
            new Location(5, 21)
        );
        $leafField1 = new LeafField('title', null, [], [], new Location(4, 21));
        $argument1 = new Argument('id', new Literal(1, new Location(3, 26)), new Location(3, 22));
        $relationalField1 = new RelationalField(
            'film',
            null,
            [
                $argument1,
            ],
            [
                $leafField1,
                $relationalField2,
            ],
            [],
            new Location(3, 17)
        );
        $argument2 = new Argument('id', new Literal(2, new Location(9, 26)), new Location(9, 22));
        $leafField3 = new LeafField('title', null, [], [], new Location(10, 21));
        $relationalField3 = new RelationalField(
            'post',
            null,
            [
                $argument2,
            ],
            [
                $leafField3,
            ],
            [],
            new Location(9, 17)
        );
        $operation = new QueryOperation(
            '',
            [],
            [],
            [
                $relationalField1,
                $relationalField3,
            ],
            new Location(2, 15)
        );
        $operationMaximumFieldDepth = $this->getQueryASTTransformationService()->getOperationMaximumFieldDepth($operation, []);
        $this->assertEquals(
            3,
            $operationMaximumFieldDepth
        );
    }

    /**
     * The Fragment Reference is the winning one
     *
     *   ```
     *   query {
     *     id # level 1
     *     ...RootData # level 1
     *   }
     *
     *   fragment RootData on QueryRoot {
     *     id # + 1
     *     self { # + 1
     *       id # + 2
     *     }
     *   }
     *   ```
     */
    public function testOperationWithFragmentMaximumFieldDepth(): void
    {
        $leafField = new LeafField('id', null, [], [], new Location(3, 17));
        $fragmentReference = new FragmentReference('RootData', new Location(4, 17));
        $operation = new QueryOperation(
            '',
            [],
            [],
            [
                $leafField,
                $fragmentReference,
            ],
            new Location(2, 15)
        );

        $fragmentFields = [
            new LeafField('id', null, [], [], new Location(8, 17)),
            new RelationalField('id', null, [], [
                new LeafField('id', null, [], [], new Location(10, 19))
            ], [], new Location(9, 17)),
        ];
        $fragment = new Fragment('RootData', 'QueryRoot', [], $fragmentFields, new Location(15, 15));
        $fragments = [
            $fragment,
        ];

        $operationMaximumFieldDepth = $this->getQueryASTTransformationService()->getOperationMaximumFieldDepth($operation, $fragments);
        $this->assertEquals(
            3,
            $operationMaximumFieldDepth
        );
    }

    /**
     * The Fragment Reference is the winning one
     *
     *   ```
     *   query {
     *     id # level 1
     *     ...RootData # level 1
     *     self { # level 1
     *       ...RootData # level 2
     *     }
     *   }
     *
     *   fragment RootData on QueryRoot {
     *     id # + 1
     *     self { # + 1
     *       id # + 2
     *     }
     *   }
     *   ```
     */
    public function testOperationWithNestedFragmentMaximumFieldDepth(): void
    {
        $leafField = new LeafField('id', null, [], [], new Location(3, 17));
        $fragmentReference2 = new FragmentReference('RootData', new Location(6, 19));
        $relationalField = new RelationalField(
            'self',
            null,
            [],
            [
                $fragmentReference2,
            ],
            [],
            new Location(5, 17)
        );
        $fragmentReference1 = new FragmentReference('RootData', new Location(4, 17));
        $operation = new QueryOperation(
            '',
            [],
            [],
            [
                $leafField,
                $fragmentReference1,
                $relationalField,
            ],
            new Location(2, 15)
        );

        $fragmentFields = [
            new LeafField('id', null, [], [], new Location(10, 17)),
            new RelationalField('id', null, [], [
                new LeafField('id', null, [], [], new Location(12, 19))
            ], [], new Location(11, 17)),
        ];
        $fragment = new Fragment('RootData', 'QueryRoot', [], $fragmentFields, new Location(17, 15));
        $fragments = [
            $fragment,
        ];

        $operationMaximumFieldDepth = $this->getQueryASTTransformationService()->getOperationMaximumFieldDepth($operation, $fragments);
        $this->assertEquals(
            4,
            $operationMaximumFieldDepth
        );
    }

    /**
     * The Fragment Reference is the winning one
     *
     *   ```
     *   query {
     *     id # level 1
     *     self { # level 1
     *       ...on QueryRoot { # level 2
     *         id # level 3
     *       }
     *     }
     *   }
     *   ```
     */
    public function testOperationWithNestedInlineFragmentMaximumFieldDepth(): void
    {
        $leafField1 = new LeafField('id', null, [], [], new Location(3, 17));
        $leafField2 = new LeafField('id', null, [], [], new Location(6, 21));
        $inlineFragment = new InlineFragment(
            'QueryRoot',
            [
                $leafField2
            ],
            [],
            new Location(5, 19)
        );
        $relationalField = new RelationalField(
            'self',
            null,
            [],
            [
                $inlineFragment,
            ],
            [],
            new Location(5, 17)
        );
        $operation = new QueryOperation(
            '',
            [],
            [],
            [
                $leafField1,
                $relationalField,
            ],
            new Location(2, 15)
        );

        $operationMaximumFieldDepth = $this->getQueryASTTransformationService()->getOperationMaximumFieldDepth($operation, []);
        $this->assertEquals(
            3,
            $operationMaximumFieldDepth
        );
    }
}
