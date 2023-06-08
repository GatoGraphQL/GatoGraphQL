<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Unit\Faker\Upstream\ComponentModel\ExtendedSpec\Execution;

use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution\ExecutableDocumentTest as UpstreamExecutableDocumentTest;
use PoP\GraphQLParser\Exception\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocumentInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Module\ModuleInterface;

class ExecutableDocumentTest extends UpstreamExecutableDocumentTest
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    protected static function getModuleClassesToInitialize(): array
    {
        return [
            ...parent::getModuleClassesToInitialize(),
            ...[
                \PoPWPSchema\Posts\Module::class,
            ]
        ];
    }

    protected function createExecutableDocument(
        Document $document,
        Context $context,
    ): ExecutableDocumentInterface {
        return new ExecutableDocument($document, $context);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('getExistingTypeOrInterfaceQueries')]
    public function testExistingTypeFragmentSpread(string $query): void
    {
        $document = $this->getParser()->parse($query);
        $context = new Context();
        $executableDocument = $this->createExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertTrue(true);
    }

    /**
     * @return mixed[]
     */
    public static function getExistingTypeOrInterfaceQueries(): array
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
                
                fragment PostData on CustomPost {
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
                        ...on CustomPost {
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

    #[\PHPUnit\Framework\Attributes\DataProvider('getNonExistingTypeOrInterfaceQueries')]
    public function testNonExistingTypeFragmentSpread(string $query): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_5_5_1_2, ['ThisTypeDoesNotExist']))->getMessage());
        $document = $this->getParser()->parse($query);
        $context = new Context();
        $executableDocument = $this->createExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertTrue(true);
    }

    /**
     * @return mixed[]
     */
    public static function getNonExistingTypeOrInterfaceQueries(): array
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

    #[\PHPUnit\Framework\Attributes\DataProvider('getNonCompositeTypeQueries')]
    public function testNonCompositeTypeFragmentSpread(string $query): void
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

    /**
     * @return mixed[]
     */
    public static function getNonCompositeTypeQueries(): array
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

    #[\PHPUnit\Framework\Attributes\DataProvider('getVariableIsInputTypeQueries')]
    public function testVariableIsInputType(string $query): void
    {
        $document = $this->getParser()->parse($query);
        $context = new Context();
        $executableDocument = $this->createExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertTrue(true);
    }

    /**
     * @return mixed[]
     */
    public static function getVariableIsInputTypeQueries(): array
    {
        return [
            'scalar' => [
                <<<GRAPHQL
                query (\$someVar: String) {
                    echo(value: \$someVar)
                }
                GRAPHQL,
            ],
            'enum' => [
                <<<GRAPHQL
                query (\$someVar: CustomPostStatusEnum) {
                    echo(value: \$someVar)
                }
                GRAPHQL,
            ],
            'inputObject' => [
                <<<GRAPHQL
                query (\$someVar: RootUpdateCustomPostInput) {
                    echo(value: \$someVar)
                }
                GRAPHQL,
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('getVariableIsNotInputTypeQueries')]
    public function testVariableIsNotInputType(string $query, string $variableType): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_5_8_2, ['someVar', $variableType]))->getMessage());
        $document = $this->getParser()->parse($query);
        $context = new Context();
        $executableDocument = $this->createExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertTrue(true);
    }

    /**
     * @return mixed[]
     */
    public static function getVariableIsNotInputTypeQueries(): array
    {
        return [
            'object' => [
                <<<GRAPHQL
                query (\$someVar: Post) {
                    echo(value: \$someVar)
                }
                GRAPHQL,
                'Post',
            ],
            'union' => [
                <<<GRAPHQL
                query (\$someVar: CustomPostUnion) {
                    echo(value: \$someVar)
                }
                GRAPHQL,
                'CustomPostUnion',
            ],
            'interface' => [
                <<<GRAPHQL
                query (\$someVar: CustomPost) {
                    echo(value: \$someVar)
                }
                GRAPHQL,
                'CustomPost',
            ],
        ];
    }
}
