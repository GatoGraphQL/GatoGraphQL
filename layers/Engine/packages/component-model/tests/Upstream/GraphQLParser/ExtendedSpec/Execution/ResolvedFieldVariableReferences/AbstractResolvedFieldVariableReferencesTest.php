<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution\ResolvedFieldVariableReferences;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReference;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\ResolvedFieldVariableReference;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\AbstractTestCase;
use PoP\Root\Feedback\FeedbackItemResolution;

abstract class AbstractResolvedFieldVariableReferencesTest extends AbstractTestCase
{
    /**
     * @return array<string, mixed> [key]: Component class, [value]: Configuration
     */
    protected static function getComponentClassConfiguration(): array
    {
        $componentClassConfiguration = parent::getComponentClassConfiguration();
        $componentClassConfiguration[\PoP\GraphQLParser\Component::class][\PoP\GraphQLParser\Environment::ENABLE_MULTIPLE_QUERY_EXECUTION] = true;
        $componentClassConfiguration[\PoP\GraphQLParser\Component::class][\PoP\GraphQLParser\Environment::ENABLE_DYNAMIC_VARIABLES] = true;
        $componentClassConfiguration[\PoP\GraphQLParser\Component::class][\PoP\GraphQLParser\Environment::ENABLE_RESOLVED_FIELD_VARIABLE_REFERENCES] = static::enabled();
        return $componentClassConfiguration;
    }

    abstract protected static function enabled(): bool;

    protected function getParser(): ParserInterface
    {
        return $this->getService(ParserInterface::class);
    }

    public function testResolvedFieldVariableReferences(): void
    {
        $parser = $this->getParser();
        $query = '
            {
                userList: getJSON(
                    url: "https://someurl.com/rest/users"
                )
            
                userListLang: extract(
                    object: $_userList,
                    path: "lang"
                )
            }
        ';
        $document = $parser->parse($query);
        $context = new Context('');
        $field = new LeafField(
            'getJSON',
            'userList',
            [
                new Argument('url', new Literal('https://someurl.com/rest/users', new Location(4, 27)), new Location(4, 21)),
            ],
            [],
            new Location(3, 27)
        );
        $dynamicVariableReference = static::enabled()
            ? new ResolvedFieldVariableReference('_userList', $field, new Location(8, 29))
            : new DynamicVariableReference('_userList', new Location(8, 29));
        if (!static::enabled()) {
            $dynamicVariableReference->setContext($context);
        }
        $queryOperation = new QueryOperation(
            '',
            [],
            [],
            [
                $field,
                new LeafField(
                    'extract',
                    'userListLang',
                    [
                        new Argument('object', $dynamicVariableReference, new Location(8, 21)),
                        new Argument('path', new Literal('lang', new Location(9, 28)), new Location(9, 21)),
                    ],
                    [],
                    new Location(7, 31)
                )
            ],
            new Location(2, 13)
        );

        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertEquals(
            [
                $queryOperation,
            ],
            $executableDocument->getRequestedOperations()
        );
    }

    public function testInFragments(): void
    {
        $parser = $this->getParser();
        $query = '
            query {
                self {
                    ...RootData

                    userListLang: extract(
                        object: $_userList,
                        path: "lang"
                    )
                }
            }

            fragment RootData on QueryRoot {
                userList: getJSON(
                    url: "https://someurl.com/rest/users"
                )
            }
        ';
        $document = $parser->parse($query);
        $context = new Context('');
        $field = new LeafField(
            'getJSON',
            'userList',
            [
                new Argument('url', new Literal('https://someurl.com/rest/users', new Location(15, 27)), new Location(15, 21)),
            ],
            [],
            new Location(14, 27)
        );
        $dynamicVariableReference = static::enabled()
            ? new ResolvedFieldVariableReference('_userList', $field, new Location(7, 33))
            : new DynamicVariableReference('_userList', new Location(7, 33));
        if (!static::enabled()) {
            $dynamicVariableReference->setContext($context);
        }
        $queryOperation = new QueryOperation(
            '',
            [],
            [],
            [
                new RelationalField(
                    'self',
                    null,
                    [],
                    [
                        new FragmentReference('RootData', new Location(4, 24)),
                        new LeafField(
                            'extract',
                            'userListLang',
                            [
                                new Argument('object', $dynamicVariableReference, new Location(7, 25)),
                                new Argument('path', new Literal('lang', new Location(8, 32)), new Location(8, 25)),
                            ],
                            [],
                            new Location(6, 35)
                        ),
                    ],
                    [],
                    new Location(3, 17)
                )
            ],
            new Location(2, 19)
        );

        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertEquals(
            [
                $queryOperation,
            ],
            $executableDocument->getRequestedOperations()
        );
    }

    public function testInInlineFragments(): void
    {
        $parser = $this->getParser();
        $query = '
            query {
                self {
                    ... on QueryRoot {
                        userList: getJSON(
                            url: "https://someurl.com/rest/users"
                        )
                    }

                    userListLang: extract(
                        object: $_userList,
                        path: "lang"
                    )
                }
            }
        ';
        $document = $parser->parse($query);
        $context = new Context('');
        $field = new LeafField(
            'getJSON',
            'userList',
            [
                new Argument('url', new Literal('https://someurl.com/rest/users', new Location(6, 35)), new Location(6, 29)),
            ],
            [],
            new Location(5, 35)
        );
        $dynamicVariableReference = static::enabled()
            ? new ResolvedFieldVariableReference('_userList', $field, new Location(11, 33))
            : new DynamicVariableReference('_userList', new Location(11, 33));
        if (!static::enabled()) {
            $dynamicVariableReference->setContext($context);
        }
        $queryOperation = new QueryOperation(
            '',
            [],
            [],
            [
                new RelationalField(
                    'self',
                    null,
                    [],
                    [
                        new InlineFragment(
                            'QueryRoot',
                            [
                                $field,
                            ],
                            [],
                            new Location(4, 28)
                        ),
                        new LeafField(
                            'extract',
                            'userListLang',
                            [
                                new Argument('object', $dynamicVariableReference, new Location(11, 25)),
                                new Argument('path', new Literal('lang', new Location(12, 32)), new Location(12, 25)),
                            ],
                            [],
                            new Location(10, 35)
                        ),
                    ],
                    [],
                    new Location(3, 17)
                )
            ],
            new Location(2, 19)
        );

        $executableDocument = new ExecutableDocument($document, $context);
        $executableDocument->validateAndInitialize();
        $this->assertEquals(
            [
                $queryOperation,
            ],
            $executableDocument->getRequestedOperations()
        );
    }
}
