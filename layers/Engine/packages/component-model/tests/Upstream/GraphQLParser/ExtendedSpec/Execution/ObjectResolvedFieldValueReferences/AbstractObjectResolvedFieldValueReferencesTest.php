<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Upstream\GraphQLParser\ExtendedSpec\Execution\ObjectResolvedFieldValueReferences;

use PoP\ComponentModel\GraphQLParser\ExtendedSpec\Parser\Parser;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\ObjectResolvedFieldValueReference;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
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
 *     AST entity `ObjectResolvedFieldValueReference`
 *
 * When disabled:
 *
 *   AST entity `DynamicVariableReference` remains as is
 */
abstract class AbstractObjectResolvedFieldValueReferencesTest extends AbstractTestCase
{
    private ?ParserInterface $parser = null;

    /**
     * @return array<string, mixed> [key]: Module class, [value]: Configuration
     */
    protected static function getModuleClassConfiguration(): array
    {
        $moduleClassConfiguration = parent::getModuleClassConfiguration();
        $moduleClassConfiguration[\PoP\GraphQLParser\Module::class][\PoP\GraphQLParser\Environment::ENABLE_RESOLVED_FIELD_VARIABLE_REFERENCES] = static::enabled();
        return $moduleClassConfiguration;
    }

    abstract protected static function enabled(): bool;

    protected function getParser(): ParserInterface
    {
        return $this->parser ??= new Parser();
    }

