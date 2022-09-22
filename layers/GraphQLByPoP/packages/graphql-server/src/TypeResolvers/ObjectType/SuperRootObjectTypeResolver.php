<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\SuperRoot;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SuperRootTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\CanonicalTypeNameTypeResolverTrait;

class SuperRootObjectTypeResolver extends AbstractObjectTypeResolver
{
    use CanonicalTypeNameTypeResolverTrait;

    private ?SuperRootTypeDataLoader $superRootTypeDataLoader = null;

    final public function setSuperRootTypeDataLoader(SuperRootTypeDataLoader $superRootTypeDataLoader): void
    {
        $this->superRootTypeDataLoader = $superRootTypeDataLoader;
    }
    final protected function getSuperRootTypeDataLoader(): SuperRootTypeDataLoader
    {
        /** @var SuperRootTypeDataLoader */
        return $this->superRootTypeDataLoader ??= $this->instanceManager->getInstance(SuperRootTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'SuperRoot';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('(Internal) Super Root type, starting from which the query is executed', 'engine');
    }

    public function getID(object $object): string|int|null
    {
        /** @var SuperRoot */
        $superRoot = $object;
        return $superRoot->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getSuperRootTypeDataLoader();
    }
}
