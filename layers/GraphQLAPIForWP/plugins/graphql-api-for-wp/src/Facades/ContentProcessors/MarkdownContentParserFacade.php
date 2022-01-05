<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\ContentProcessors;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;

class MarkdownContentParserFacade
{
    public static function getInstance(): MarkdownContentParserInterface
    {
        /**
         * @var MarkdownContentParserInterface
         */
        $service = App::getContainer()->get(MarkdownContentParserInterface::class);
        return $service;
    }
}
