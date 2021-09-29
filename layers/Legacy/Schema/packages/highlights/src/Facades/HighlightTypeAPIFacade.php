<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\Highlights\TypeAPIs\HighlightTypeAPIInterface;

class HighlightTypeAPIFacade
{
    public static function getInstance(): HighlightTypeAPIInterface
    {
        /**
         * @var HighlightTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(HighlightTypeAPIInterface::class);
        return $service;
    }
}
