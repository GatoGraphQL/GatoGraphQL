<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface HasInterfacesTypeInterface extends TypeInterface
{
    /**
     * Return the interfaces through their ID representation: Kind + Name
     */
    public function getInterfaceIDs(): array;
}
