<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\WPDataModel;

use GatoGraphQL\GatoGraphQL\WPDataModel\WPDataModelProviderInterface;
use PoP\Root\App;

class WPDataModelProviderFacade
{
    public static function getInstance(): WPDataModelProviderInterface
    {
        /**
         * @var WPDataModelProviderInterface
         */
        $service = App::getContainer()->get(WPDataModelProviderInterface::class);
        return $service;
    }
}
