<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectFacades;

use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRootObject;
use PoP\Root\Container\ContainerBuilderFactory;

class MutationRootObjectFacade
{
    public static function getInstance(): MutationRootObject
    {
        $containerBuilderFactory = ContainerBuilderFactory::getInstance();
        return $containerBuilderFactory->get(MutationRootObject::class);
    }
}
