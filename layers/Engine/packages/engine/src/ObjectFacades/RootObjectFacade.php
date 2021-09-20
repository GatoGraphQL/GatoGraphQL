<?php

declare(strict_types=1);

namespace PoP\Engine\ObjectFacades;

use PoP\Engine\ObjectModels\RootObject;
use PoP\Root\Container\ContainerBuilderFactory;

class RootObjectFacade
{
    public static function getInstance(): RootObject
    {
        $containerBuilderFactory = ContainerBuilderFactory::getInstance();
        return $containerBuilderFactory->get(RootObject::class);
    }
}
