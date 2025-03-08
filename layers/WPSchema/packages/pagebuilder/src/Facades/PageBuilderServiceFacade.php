<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\Facades;

use PoP\Root\App;
use PoPWPSchema\PageBuilder\Services\PageBuilderInterface;

class PageBuilderFacade
{
    public static function getInstance(): PageBuilderInterface
    {
        /**
         * @var PageBuilderInterface
         */
        $service = App::getContainer()->get(PageBuilderInterface::class);
        return $service;
    }
}
