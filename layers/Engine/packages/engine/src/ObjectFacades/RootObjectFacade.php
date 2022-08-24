<?php

declare(strict_types=1);

namespace PoP\Engine\ObjectFacades;

use PoP\Root\App;
use PoP\Engine\ObjectModels\Root;

class RootObjectFacade
{
    public static function getInstance(): Root
    {
        $containerBuilderFactory = App::getContainer();
        /** @var Root */
        return $containerBuilderFactory->get(Root::class);
    }
}
