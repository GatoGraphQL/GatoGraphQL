<?php

declare(strict_types=1);

namespace PoPSchema\Media\Facades;

use PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class MediaTypeAPIFacade
{
    public static function getInstance(): MediaTypeAPIInterface
    {
        /**
         * @var MediaTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(MediaTypeAPIInterface::class);
        return $service;
    }
}
