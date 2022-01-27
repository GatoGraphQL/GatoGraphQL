<?php

declare(strict_types=1);

namespace PoP\Engine\Upstream\ComponentModel\Parser;

use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\Root\AbstractTestCase;

abstract class AbstractMetaDirectiveTest extends AbstractTestCase
{
    protected static function getComponentClassConfiguration(): array
    {
        $componentClassConfiguration = parent::getComponentClassConfiguration();
        $componentClassConfiguration[\PoP\GraphQLParser\Component::class][\PoP\GraphQLParser\Environment::ENABLE_COMPOSABLE_DIRECTIVES] = static::enableComposableDirectives();
        return $componentClassConfiguration;
    }

    abstract protected static function enableComposableDirectives(): bool;

    protected function getParser(): ParserInterface
    {
        return $this->getService(ParserInterface::class);
    }

    /**
     * @dataProvider queryWithMetaDirectiveProvider
     */
    public function testMetaDirectives(string $query, Document $expectedDocument)
    {
        $parser = $this->getParser();

        $parsedDocument = $parser->parse($query);

        $this->assertEquals($expectedDocument, $parsedDocument);
    }

    abstract public function queryWithMetaDirectiveProvider();
}
