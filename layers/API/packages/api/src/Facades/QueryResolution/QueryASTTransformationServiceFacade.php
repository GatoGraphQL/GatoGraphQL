<?php

declare(strict_types=1);

namespace PoPAPI\API\Facades\QueryResolution;

use PoP\Root\App;
use PoPAPI\API\QueryResolution\QueryASTTransformationServiceInterface;

class QueryASTTransformationServiceFacade
{
    public static function getInstance(): QueryASTTransformationServiceInterface
    {
        /**
         * @var QueryASTTransformationServiceInterface
         */
        $service = App::getContainer()->get(QueryASTTransformationServiceInterface::class);
        return $service;
    }
}
