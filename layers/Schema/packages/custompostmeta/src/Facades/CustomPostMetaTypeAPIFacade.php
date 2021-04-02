<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMeta\Facades;

use PoPSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CustomPostMetaTypeAPIFacade
{
    public static function getInstance(): CustomPostMetaTypeAPIInterface
    {
        /**
         * @var CustomPostMetaTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(CustomPostMetaTypeAPIInterface::class);
        return $service;
    }
}
