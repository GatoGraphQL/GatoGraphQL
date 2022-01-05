<?php

declare(strict_types=1);

namespace GraphQLAPI\MarkdownConvertor\Facades;

use PoP\Engine\App;
use GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface;

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
