<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\Facades;

use PoP\Root\App;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;

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
