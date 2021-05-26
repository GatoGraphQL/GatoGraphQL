<?php

declare(strict_types=1);

namespace PoPSchema\Media\ConditionalOnComponent\Users\Facades;

use PoPSchema\Media\ConditionalOnComponent\Users\TypeAPIs\MediaTypeAPIInterface;
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
