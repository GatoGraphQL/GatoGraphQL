<?php

declare(strict_types=1);

namespace PoP\Translation\Facades;

use PoP\Root\App;
use PoP\Translation\TranslationAPIInterface;

class SystemTranslationAPIFacade
{
    public static function getInstance(): TranslationAPIInterface
    {
        /**
         * @var TranslationAPIInterface
         */
        $service = App::getSystemContainer()->get(TranslationAPIInterface::class);
        return $service;
    }
}
