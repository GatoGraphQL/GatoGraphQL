<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution\MultiFieldDirectives;

use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\ParserInterface;
use PoP\Root\AbstractTestCase;

abstract class AbstractMultiFieldDirectiveTest extends AbstractTestCase
{
    protected static function getModuleClassConfiguration(): array
    {
        $moduleClassConfiguration = parent::getModuleClassConfiguration();
        $moduleClassConfiguration[\PoP\GraphQLParser\Module::class][\PoP\GraphQLParser\Environment::ENABLE_MULTIFIELD_DIRECTIVES] = static::enableMultiFieldDirectives();
        return $moduleClassConfiguration;
    }

    abstract protected static function enableMultiFieldDirectives(): bool;

    protected function getParser(): ParserInterface
    {
        return $this->getService(ParserInterface::class);
    }

    /**
     * @dataProvider queryWithMultiFieldDirectiveProvider
     */
    public function testMultiFieldDirectives(string $query, Document $expectedDocument)
    {
        $parser = $this->getParser();

        $parsedDocument = $parser->parse($query);

        $this->assertEquals($expectedDocument, $parsedDocument);
    }

    abstract public function queryWithMultiFieldDirectiveProvider();
}
