<?php

declare(strict_types=1);

namespace PoPSchema\Settings\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\Settings\TypeAPIs\SettingsTypeAPIInterface;

class SettingsTypeAPIFacade
{
    public static function getInstance(): SettingsTypeAPIInterface
    {
        /**
         * @var SettingsTypeAPIInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(SettingsTypeAPIInterface::class);
        return $service;
    }
}
