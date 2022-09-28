<?php

declare(strict_types=1);

namespace PoP\Engine\ObjectFacades;

use PoP\Root\App;
use PoP\Engine\ObjectModels\SuperRoot;

class SuperRootObjectFacade
{
    public static function getInstance(): SuperRoot
    {
        $containerBuilderFactory = App::getContainer();
        /** @var SuperRoot */
        return $containerBuilderFactory->get(SuperRoot::class);
    }
}
