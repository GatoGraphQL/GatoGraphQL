<?php

declare(strict_types=1);

namespace PoPSchema\Tags\Facades;

use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class TagTypeAPIFacade
{
    public static function getInstance(): TagTypeAPIInterface
    {
        /**
         * @var TagTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(TagTypeAPIInterface::class);
        return $service;
    }
}
