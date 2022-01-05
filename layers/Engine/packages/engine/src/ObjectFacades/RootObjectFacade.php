<?php

declare(strict_types=1);

namespace PoP\Engine\ObjectFacades;

use PoP\Engine\ObjectModels\Root;
use PoP\Root\Container\ContainerBuilderFactory;

class RootObjectFacade
{
    public static function getInstance(): Root
    {
        $containerBuilderFactory = \PoP\Engine\App::getContainerBuilderFactory()->getInstance();
        return $containerBuilderFactory->get(Root::class);
    }
}
