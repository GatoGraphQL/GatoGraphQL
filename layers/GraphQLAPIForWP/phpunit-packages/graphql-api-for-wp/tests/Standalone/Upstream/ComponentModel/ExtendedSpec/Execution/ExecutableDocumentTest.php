<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Standalone\Upstream\ComponentModel\ExtendedSpec\Execution;

use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution\ExecutableDocumentTest as UpstreamExecutableDocumentTest;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocumentInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\Root\Feedback\FeedbackItemResolution;

class ExecutableDocumentTest extends UpstreamExecutableDocumentTest
{
    /**
     * @return string[]
     */
    protected static function getComponentClassesToInitialize(): array
    {
        return [
            ...parent::getComponentClassesToInitialize(),
            ...[
                \PoPWPSchema\Posts\Component::class,
            ]
        ];
    }

    protected function createExecutableDocument(
        Document $document,
        Context $context,
    ): ExecutableDocumentInterface {
        return new ExecutableDocument($document, $context);
    }

    /**
     * @dataProvider getExistingTypeOrInterfaceQueries
     */
    public function testExistingTypeFragmentSpread(string $query)
    {
        $document = $this->getParser()->parse($query);
        $context = new Context();
        $executableDocument = $this->createExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertTrue(true);
    }

    public function getExistingTypeOrInterfaceQueries(): array
    {
        return [
            'object-type-in-fragment' => [
                <<<GRAPHQL
                {
                    customPosts {
                        __typename
                        ...PostData
                    }
                }
                
                fragment PostData on Post {
                    id
                    title
                }
                GRAPHQL,
            ],
            'interface-type-in-fragment' => [
                <<<GRAPHQL
                {
                    customPosts {
                        __typename
                        ...PostData
                    }
                }
                
                fragment PostData on IsCustomPost {
                    id
                    title
                }
                GRAPHQL,
            ],
            'union-type-in-fragment' => [
                <<<GRAPHQL
                {
                    customPosts {
                        __typename
                        ...CustomPostData
                    }
                }
                
                fragment CustomPostData on CustomPostUnion {
                    customPostType
                    ...PostData
                }
                
                fragment PostData on Post {
                    id
                    title
                }
                GRAPHQL,
            ],
            'object-type-in-inline-fragment' => [
                <<<GRAPHQL
                {
                    customPosts {
                        __typename
                        ...on Post {
                            id
                            title
                        }
                    }
                }
                GRAPHQL,
            ],
            'interface-type-in-inline-fragment' => [
                <<<GRAPHQL
                {
                    customPosts {
                        __typename
                        ...on IsCustomPost {
                            id
                            title
                        }
                    }
                }
                GRAPHQL,
            ],
            'union-type-in-inline-fragment' => [
                <<<GRAPHQL
                {
                    customPosts {
                        __typename
                        ...on CustomPostUnion {
                            customPostType
                            ...on Post {
                                id
                                title
                            }
                        }
                    }
                }
                GRAPHQL,
            ],
        ];
    }

    /**
     * @dataProvider getNonExistingTypeOrInterfaceQueries
     */
    public function testNonExistingTypeFragmentSpread(string $query)
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_5_5_1_2, ['ThisTypeDoesNotExist']))->getMessage());
        $document = $this->getParser()->parse($query);
        $context = new Context();
        $executableDocument = $this->createExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertTrue(true);
    }

    public function getNonExistingTypeOrInterfaceQueries(): array
    {
        return [
            'fragment' => [
                <<<GRAPHQL
                {
                    customPosts {
                        __typename
                        ...PostData
                    }
                }
                
                fragment PostData on ThisTypeDoesNotExist {
                    id
                    title
                }
                GRAPHQL,
            ],
            'nested-fragment' => [
                <<<GRAPHQL
                {
                    customPosts {
                        __typename
                        ...SomePostData
                    }
                }
                
                fragment SomePostData on Post {
                    id
                    ...OtherPostData
                }
                
                fragment OtherPostData on ThisTypeDoesNotExist {
                    title
                }
                GRAPHQL,
            ],
            'inline-fragment' => [
                <<<GRAPHQL
                {
                    customPosts {
                        __typename
                        ...on ThisTypeDoesNotExist {
                            id
                            title
                        }
                    }
                }
                GRAPHQL,
            ],
            'nested-inline-fragment' => [
                <<<GRAPHQL
                {
                    customPosts {
                        __typename
                        ...on Post {
                            id
                            ...on ThisTypeDoesNotExist {
                                title
                            }
                        }
                    }
                }
                GRAPHQL,
            ],
        ];
    }

    /**
     * @dataProvider getNonCompositeTypeQueries
     */
    public function testNonCompositeTypeFragmentSpread(string $query)
    {
        $types = [
            'scalar' => 'String',
            'enum' => 'CustomPostStatusEnum',
        ];
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_5_5_1_3, [$types[$this->dataName()]]))->getMessage());
        $document = $this->getParser()->parse($query);
        $context = new Context();
        $executableDocument = $this->createExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertTrue(true);
    }

    public function getNonCompositeTypeQueries(): array
    {
        return [
            'scalar' => [
                <<<GRAPHQL
                {
                    customPosts {
                        __typename
                        ...StringData
                    }
                }
                
                fragment StringData on String {
                    id
                    title
                }
                GRAPHQL,
            ],
            'enum' => [
                <<<GRAPHQL
                {
                    customPosts {
                        __typename
                        ...SomePostData
                    }
                }
                
                fragment SomePostData on Post {
                    id
                    ...OtherEnumData
                }
                
                fragment OtherEnumData on CustomPostStatusEnum {
                    title
                }
                GRAPHQL,
            ],
        ];
    }
}
