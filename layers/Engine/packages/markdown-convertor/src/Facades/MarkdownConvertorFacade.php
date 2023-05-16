<?php

declare(strict_types=1);

namespace PoP\MarkdownConvertor\Facades;

use PoP\Root\App;
use PoP\MarkdownConvertor\MarkdownConvertorInterface;

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
