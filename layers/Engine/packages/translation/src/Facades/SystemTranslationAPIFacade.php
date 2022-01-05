<?php

declare(strict_types=1);

namespace PoP\Translation\Facades;

use PoP\Root\App;
use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Translation\TranslationAPIInterface;

class SystemTranslationAPIFacade
{
    public static function getInstance(): TranslationAPIInterface
    {
        /**
         * @var TranslationAPIInterface
         */
        $service = App::getSystemContainerBuilderFactory()->getInstance()->get(TranslationAPIInterface::class);
        return $service;
    }
}
