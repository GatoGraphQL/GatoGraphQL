<?php

declare(strict_types=1);

namespace PoP\Engine\Upstream\ComponentModel\Parser;

use PoP\GraphQLParser\Parser\Ast\Document;
use PoP\GraphQLParser\Parser\ExtendedParserInterface;
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

    protected function getParser(): ExtendedParserInterface
    {
        return $this->getService(ExtendedParserInterface::class);
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
