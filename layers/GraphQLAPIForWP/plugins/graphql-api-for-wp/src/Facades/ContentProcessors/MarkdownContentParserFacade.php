<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\ContentProcessors;

use PoP\Engine\App;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class MarkdownContentParserFacade
{
    public static function getInstance(): MarkdownContentParserInterface
    {
        /**
         * @var MarkdownContentParserInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(MarkdownContentParserInterface::class);
        return $service;
    }
}
