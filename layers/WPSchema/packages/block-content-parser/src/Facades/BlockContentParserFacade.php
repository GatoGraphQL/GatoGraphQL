<?php

declare(strict_types=1);

namespace PoPWPSchema\BlockContentParser\Facades;

use PoP\Root\App;
use PoPWPSchema\BlockContentParser\BlockContentParserInterface;

class BlockContentParserFacade
{
    public static function getInstance(): BlockContentParserInterface
    {
        /**
         * @var BlockContentParserInterface
         */
        $service = App::getContainer()->get(BlockContentParserInterface::class);
        return $service;
    }
}
