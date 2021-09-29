<?php

declare(strict_types=1);

namespace PoP\Translation\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Translation\TranslationAPIInterface;

class TranslationAPIFacade
{
    public static function getInstance(): TranslationAPIInterface
    {
        /**
         * @var TranslationAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(TranslationAPIInterface::class);
        return $service;
    }
}
