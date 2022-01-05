<?php

declare(strict_types=1);

namespace PoPSchema\Pages\Facades;

use PoP\Engine\App;
use PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface;

class PageTypeAPIFacade
{
    public static function getInstance(): PageTypeAPIInterface
    {
        /**
         * @var PageTypeAPIInterface
         */
        $service = App::getContainer()->get(PageTypeAPIInterface::class);
        return $service;
    }
}
