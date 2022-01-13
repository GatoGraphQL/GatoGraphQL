<?php

declare(strict_types=1);

namespace PoP\Root\Facades\Translation;

use PoP\Root\App;
use PoP\Root\Translation\TranslationAPIInterface;

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
