<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\ContentProcessors;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;

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
