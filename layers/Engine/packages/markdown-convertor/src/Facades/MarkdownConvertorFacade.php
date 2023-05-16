<?php

declare(strict_types=1);

namespace GatoGraphQL\MarkdownConvertor\Facades;

use PoP\Root\App;
use GatoGraphQL\MarkdownConvertor\MarkdownConvertorInterface;

class MarkdownConvertorFacade
{
    public static function getInstance(): MarkdownConvertorInterface
    {
        /**
         * @var MarkdownConvertorInterface
         */
        $service = App::getContainer()->get(MarkdownConvertorInterface::class);
        return $service;
    }
}
