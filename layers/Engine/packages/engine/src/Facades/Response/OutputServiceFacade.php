<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\Response;

use PoP\Root\App;
use PoP\Engine\Response\OutputServiceInterface;

class OutputServiceFacade
{
    public static function getInstance(): OutputServiceInterface
    {
        /**
         * @var OutputServiceInterface
         */
        $service = App::getContainer()->get(OutputServiceInterface::class);
        return $service;
    }
}
