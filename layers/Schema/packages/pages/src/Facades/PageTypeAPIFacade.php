<?php

declare(strict_types=1);

namespace PoPSchema\Pages\Facades;

use PoP\Engine\App;
use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface;

class PageTypeAPIFacade
{
    public static function getInstance(): PageTypeAPIInterface
    {
        /**
         * @var PageTypeAPIInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(PageTypeAPIInterface::class);
        return $service;
    }
}
