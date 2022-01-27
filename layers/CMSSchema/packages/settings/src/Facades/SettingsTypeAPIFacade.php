<?php

declare(strict_types=1);

namespace PoPCMSSchema\Settings\Facades;

use PoP\Root\App;
use PoPCMSSchema\Settings\TypeAPIs\SettingsTypeAPIInterface;

class SettingsTypeAPIFacade
{
    public static function getInstance(): SettingsTypeAPIInterface
    {
        /**
         * @var SettingsTypeAPIInterface
         */
        $service = App::getContainer()->get(SettingsTypeAPIInterface::class);
        return $service;
    }
}
