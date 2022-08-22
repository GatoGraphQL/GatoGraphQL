<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface HasInterfacesTypeInterface extends NamedTypeInterface
{
    /**
     * Return the interfaces through their ID representation: Kind + Name
     * @return string[]
     */
    public function getInterfaceIDs(): array;
}
