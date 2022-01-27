<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Facades\Error;

use PoP\Root\App;
use PoP\GraphQLParser\Error\GraphQLErrorMessageProviderInterface;

class GraphQLErrorMessageProviderFacade
{
    public static function getInstance(): GraphQLErrorMessageProviderInterface
    {
        /**
         * @var GraphQLErrorMessageProviderInterface
         */
        $service = App::getContainer()->get(GraphQLErrorMessageProviderInterface::class);
        return $service;
    }
}
