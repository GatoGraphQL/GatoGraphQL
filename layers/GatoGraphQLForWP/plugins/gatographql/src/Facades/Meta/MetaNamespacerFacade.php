<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Meta;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Meta\MetaNamespacerInterface;

class MetaNamespacerFacade
{
    public static function getInstance(): MetaNamespacerInterface
    {
        /**
         * @var MetaNamespacerInterface
         */
        $service = App::getContainer()->get(MetaNamespacerInterface::class);
        return $service;
    }
}
