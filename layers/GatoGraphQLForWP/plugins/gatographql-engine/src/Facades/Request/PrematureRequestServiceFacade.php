<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Request;

use GatoGraphQL\GatoGraphQL\Request\PrematureRequestServiceInterface;
use PoP\Root\App;

class PrematureRequestServiceFacade
{
    public static function getInstance(): PrematureRequestServiceInterface
    {
        /**
         * @var PrematureRequestServiceInterface
         */
        $service = App::getContainer()->get(PrematureRequestServiceInterface::class);
        return $service;
    }
}
