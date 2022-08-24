<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectFacades;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;

class MutationRootObjectFacade
{
    public static function getInstance(): MutationRoot
    {
        $containerBuilderFactory = App::getContainer();
        /** @var MutationRoot */
        return $containerBuilderFactory->get(MutationRoot::class);
    }
}
