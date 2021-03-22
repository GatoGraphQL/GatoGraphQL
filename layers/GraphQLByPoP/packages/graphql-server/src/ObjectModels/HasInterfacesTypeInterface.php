<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface HasInterfacesTypeInterface
{
    public function getInterfaces(): array;
    /**
     * Return the interfaces through their ID representation: Kind + Name
     */
    public function getInterfaceIDs(): array;
}
