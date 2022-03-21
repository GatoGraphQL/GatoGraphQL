<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution\ResolvedFieldVariableReferences;

use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReference;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\ResolvedFieldVariableReference;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\AbstractTestCase;

/**
 * When ENABLE_RESOLVED_FIELD_VARIABLE_REFERENCES is enabled:
 *
 *   If the "Field Value Reference" is valid:
 *   - Reference by alias
 *   - Reference by fieldName
 *   - Reference appearing before resolution
 *  *
 *   Then:
 *     AST entity `DynamicVariableReference` must be replaced with
 *     AST entity `ResolvedFieldVariableReference`
 *
 * When disabled:
 *
 *   AST entity `DynamicVariableReference` remains as is
 */
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

    /**
     * Referencing by alias.
     */
    public function testResolvedFieldVariableReferences(): void
    {
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
        $context = new Context('');
        $field = new LeafField(
            'getJSON',
            'userList',
            [
                new Argument(
                    'url',
                    new Literal(
                        'https://someurl.com/rest/users',
                        new Location(4, 27)
                    ),
                    new Location(4, 21)
                ),
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

        $this->executeValidation($query, $context, $queryOperation);
    }

    protected function executeValidation(
        string $query,
        Context $context,
        QueryOperation $queryOperation
    ): void {
        $executableDocument = new ExecutableDocument(
            $this->getParser()->parse($query),
            $context,
        );
        $executableDocument->validateAndInitialize();
        $this->assertEquals(
            [
                $queryOperation,
            ],
            $executableDocument->getRequestedOperations()
        );
    }

    /**
     * Adding the reference first, the field resolution after.
     */
    public function testInverseOrder(): void
    {
        $query = '
            {
                userListLang: extract(
                    object: $_userList,
                    path: "lang"
                )

                userList: getJSON(
                    url: "https://someurl.com/rest/users"
                )
            }
        ';
        $context = new Context('');
        $field = new LeafField(
            'getJSON',
            'userList',
            [
                new Argument(
                    'url',
                    new Literal(
                        'https://someurl.com/rest/users',
                        new Location(9, 27)
                    ),
                    new Location(9, 21)
                ),
            ],
            [],
            new Location(8, 27)
        );
        $dynamicVariableReference = static::enabled()
            ? new ResolvedFieldVariableReference('_userList', $field, new Location(4, 29))
            : new DynamicVariableReference('_userList', new Location(4, 29));
        if (!static::enabled()) {
            $dynamicVariableReference->setContext($context);
        }
        $queryOperation = new QueryOperation(
            '',
            [],
            [],
            [
                new LeafField(
                    'extract',
                    'userListLang',
                    [
                        new Argument('object', $dynamicVariableReference, new Location(4, 21)),
                        new Argument('path', new Literal('lang', new Location(5, 28)), new Location(5, 21)),
                    ],
                    [],
                    new Location(3, 31)
                ),
                $field,
            ],
            new Location(2, 13)
        );

        $this->executeValidation($query, $context, $queryOperation);
    }

    /**
     * Referencing the fieldName.
     */
    public function testWithoutAlias(): void
    {
        $query = '
            {
                getJSON(
                    url: "https://someurl.com/rest/users"
                )
            
                userListLang: extract(
                    object: $_getJSON,
                    path: "lang"
                )
            }
        ';
        $context = new Context('');
        $field = new LeafField(
            'getJSON',
            null,
            [
                new Argument(
                    'url',
                    new Literal(
                        'https://someurl.com/rest/users',
                        new Location(4, 27)
                    ),
                    new Location(4, 21)
                ),
            ],
            [],
            new Location(3, 17)
        );
        $dynamicVariableReference = static::enabled()
            ? new ResolvedFieldVariableReference('_getJSON', $field, new Location(8, 29))
            : new DynamicVariableReference('_getJSON', new Location(8, 29));
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

        $this->executeValidation($query, $context, $queryOperation);
    }

    /**
     * Remove the directives added to the resolved field from its reference.
     */
    public function testRemoveDirectives(): void
    {
        $query = '
            {
                userList: getJSON(
                    url: "https://someurl.com/rest/users"
                ) @skip(if: true)
            
                userListLang: extract(
                    object: $_userList,
                    path: "lang"
                )
            }
        ';
        $context = new Context('');
        $directive = new Directive(
            'skip',
            [
                new Argument('if', new Literal(true, new Location(5, 29)), new Location(5, 25)),
            ],
            new Location(5, 20)
        );
        $field = new LeafField(
            'getJSON',
            'userList',
            [
                new Argument(
                    'url',
                    new Literal(
                        'https://someurl.com/rest/users',
                        new Location(4, 27)
                    ),
                    new Location(4, 21)
                ),
            ],
            [
                $directive
            ],
            new Location(3, 27)
        );
        // Remove the directives
        $referencedField = new LeafField(
            $field->getName(),
            $field->getAlias(),
            $field->getArguments(),
            [],
            $field->getLocation()
        );
        $dynamicVariableReference = static::enabled()
            ? new ResolvedFieldVariableReference('_userList', $referencedField, new Location(8, 29))
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

        $this->executeValidation($query, $context, $queryOperation);
    }

    /**
     * The referenced field does not exist (whether as fieldName or alias).
     */
    public function testNonExistingFieldVariableReferences(): void
    {
        $query = '
            {
                userList: getJSON(
                    url: "https://someurl.com/rest/users"
                )
            
                userListLang: extract(
                    object: $_nonExistingField,
                    path: "lang"
                )
            }
        ';
        $context = new Context('');
        $field = new LeafField(
            'getJSON',
            'userList',
            [
                new Argument(
                    'url',
                    new Literal(
                        'https://someurl.com/rest/users',
                        new Location(4, 27)
                    ),
                    new Location(4, 21)
                ),
            ],
            [],
            new Location(3, 27)
        );
        $dynamicVariableReference = new DynamicVariableReference('_nonExistingField', new Location(8, 29));
        $dynamicVariableReference->setContext($context);
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

        $this->executeValidation($query, $context, $queryOperation);
    }

    /**
     * Referencing the fieldName does not work if the field has an alias;
     */
    public function testMatchingFieldNameButNotAlias(): void
    {
        $query = '
            {
                userList: getJSON(
                    url: "https://someurl.com/rest/users"
                )
            
                userListLang: extract(
                    object: $_getJSON,
                    path: "lang"
                )
            }
        ';
        $context = new Context('');
        $field = new LeafField(
            'getJSON',
            'userList',
            [
                new Argument(
                    'url',
                    new Literal(
                        'https://someurl.com/rest/users',
                        new Location(4, 27)
                    ),
                    new Location(4, 21)
                ),
            ],
            [],
            new Location(3, 27)
        );
        $dynamicVariableReference = new DynamicVariableReference('_getJSON', new Location(8, 29));
        $dynamicVariableReference->setContext($context);
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

        $this->executeValidation($query, $context, $queryOperation);
    }

    /**
     * The field can be resolved within a fragment.
     */
    public function testInFragments(): void
    {
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
        $context = new Context('');
        $field = new LeafField(
            'getJSON',
            'userList',
            [
                new Argument(
                    'url',
                    new Literal(
                        'https://someurl.com/rest/users',
                        new Location(15, 27)
                    ),
                    new Location(15, 21)
                ),
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

        $this->executeValidation($query, $context, $queryOperation);
    }

    /**
     * The field can be resolved within an inline fragment.
     */
    public function testInInlineFragments(): void
    {
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
        $context = new Context('');
        $field = new LeafField(
            'getJSON',
            'userList',
            [
                new Argument(
                    'url',
                    new Literal(
                        'https://someurl.com/rest/users',
                        new Location(6, 35)
                    ),
                    new Location(6, 29)
                ),
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

        $this->executeValidation($query, $context, $queryOperation);
    }

    /**
     * The reference must be done for a field on the same query block
     * (so that it will be resolved on the same Engine Iteration).
     */
    public function testDifferentQueryBlock(): void
    {
        $query = '
            {
                userList: getJSON(
                    url: "https://someurl.com/rest/users"
                )
            
                self {
                    userListLang: extract(
                        object: $_userList,
                        path: "lang"
                    )
                }
            }
        ';
        $context = new Context('');
        $field = new LeafField(
            'getJSON',
            'userList',
            [
                new Argument(
                    'url',
                    new Literal(
                        'https://someurl.com/rest/users',
                        new Location(4, 27)
                    ),
                    new Location(4, 21)
                ),
            ],
            [],
            new Location(3, 27)
        );
        $dynamicVariableReference = new DynamicVariableReference('_userList', new Location(9, 33));
        $dynamicVariableReference->setContext($context);
        $queryOperation = new QueryOperation(
            '',
            [],
            [],
            [
                $field,
                new RelationalField(
                    'self',
                    null,
                    [],
                    [
                        new LeafField(
                            'extract',
                            'userListLang',
                            [
                                new Argument('object', $dynamicVariableReference, new Location(9, 25)),
                                new Argument('path', new Literal('lang', new Location(10, 32)), new Location(10, 25)),
                            ],
                            [],
                            new Location(8, 35)
                        ),
                    ],
                    [],
                    new Location(7, 17)
                )
            ],
            new Location(2, 13)
        );

        $this->executeValidation($query, $context, $queryOperation);
    }
}
