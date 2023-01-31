<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\WPDataModel;

use GraphQLAPI\GraphQLAPI\WPDataModel\WPDataModelProviderInterface;
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
