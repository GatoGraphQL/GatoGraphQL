<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\Facades;

use PoP\Root\App;
use PoPWPSchema\PageBuilder\Services\PageBuilderServiceInterface;

class PageBuilderServiceFacade
{
    public static function getInstance(): PageBuilderServiceInterface
    {
        /**
         * @var PageBuilderServiceInterface
         */
        $service = App::getContainer()->get(PageBuilderServiceInterface::class);
        return $service;
    }
}
