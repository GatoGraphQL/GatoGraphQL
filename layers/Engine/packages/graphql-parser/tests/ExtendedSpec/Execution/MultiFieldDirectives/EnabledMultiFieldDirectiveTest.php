<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution\MultiFieldDirectives;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Location;

class EnabledMultiFieldDirectiveTest extends AbstractMultiFieldDirectiveTest
{
    protected static function enableMultiFieldDirectives(): bool
    {
        return true;
    }

    public function queryWithMultiFieldDirectiveProvider()
    {
        $directive1 = new Directive(
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
        );
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
                                    [
                                        $directive1,
                                    ],
                                    new Location(2, 9)
                                ),
                                new LeafField(
                                    'description',
                                    null,
                                    [],
                                    [
                                        $directive1,
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

    // /**
    //  * @dataProvider failingQueryWithMultiFieldDirectiveProvider
    //  */
    // public function testFailingMultiFieldDirectives(string $query)
    // {
    //     $parser = $this->getParser();
    //     $errorMessages = [
    //         'no-directive-under-pos' => (new FeedbackItemResolution(
    //             GraphQLExtendedSpecErrorFeedbackItemProvider::class,
    //             GraphQLExtendedSpecErrorFeedbackItemProvider::E4,
    //             [
    //                 2,
    //                 'forEach',
    //                 'affectDirectivesUnderPos',
    //             ]
    //         ))->getMessage(),
    //         'no-negative-pos' => (new FeedbackItemResolution(
    //             GraphQLExtendedSpecErrorFeedbackItemProvider::class,
    //             GraphQLExtendedSpecErrorFeedbackItemProvider::E3,
    //             [
    //                 'affectDirectivesUnderPos',
    //                 'forEach',
    //                 -2,
    //             ]
    //         ))->getMessage(),
    //         'no-2nd-directive-under-pos' => (new FeedbackItemResolution(
    //             GraphQLExtendedSpecErrorFeedbackItemProvider::class,
    //             GraphQLExtendedSpecErrorFeedbackItemProvider::E4,
    //             [
    //                 2,
    //                 'forEach',
    //                 'affectDirectivesUnderPos',
    //             ]
    //         ))->getMessage(),
    //         'directive-referenced-only-once' => (new FeedbackItemResolution(
    //             GraphQLExtendedSpecErrorFeedbackItemProvider::class,
    //             GraphQLExtendedSpecErrorFeedbackItemProvider::E1,
    //             [
    //                 'advancePointerInArrayOrObject',
    //             ]
    //         ))->getMessage(),
    //         'no-empty-pos' => (new FeedbackItemResolution(
    //             GraphQLExtendedSpecErrorFeedbackItemProvider::class,
    //             GraphQLExtendedSpecErrorFeedbackItemProvider::E2,
    //             [
    //                 'affectDirectivesUnderPos',
    //                 'forEach',
    //             ]
    //         ))->getMessage(),
    //     ];
    //     $this->expectException(InvalidRequestException::class);
    //     $this->expectErrorMessage($errorMessages[$this->dataName()]);
    //     $parser->parse($query);
    // }

    // public function failingQueryWithMultiFieldDirectiveProvider(): array
    // {
    //     return [
    //         'no-directive-under-pos' => [
    //             <<<GRAPHQL
    //                 query {
    //                     capabilities @forEach(affectDirectivesUnderPos: [2]) @upperCase
    //                 }
    //             GRAPHQL,
    //         ],
    //         'no-negative-pos' => [
    //             <<<GRAPHQL
    //                 query {
    //                     capabilities @forEach(affectDirectivesUnderPos: [-2]) @upperCase
    //                 }
    //             GRAPHQL,
    //         ],
    //         'no-2nd-directive-under-pos' => [
    //             <<<GRAPHQL
    //                 query {
    //                     capabilities @forEach(affectDirectivesUnderPos: [1,2]) @upperCase
    //                 }
    //             GRAPHQL,
    //         ],
    //         'directive-referenced-only-once' => [
    //             <<<GRAPHQL
    //                 query {
    //                     groupCapabilities @forEach(affectDirectivesUnderPos: [1,2]) @advancePointerInArrayOrObject(path: "group") @upperCase @lowerCase
    //                 }
    //             GRAPHQL
    //         ],
    //         'no-empty-pos' => [
    //             <<<GRAPHQL
    //                 query {
    //                     groupCapabilities @forEach(affectDirectivesUnderPos: []) @upperCase
    //                 }
    //             GRAPHQL
    //         ],
    //     ];
    // }
}
