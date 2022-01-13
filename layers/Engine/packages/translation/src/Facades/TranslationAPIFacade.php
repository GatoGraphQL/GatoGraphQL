<?php

declare(strict_types=1);

namespace PoP\Translation\Facades;

use PoP\Root\App;
use PoP\Root\Translation\TranslationAPIInterface;

class TranslationAPIFacade
{
    public static function getInstance(): TranslationAPIInterface
    {
        /**
         * @var TranslationAPIInterface
         */
        $service = App::getContainer()->get(TranslationAPIInterface::class);
        return $service;
    }
}