    /**
     * Referencing by alias.
     */
    public function testObjectResolvedFieldValueReferences(): void
    {
        $query = '
            {
                userList: getJSON(
                    url: "https://someurl.com/rest/users"
                )
            
                userListLang: extract(
                    object: $__userList,
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
        $variableReference = static::enabled()
            ? new ObjectResolvedFieldValueReference('_userList', $field, new Location(8, 29))
            : new VariableReference('_userList', null, new Location(8, 29));
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
                        new Argument('object', $variableReference, new Location(8, 21)),
                        new Argument('path', new Literal('lang', new Location(9, 28)), new Location(9, 21)),
                    ],
                    [],
                    new Location(7, 31)
                )
            ],
            new Location(2, 13)
        );

        $this->executeAssertion($query, $context, $queryOperation);
    }

    protected function executeAssertion(
        string $query,
        Context $context,
        QueryOperation $queryOperation
    ): void {
        $executableDocument = new ExecutableDocument(
            $this->getParser()->parse($query),
            $context,
        );
        $this->assertEquals(
            [
                $queryOperation,
            ],
            $executableDocument->getDocument()->getOperations()
        );
    }

    /**
     * Adding the reference first, the field resolution after,
     * must not work (as to avoid potential circular references)
     */
    public function testNoInverseOrder(): void
    {
        $query = '
            {
                userListLang: extract(
                    object: $__userList,
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
        $variableReference = new VariableReference('_userList', null, new Location(4, 29));
        $queryOperation = new QueryOperation(
            '',
            [],
            [],
            [
                new LeafField(
                    'extract',
                    'userListLang',
                    [
                        new Argument('object', $variableReference, new Location(4, 21)),
                        new Argument('path', new Literal('lang', new Location(5, 28)), new Location(5, 21)),
                    ],
                    [],
                    new Location(3, 31)
                ),
                $field,
            ],
            new Location(2, 13)
        );

        $this->executeAssertion($query, $context, $queryOperation);
    }

    /**
     * Testing several examples cannot be achieved:
     *
     * - circular references
     * - self referencing field
     * - self referencing field from directive
     *
     * Their ASTs cannot be constructed, because there are no setters,
     * and the __construct from Field and ObjectResolvedFieldValueReference
     * depend on each other.
     *
     * Test via Integration test then.
     */
    /*
    public function disabledTestNoCircularReferences(): void
    {
        $query = '
            {
                userListLang: extract(
                    object: $__userList,
                    path: "lang"
                )

                userList: getJSON(
                    url: "https://someurl.com/rest/users",
                    source: $__userListLang
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
                new Argument(
                    'source',
                    $dynamicVariableReference2, // This reference does not exist yet!
                    new Location(10, 24)
                ),
            ],
            [],
            new Location(8, 27)
        );
        $variableReference = new VariableReference('_userList', null, new Location(4, 29));
        $variableReference->setContext($context);
        $dynamicVariableReference2 = static::enabled()
            ? new ObjectResolvedFieldValueReference('_userListLang', $field, new Location(10, 29))
            : new VariableReference('_userListLang', null, new Location(10, 29));
        if (!static::enabled()) {
            $dynamicVariableReference2->setContext($context);
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
                        new Argument('object', $variableReference, new Location(4, 21)),
                        new Argument('path', new Literal('lang', new Location(5, 28)), new Location(5, 21)),
                    ],
                    [],
                    new Location(3, 31)
                ),
                $field,
            ],
            new Location(2, 13)
        );

        $this->executeAssertion($query, $context, $queryOperation);
    }
    */

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
                    object: $__getJSON,
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
        $variableReference = static::enabled()
            ? new ObjectResolvedFieldValueReference('_getJSON', $field, new Location(8, 29))
            : new VariableReference('_getJSON', null, new Location(8, 29));
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
                        new Argument('object', $variableReference, new Location(8, 21)),
                        new Argument('path', new Literal('lang', new Location(9, 28)), new Location(9, 21)),
                    ],
                    [],
                    new Location(7, 31)
                )
            ],
            new Location(2, 13)
        );

        $this->executeAssertion($query, $context, $queryOperation);
    }

    /**
     * Variable references inside directives are not supported.
     */
    public function testReferenceInDirectives(): void
    {
        $query = '
            {
                userList: getJSON(
                    url: "https://someurl.com/rest/users"
                )
            
                userListLang: extract @default(
                    value: $__userList,
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
        $variableReference = new VariableReference('_userList', null, new Location(8, 28));
        $queryOperation = new QueryOperation(
            '',
            [],
            [],
            [
                $field,
                new LeafField(
                    'extract',
                    'userListLang',
                    [],
                    [
                        new Directive(
                            'default',
                            [
                                new Argument('value', $variableReference, new Location(8, 21)),
                            ],
                            new Location(7, 40)
                        )
                    ],
                    new Location(7, 31)
                )
            ],
            new Location(2, 13)
        );

        $this->executeAssertion($query, $context, $queryOperation);
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
                    object: $__nonExistingField,
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
        $variableReference = new VariableReference('_nonExistingField', null, new Location(8, 29));
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
                        new Argument('object', $variableReference, new Location(8, 21)),
                        new Argument('path', new Literal('lang', new Location(9, 28)), new Location(9, 21)),
                    ],
                    [],
                    new Location(7, 31)
                )
            ],
            new Location(2, 13)
        );

        $this->executeAssertion($query, $context, $queryOperation);
    }

    /**
     * Variable references inside directives are not supported,
     * but test anyway passing a reference that does not exist.
     */
    public function testNonExistingFieldVariableReferencesInDirectives(): void
    {
        $query = '
            {
                userList: getJSON(
                    url: "https://someurl.com/rest/users"
                )
            
                userListLang: extract @default(
                    value: $__nonExistingField,
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
        $variableReference = new VariableReference('_nonExistingField', null, new Location(8, 28));
        $queryOperation = new QueryOperation(
            '',
            [],
            [],
            [
                $field,
                new LeafField(
                    'extract',
                    'userListLang',
                    [],
                    [
                        new Directive(
                            'default',
                            [
                                new Argument('value', $variableReference, new Location(8, 21)),
                            ],
                            new Location(7, 40)
                        )
                    ],
                    new Location(7, 31)
                )
            ],
            new Location(2, 13)
        );

        $this->executeAssertion($query, $context, $queryOperation);
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
                    object: $__getJSON,
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
        $variableReference = new VariableReference('_getJSON', null, new Location(8, 29));
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
                        new Argument('object', $variableReference, new Location(8, 21)),
                        new Argument('path', new Literal('lang', new Location(9, 28)), new Location(9, 21)),
                    ],
                    [],
                    new Location(7, 31)
                )
            ],
            new Location(2, 13)
        );

        $this->executeAssertion($query, $context, $queryOperation);
    }

    /**
     * The field cannot be resolved within a fragment.
     */
    public function testInFragments(): void
    {
        $query = '
            query {
                self {
                    ...RootData

                    userListLang: extract(
                        object: $__userList,
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
        $variableReference = new VariableReference('_userList', null, new Location(7, 33));
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
                                new Argument('object', $variableReference, new Location(7, 25)),
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

        $this->executeAssertion($query, $context, $queryOperation);
    }

    /**
     * The field cannot be resolved within an inline fragment.
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
                        object: $__userList,
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
        $variableReference = new VariableReference('_userList', null, new Location(11, 33));
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
                                new Argument('object', $variableReference, new Location(11, 25)),
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

        $this->executeAssertion($query, $context, $queryOperation);
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
                        object: $__userList,
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
        $variableReference = new VariableReference('_userList', null, new Location(9, 33));
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
                                new Argument('object', $variableReference, new Location(9, 25)),
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

        $this->executeAssertion($query, $context, $queryOperation);
    }
}
