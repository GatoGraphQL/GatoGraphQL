<?php

declare(strict_types=1);

namespace PoP\EngineWP\Facades\HelperServices;

use PoP\Root\App;
use PoP\EngineWP\HelperServices\TemplateHelpersInterface;

class TemplateHelpersFacade
{
    public static function getInstance(): TemplateHelpersInterface
    {
        /**
         * @var TemplateHelpersInterface
         */
        $service = App::getContainer()->get(TemplateHelpersInterface::class);
        return $service;
    }
}
