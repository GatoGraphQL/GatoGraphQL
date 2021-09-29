<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMeta\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;

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
