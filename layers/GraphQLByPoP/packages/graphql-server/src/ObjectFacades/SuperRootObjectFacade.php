<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectFacades;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\ObjectModels\SuperRoot;

class SuperRootObjectFacade
{
    public static function getInstance(): SuperRoot
    {
        $containerBuilderFactory = App::getContainer();
        /** @var SuperRoot */
        return $containerBuilderFactory->get(SuperRoot::class);
    }
}
