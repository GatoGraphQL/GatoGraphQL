<?php

declare(strict_types=1);

namespace GraphQLAPI\MarkdownConvertor\Facades;

use PoP\Engine\App;
use GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class MarkdownConvertorFacade
{
    public static function getInstance(): MarkdownConvertorInterface
    {
        /**
         * @var MarkdownConvertorInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(MarkdownConvertorInterface::class);
        return $service;
    }
}
