<?php

declare(strict_types=1);

namespace PoPWPSchema\BlockContentParser\Facades;

use PoP\Root\App;
use PoPWPSchema\BlockContentParser\ContentParserInterface;

class ContentParserFacade
{
    public static function getInstance(): ContentParserInterface
    {
        /**
         * @var ContentParserInterface
         */
        $service = App::getContainer()->get(ContentParserInterface::class);
        return $service;
    }
}
