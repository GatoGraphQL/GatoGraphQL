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
}
