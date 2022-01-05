<?php

declare(strict_types=1);

namespace PoP\Hooks\Facades;

use PoP\Hooks\HooksAPIInterface;
use PoP\Root\Container\SystemContainerBuilderFactory;

class SystemHooksAPIFacade
{
    public static function getInstance(): HooksAPIInterface
    {
        /**
         * @var HooksAPIInterface
         */
        $service = \PoP\Root\App::getSystemContainerBuilderFactory()->getInstance()->get(HooksAPIInterface::class);
        return $service;
    }
}
