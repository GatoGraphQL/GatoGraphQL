<?php

declare(strict_types=1);

namespace PoPSchema\Pages\Facades;

use PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PageTypeAPIFacade
{
    public static function getInstance(): PageTypeAPIInterface
    {
        /**
         * @var PageTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(PageTypeAPIInterface::class);
        return $service;
    }
}
