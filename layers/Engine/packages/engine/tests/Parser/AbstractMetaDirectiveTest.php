<?php

declare(strict_types=1);

namespace PoP\Engine\Parser;

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
}
