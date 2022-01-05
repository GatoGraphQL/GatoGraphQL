<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectFacades;

use PoP\Engine\App;
use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;

class MutationRootObjectFacade
{
    public static function getInstance(): MutationRoot
    {
        $containerBuilderFactory = App::getContainerBuilderFactory()->getInstance();
        return $containerBuilderFactory->get(MutationRoot::class);
    }
}
