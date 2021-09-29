<?php

declare(strict_types=1);

namespace PoP\Translation\Facades;

use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Translation\TranslationAPIInterface;

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
