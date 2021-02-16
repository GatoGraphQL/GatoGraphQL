<?php

declare(strict_types=1);

namespace PoP\Translation\Facades;

use PoP\Translation\TranslationAPIInterface;
use PoP\Root\Container\SystemContainerBuilderFactory;

class SystemTranslationAPIFacade
{
    public static function getInstance(): TranslationAPIInterface
    {
        /**
         * @var TranslationAPIInterface
         */
        $service = SystemContainerBuilderFactory::getInstance()->get(TranslationAPIInterface::class);
        return $service;
    }
}
