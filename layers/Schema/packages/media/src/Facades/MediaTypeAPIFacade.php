<?php

declare(strict_types=1);

namespace PoPSchema\Media\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface;

class MediaTypeAPIFacade
{
    public static function getInstance(): MediaTypeAPIInterface
    {
        /**
         * @var MediaTypeAPIInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(MediaTypeAPIInterface::class);
        return $service;
    }
}
