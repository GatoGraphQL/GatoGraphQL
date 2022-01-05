<?php

declare(strict_types=1);

namespace PoPSchema\Settings\Facades;

use PoP\Engine\App;
use PoPSchema\Settings\TypeAPIs\SettingsTypeAPIInterface;

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
